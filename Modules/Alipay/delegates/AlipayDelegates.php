<?php
/**
 * Created by PhpStorm.
 * User: xiaojian
 * Date: 2016/11/21
 * Time: 17:15
 */

namespace Moon\Modules\Alipay\delegates;


use Moon\Helper\EmailHelper;
use Moon\Lib\Alipay\Alipay;
use Moon\Lib\ConfigData;
use Moon\Lib\Framework\BaseDelegates;
use Moon\Lib\Log\Log;
use Moon\Lib\Log\StatisticsLog;
use Moon\Model\Mysql\MemberMysql;
use Moon\Model\Mysql\UserPayMysql;
use Moon\Model\Redis\CommonRedis;
use Moon\Model\Redis\MemberRedis;
use Moon\Modules\Member\delegates\MemberData;

class AlipayDelegates extends BaseDelegates
{
    public function pay($params){
        $user_pay = new UserPayMysql();

        $config = ConfigData::getAlipayGoods();

        $jsonData["body"] = $config[$params['goods_id']]['Body'];
        $jsonData["subject"] = $config[$params['goods_id']]['Subject'];
        $jsonData["out_trade_no"] = "";
        $jsonData["timeout_express"] = "90m";
        $jsonData["total_amount"] = number_format($config[$params['goods_id']]['TotalAmount'], 2);
        $jsonData["seller_id"] = Alipay::$seller_id;
        $jsonData["product_code"] = "QUICK_MSECURITY_PAY";
        $time = time();

        //生成订单
        $id = $user_pay->add([
            'amount'=>$jsonData["total_amount"],
            'create_time'=>$time,
            'update_time'=>$time,
            'user_id'=>$params['user_id'],
            'goods_id'=>$params['goods_id']
        ]);

        //根据订单id生成订单号
        $out_trade_no = date("YmdHis") . $id . mt_rand(1000, 9999);
        $jsonData["out_trade_no"] = $out_trade_no;

        $user_pay->modById(['out_trade_no'=>$out_trade_no],$id);

        $data['app_id'] = Alipay::$appid;
        $data['method'] = 'alipay.trade.app.pay';
        $data['format'] = 'JSON';
        $data['charset'] = 'utf-8';
        $data['sign_type'] = 'RSA';
        $data['timestamp'] = date("Y-m-d H:i:s");
        $data['version'] = '1.0';
        $data['notify_url'] = "http://106.75.31.178:8080/alipay/alipay-notify/notify";
        $data['biz_content'] = json_encode($jsonData);
        ksort($data);
        $data['sign'] = rawurlencode(Alipay::genSign($data));


        $data['timestamp'] = rawurlencode($data['timestamp']);
        $data['biz_content'] = rawurlencode($data['biz_content']);
        $data['notify_url'] = rawurlencode($data['notify_url']);

        $result = Alipay::createLinkstring($data);

        return ['error'=>0,'result'=>$result];
    }

    public function payReport($params){
        $user_pay = new UserPayMysql();

        $report = json_decode($params['report'], true);

        if ($report['resultStatus'] != 9000) return ['error'=>6,'id'=>0];

        $out_trade_no = $report['result']['alipay_trade_app_pay_response']['out_trade_no'];
        $trade_no = $report['result']['alipay_trade_app_pay_response']['trade_no'];

        //验签
        $string = json_encode($report['result']['alipay_trade_app_pay_response']);
        $string = stripslashes($string);
        $check = Alipay::rsaVerify($string, Alipay::$alipay_public_key_path, $report['result']['sign']);
        if (!$check) return['error'=>6];

        $order = $user_pay->getOrderByOrderId(['out_trade_no'=>$out_trade_no]);

        //用户信息
        $member_data = new MemberData($order['user_id']);
        $userinfo = $member_data->getRedis()->getUserAttributes(["role_name", "level", "username","gold"]);


        $member_redis = new MemberRedis($order['user_id']);
        $member_mysql = new MemberMysql($order['user_id']);

        $config = ConfigData::getAlipayGoods();
        $gold = $config[$order['goods_id']]['Coin'];

        //判断是否已经返利
        $bool_order = $user_pay->getOrderByOrderId(["user_id"=>$order['user_id'],"trade_status"=>2,"goods_id"=>$order['goods_id']]);
        if(!$bool_order) {
            $this->cancelNotice($order['user_id'],$order['goods_id']);

            $gold += $config[$order['goods_id']]['FirstCharge'];
        }
        if ($order['trade_status'] == 2) return ['error' => 0, 'gold' => intval($gold)];
        //判断该用户是否是第一次充值
        $old_order = $user_pay->getOrderByOrderId(["user_id"=>$order['user_id'],"trade_status"=>2]);

        //修改用户金币
        $member_mysql->updateUserInfo(['gold'=>$userinfo['gold']+$gold]);
        $member_redis->incUserAttr( 'gold', $gold);
        $user_pay->modById(['trade_status'=>2,'trade_no'=>$trade_no],$order['id']);


        if (!$old_order) {//首冲奖励
            $this->fistReward($order['user_id']);
//            StatisticsLog::pay($userinfo,$order['user_id'],$out_trade_no,$order['amount'],90001);
        }

        if($gold>=300) {//月卡
            $common_redis = new CommonRedis();
            $card = $common_redis->getMonthCard($order['user_id']);
            if(!$card){
                $this->monthCard($order['user_id']);
                StatisticsLog::pay($userinfo,$order['user_id'],$out_trade_no,$order['amount'],90002);
            }

        }

        //日志
        Log::gold($order['user_id'],$gold,"支付宝购买{$gold}个金币");

        //统计日志
        StatisticsLog::pay($userinfo,$order['user_id'],$out_trade_no,$order['amount'],$order['goods_id']);
        StatisticsLog::consume($userinfo,$order['user_id'],$gold);

        return ['error' => 0, 'gold' => intval($gold)];
    }

    public function fistReward($user_id){
        $member_redis = new MemberRedis($user_id);
        $member_mysql = new MemberMysql($user_id);
        $first_recharge = ConfigData::getFirstRecharge();
        $first_recharge = $first_recharge[1];
        $prop=[];
        $resource=[];
        if($first_recharge['Item']) {
            foreach ($first_recharge['Item'] as $val) {
                $prop[] = EmailHelper::initPropReward($val[0], $val[1]);
            }
        }
        if($first_recharge['Res']){
            foreach ($first_recharge['Res'] as $val) {
                $resource[] = EmailHelper::initResourceReward($val[0], $val[1]);
            }
        }
        EmailHelper::initPrizeEmailInfo($user_id, date("Y-m-d, H:i:s"), "第一次充值达成，请在邮箱中领取奖励", "首充奖励", $resource, $prop);
        $member_redis->updateAttribute( 'first_reward', 1 );
        $member_mysql->updateUserBaseInfo( ["first_reward"=> 1]);

    }

    public function monthCard($user_id){
        $common_redis = new CommonRedis();
        $common_redis->setMonthCard($user_id);
    }

    /**
     * 取消首冲提示
     */
    public function cancelNotice($user_id,$goods_id){
        $common_redis = new CommonRedis();
        $status = $common_redis->getFirstNotice($user_id);
        $status[$goods_id] = 0;
        $common_redis->setFirstNotice($user_id,$status);
    }

    public function firstNotice($user_id){
        $common_redis = new CommonRedis();
        $pay_mysql = new UserPayMysql();
        $status = $common_redis->getFirstNotice($user_id);
        if(!$status){
            $config = ConfigData::getAlipayGoods();
            foreach($config as $k=>$v){
                $res = $pay_mysql->getOrderByOrderId(['goods_id'=>$k,'user_id'=>$user_id]);
                if($res) $status[$k] = 0;else $status[$k] = 1;
            }
            $common_redis->setFirstNotice($user_id,$status);
        }
        return ['error'=>0,'status'=>$status];

    }
}