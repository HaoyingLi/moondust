<?php
/**
 * Created by PhpStorm.
 * User: xiaojian
 * Date: 2016/11/26
 * Time: 1:20
 */

namespace Moon\Modules\Alipay\controllers;

use Moon\Lib\Alipay\Alipay;
use Moon\Lib\Alipay\AlipayNotify;
use Moon\Lib\ConfigData;
use Moon\Lib\Log\Log;
use Moon\Lib\Log\LogSequence;
use Moon\Lib\Log\MysqlSequence;
use Moon\Lib\Log\StatisticsLog;
use Moon\Lib\Log\StatisticsLogSequence;
use Moon\Model\Mysql\MemberMysql;
use Moon\Model\Mysql\UserPayMysql;
use Moon\Model\Redis\CommonRedis;
use Moon\Model\Redis\MemberRedis;
use Moon\Modules\Member\delegates\MemberData;
use Moon\Modules\Alipay\delegates\AlipayDelegates;

/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */


class AlipayNotifyController
{
    function notifyAction()
    {
        //↓↓↓↓↓↓↓↓↓↓请在这里配置您的基本信息↓↓↓↓↓↓↓↓↓↓↓↓↓↓↓
        //合作身份者id，以2088开头的16位纯数字
        $alipay_config['partner'] = '2088521051605201';

        //商户的私钥（后缀是.pen）文件相对路径
        $alipay_config['private_key_path'] = '../Lib/Alipay/rsa_private_key.pem';

        //支付宝公钥（后缀是.pen）文件相对路径
        $alipay_config['ali_public_key_path'] = '../Lib/Alipay/alipay_public_key.pem';

        //↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑

        //签名方式 不需修改
        $alipay_config['sign_type'] = strtoupper('RSA');

        //字符编码格式 目前支持 gbk 或 utf-8
        $alipay_config['input_charset'] = strtolower('utf-8');

        //ca证书路径地址，用于curl中ssl校验
        //请保证cacert.pem文件在当前文件夹目录中
        $alipay_config['cacert'] = getcwd() . '\\cacert.pem';

        //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        $alipay_config['transport'] = 'http';
        //计算得出通知验证结果
        $alipayNotify = new AlipayNotify($alipay_config);
        $verify_result = $alipayNotify->verifyNotify();
        if ($verify_result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代


            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——

            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表

            //商户订单号

            $out_trade_no = $_POST['out_trade_no'];

            //支付宝交易号

            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];


            if ($_POST['trade_status'] == 'TRADE_FINISHED') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
                $user_pay = new UserPayMysql();
                $order = $user_pay->getOrderByOrderId(['out_trade_no'=>$out_trade_no]);

                if ($order['trade_status'] == 2) return;

                //判断该用户是否是第一次充值
                $old_order = $user_pay->getOrderByOrderId(["user_id"=>$order['user_id'],"trade_status"=>2]);

                //用户信息
                $member_data = new MemberData($order['user_id']);
                $userinfo = $member_data->getRedis()->getUserAttributes(["role_name", "level", "username","gold"]);

                $member_redis = new MemberRedis($order['user_id']);
                $member_mysql = new MemberMysql($order['user_id']);
                $alipay_delegates = new AlipayDelegates();
                if (!$old_order) {//首冲奖励
                    $alipay_delegates->fistReward($order['user_id']);
                    StatisticsLog::pay($userinfo,$order['user_id'],$out_trade_no,$order['amount'],90001);
                }


                $config = ConfigData::getAlipayGoods();
                $gold = $config[$order['goods_id']]['Coin'];

                //判断是否已经返利
                $bool_order = $user_pay->getOrderByOrderId(["user_id"=>$order['user_id'],"trade_status"=>2,"goods_id"=>$order['goods_id']]);
                if(!$bool_order) {
                    $alipay_delegates->cancelNotice($order['user_id'],$order['goods_id']);
                    $gold += $config[$order['goods_id']]['FirstCharge'];
                }

                if($gold>=300) {
                    $common_redis = new CommonRedis();
                    $card = $common_redis->getMonthCard($order['user_id']);
                    if(!$card){
                        $alipay_delegates->monthCard($order['user_id']);
                        StatisticsLog::pay($userinfo,$order['user_id'],$out_trade_no,$order['amount'],90002);
                    }
                }

                //修改用户金币
                $member_mysql->updateUserInfo(['gold'=>$userinfo['gold']+$gold]);
                $member_redis->incUserAttr( 'gold', $gold);
                $user_pay->modById(['trade_status'=>2,'trade_no'=>$trade_no],$order['id']);

                //日志
                Log::gold($order['user_id'],$gold,"支付宝购买{$gold}个金币");

                //统计日志
                StatisticsLog::pay($userinfo,$order['user_id'],$out_trade_no,$order['amount'],$order['goods_id']);
                StatisticsLog::consume($userinfo,$order['user_id'],$gold);
                LogSequence::push();
                StatisticsLogSequence::push();
            } else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                //如果有做过处理，不执行商户的业务程序

                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
                //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的

                //调试用，写文本函数记录程序运行情况是否正常
                //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
                $user_pay = new UserPayMysql();
                $order = $user_pay->getOrderByOrderId(['out_trade_no'=>$out_trade_no]);

                if ($order['trade_status'] == 2) return;

                //判断该用户是否是第一次充值
                $old_order = $user_pay->getOrderByOrderId(["user_id"=>$order['user_id'],"trade_status"=>2]);

                //用户信息
                $member_data = new MemberData($order['user_id']);
                $userinfo = $member_data->getRedis()->getUserAttributes(["role_name", "level", "username","gold"]);

                $member_redis = new MemberRedis($order['user_id']);
                $member_mysql = new MemberMysql($order['user_id']);
                $alipay_delegates = new AlipayDelegates();
                if (!$old_order) {//首冲奖励
                    $alipay_delegates->fistReward($order['user_id']);
//                    StatisticsLog::pay($userinfo,$order['user_id'],$out_trade_no,$order['amount'],90001);
                }


                $config = ConfigData::getAlipayGoods();
                $gold = $config[$order['goods_id']]['Coin'];

                //判断是否已经返利
                $bool_order = $user_pay->getOrderByOrderId(["user_id"=>$order['user_id'],"trade_status"=>2,"goods_id"=>$order['goods_id']]);
                if(!$bool_order) {
                    $alipay_delegates->cancelNotice($order['user_id'],$order['goods_id']);
                    $gold += $config[$order['goods_id']]['FirstCharge'];
                }

                if($gold>=300) {
                    $common_redis = new CommonRedis();
                    $card = $common_redis->getMonthCard($order['user_id']);
                    if(!$card){
                        $alipay_delegates->monthCard($order['user_id']);
                        StatisticsLog::pay($userinfo,$order['user_id'],$out_trade_no,$order['amount'],90002);
                    }
                }
                //修改用户金币
                $member_mysql->updateUserInfo(['gold'=>$userinfo['gold']+$gold]);
                $member_redis->incUserAttr( 'gold', $gold);
                $user_pay->modById(['trade_status'=>2,'trade_no'=>$trade_no],$order['id']);

                //日志
                Log::gold($order['user_id'],$gold,"支付宝购买{$gold}个金币");

                //统计日志
                StatisticsLog::pay($userinfo,$order['user_id'],$out_trade_no,$order['amount'],$order['goods_id']);
                StatisticsLog::consume($userinfo,$order['user_id'],$gold);
                MysqlSequence::push();
                LogSequence::push();
                StatisticsLogSequence::push();
            }

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

            echo "success";        //请不要修改或删除

            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        } else {
            //验证失败
            echo "fail";

            //调试用，写文本函数记录程序运行情况是否正常
            //logResult("这里写入想要调试的代码变量值，或其他运行的结果记录");
        }
    }
}