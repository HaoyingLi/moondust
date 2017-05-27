<?php
/**
 * Created by PhpStorm.
 * User: yuanzhouwen
 * Date: 2016/11/3
 * Time: 20:41
 */

namespace Moon\Modules\Alipay\controllers;

use Moon\Lib\Alipay\Alipay;
use Moon\Lib\Framework\BaseController;
use Moon\Modules\Alipay\delegates\AlipayDelegates;

class AlipayController extends BaseController
{
    public function __construct()
    {
        $this->origin_data = file_get_contents("php://input");
        $this->origin_data =$this->xor_enc($this->origin_data);
        $this->convertRequestParams();
    }

    public function payAction()
    {
        $this->paramsCheck( ['goods_id', 'user_id'] );

        $alipay = new AlipayDelegates();
        $res = $alipay->pay( $this->params );

        $this->jsonResponse(['code' => 0, 'data' => ['result' => $res['result']]]);

    }

    public function payReportAction()
    {
        $this->paramsCheck( ['report'] );

        $alipay = new AlipayDelegates();
        $res = $alipay->payReport( $this->params );

        if ($res['error'] != 0) $this->jsonResponse(['code' => $res['error'], 'data' => ['id'=>$res['id']]]);

        $this->jsonResponse(['code' => 0, 'data' => ['gold'=>$res['gold']]]);

    }


    //授权码签名
    public function authCodeSignAction()
    {
        //检查参数是否存在
        $this->paramsCheck( [ 'targetid' ] );

        try {
            $params = [
                'apiname' => 'com.alipay.account.auth', //服务对应的名称，常量值为com.alipay.account.auth
                'method' => 'alipay.open.auth.sdk.code.get', //接口名称，常量值为alipay.open.auth.sdk.code.get
                'app_id' => Alipay::$appid, //支付宝分配给开发者的应用ID
                'app_name' => 'mc', //调用来源方的标识，常量值为mc
                'biz_type' => 'openservice', //调用业务的类型，常量值为openservice
                'pid' => Alipay::$pid, //签约的支付宝账号对应的支付宝唯一用户号，以2088开头的16位纯数字组成
                'product_id' => 'APP_FAST_LOGIN', //产品码，常量值为APP_FAST_LOGIN
                'scope' => 'kuaijie', //授权范围，常量值为kuaijie
                'target_id' => $this->params['targetid'],//md5(uniqid(rand(), true)), //商户标识该次用户授权请求的ID，该值在商户端应保持唯一
                'auth_type' => 'AUTHACCOUNT', //标识授权类型，取值范围：AUTHACCOUNT代表授权；LOGIN代表登录
                'sign_type' => 'RSA', //签名的类型，常量值为RSA，暂不支持其他类型签名
            ];
            ksort($params);
            $params['sign'] = urlencode(Alipay::genSign($params));
            $result['infoStr'] = Alipay::createLinkstring($params);
            $this->jsonResponse(['code' => 0, 'data' => $result['infoStr']]);

        } catch (\Exception $e) {
            $result['code'] = $e->getCode();
            $result['errmsg'] = $e->getMessage();
            $this->jsonResponse(['code' => 6, 'msg' => json_encode($result), 'data' => new \stdClass()]);
        }
    }

    //授权码报告
    public function authCodeReportAction()
    {
        //检查参数是否存在
        $this->paramsCheck( [ 'report' ] );

        $report = json_decode($this->params['report'], true);

        if ($report['resultStatus'] != 9000) $this->jsonResponse(['code' => 6, 'msg' => json_encode(['resultStatus' => $report['resultStatus']]), 'data' => new \stdClass()]);

        $result = $report['result'];
        $result_arr = explode('&', $result);

        $result_array = [];
        foreach ($result_arr as $key => $val) {
            $val_arr = explode('=', $val);
            $result_array[$val_arr[0]] = $val_arr[1];
        }

        if ($result_array['result_code'] != 200) $this->jsonResponse(['code' => 6, 'msg' => json_encode(['result_code' => $result_array['result_code']]), 'data' => new \stdClass()]);

        $this->jsonResponse(['code' => 0, 'data' => ['auth_code' => $result_array['auth_code']]]);
    }

    //令牌签名
    public function tokenSignAction() {
        //检查参数是否存在
        $this->paramsCheck( [ 'auth_code' ] );

        try {
            $params = [
                'app_id' => Alipay::$appid, //支付宝分配给开发者的应用ID
                'method' => 'alipay.system.oauth.token',
                'format' => 'JSON',
                'charset' => 'utf-8',
                'sign_type' => 'RSA',
                'timestamp' => date('Y-m-d H:i:s'),
                'version' => '1.0',
                'grant_type' => 'authorization_code',
                'code' => $this->params['auth_code'],
            ];
            ksort($params);
            $params['sign'] = rawurlencode(Alipay::genSign($params));
            $params['timestamp'] = rawurlencode($params['timestamp']);
            $result['infoStr'] = Alipay::createLinkstring($params);
            $this->jsonResponse(['code' => 0, 'data' => 'https://openapi.alipay.com/gateway.do?' . $result['infoStr']]);

        } catch (\Exception $e) {
            $result['code'] = $e->getCode();
            $result['errmsg'] = $e->getMessage();
            $this->jsonResponse(['code' => 6, 'msg' => json_encode($result), 'data' => new \stdClass()]);
        }
    }

    //令牌报告
    public function tokenReportAction()
    {
        //检查参数是否存在
        $this->paramsCheck( [ 'report' ] );

        $report = json_decode($this->params['report'], true);

        if (empty($report['alipay_system_oauth_token_response'])) {
            $this->jsonResponse(['code' => 6, 'msg' => 'response null', 'data' => new \stdClass()]);
        }
        if (empty($report['sign'])) {
            $this->jsonResponse(['code' => 6, 'msg' => 'sign null', 'data' => new \stdClass()]);
        }

        $result = $report['alipay_system_oauth_token_response'];
        $string = json_encode($result);
        $string = stripslashes($string);

        $check = Alipay::rsaVerify($string, Alipay::$alipay_public_key_path, $report['sign']);

        if (!$check) {
            $this->jsonResponse(['code' => 6, 'msg' => 'sign error', 'data' => new \stdClass()]);
        }

        if (empty($result['access_token'])) {
            $this->jsonResponse(['code' => 6, 'msg' => 'token null', 'data' => new \stdClass()]);
        }

        $this->jsonResponse(['code' => 0, 'data' => ['token' => $result['access_token']]]);
    }

    //用户信息签名
    public function selfInfoSignAction()
    {
        //检查参数是否存在
        $this->paramsCheck( [ 'token' ] );

        try {
            $params = [
                'app_id' => Alipay::$appid, //支付宝分配给开发者的应用ID
                'method' => 'alipay.user.userinfo.share',
                'format' => 'JSON',
                'charset' => 'utf-8',
                'sign_type' => 'RSA',
                'timestamp' => date('Y-m-d H:i:s'),
                'version' => '1.0',
                'auth_token' => $this->params['token'],
            ];
            ksort($params);
            $params['sign'] = rawurlencode(Alipay::genSign($params));
            $params['timestamp'] = rawurlencode($params['timestamp']);
            $result['infoStr'] = Alipay::createLinkstring($params);
            $this->jsonResponse(['code' => 0, 'data' => 'https://openapi.alipay.com/gateway.do?' . $result['infoStr']]);

        } catch (\Exception $e) {
            $result['code'] = $e->getCode();
            $result['errmsg'] = $e->getMessage();
            $this->jsonResponse(['code' => 6, 'msg' => json_encode($result), 'data' => new \stdClass()]);
        }
    }

    //用户信息报告
    public function selfInfoReportAction()
    {
        //检查参数是否存在
        $this->paramsCheck( [ 'report' ] );
        $report = json_decode( $this->params['report'], true );

        if (empty($report['alipay_user_userinfo_share_response'])) {
            $this->jsonResponse(['code' => 6, 'msg' => 'response null', 'data' => new \stdClass()]);
        }
        if (empty($report['sign'])) {
            $this->jsonResponse(['code' => 6, 'msg' => 'sign null', 'data' => new \stdClass()]);
        }

        $result = $report['alipay_user_userinfo_share_response'];

        $string = json_encode($result, JSON_UNESCAPED_UNICODE);
        $string = str_replace('\/', '/', $string);
        $check = Alipay::rsaVerify($string, Alipay::$alipay_public_key_path, $report['sign']);

        if (!$check) {
            $this->jsonResponse(['code' => 6, 'msg' => 'sign error', 'data' => new \stdClass()]);
        }

        $client_response = [];
        //是否实名认证
        if (empty($result['is_certified'])) $client_response['is_certified'] = 'F';
        else $client_response['is_certified'] = $result['is_certified'];
        //是否有头像
        if (empty($result['avatar'])) $client_response['avatar'] = '';
        else $client_response['avatar'] = $result['avatar'];
        if( $client_response['avatar'] == 'https://tfsimg.alipay.com/images/partner/images/partner/T1IVXXXb0bXXXXXXXX'
            || $client_response['avatar'] == 'https:\/\/tfsimg.alipay.com\/images\/partner\/images\/partner\/T1IVXXXb0bXXXXXXXX' )
        {
            $client_response['avatar'] = '';
        }

        //是否有昵称
        if (empty($result['nick_name'])) $client_response['nick_name'] = '';
        else $client_response['nick_name'] = $result['nick_name'];
        //是否有用户id
        if (empty($result['alipay_user_id'])) $client_response['user_id'] = '';
        else $client_response['user_id'] = $result['alipay_user_id'];

        $this->jsonResponse(['code' => 0, 'data' => $client_response]);
    }

    public function firstNoticeAction(){
        $this->paramsCheck( ['user_id'] );

        $alipay = new AlipayDelegates();
        $res = $alipay->firstNotice( $this->params['user_id'] );

        $this->jsonResponse(['code' => 0, 'data' => ['status' => $res['status']]]);
    }
}