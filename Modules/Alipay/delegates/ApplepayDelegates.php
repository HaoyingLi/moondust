<?php
/**
 * Created by PhpStorm.
 * User: xiaojian
 * Date: 2016/11/22
 * Time: 14:07
 */

namespace Moon\Modules\Alipay\delegates;


use Moon\Helper\EmailHelper;
use Moon\Lib\ConfigData;
use Moon\Lib\Framework\BaseDelegates;
use Moon\Lib\Log\Log;
use Moon\Lib\Log\StatisticsLog;
use Moon\Model\Mysql\MemberMysql;
use Moon\Model\Mysql\UserPayMysql;
use Moon\Model\Redis\CommonRedis;
use Moon\Model\Redis\MemberRedis;
use Moon\Modules\Member\delegates\MemberData;

class ApplepayDelegates extends BaseDelegates
{
    public function pay($params)
    {
        $user_pay = new UserPayMysql();
        $config = ConfigData::getAppleGoods();
        $total_amount = number_format($config[$params['goods_id']]['RMB'], 2);
        $time = time();

        //生成订单
        $id = $user_pay->add([
            'amount'=>$total_amount,
            'create_time'=>$time,
            'update_time'=>$time,
            'user_id'=>$params['user_id'],
            'goods_id'=>$params['goods_id'],
            'pay_type'=>2
        ]);
        //根据id生成订单号
        $out_trade_no = date("YmdHis") . $id . mt_rand(1000, 9999);

        $user_pay->modById(['out_trade_no'=>$out_trade_no],$id);

        return $out_trade_no;
    }

    public function payReport($params)
    {
        $user_pay = new UserPayMysql();
        $url = "https://sandbox.itunes.apple.com/verifyReceipt";
//      $url = "https://buy.itunes.apple.com/verifyReceipt";

        $response = $this->curlPost($url,$params['report']);
        if ($response['status'] == "0") {
            $out_trade_no = $params['out_trade_no'];
            $trade_no = $response['receipt']['in_app'][0]['transaction_id'];
            $order = $user_pay->getOrderByOrderId(['out_trade_no'=>$out_trade_no]);

            if ($order['trade_status'] == 2) return ['error' => 110002, 'out_trade_no' => $out_trade_no];
            //判断该用户是否是第一次充值
            $old_order = $user_pay->getOrderByOrderId(["user_id"=>$order['user_id'],"trade_status"=>2]);

            $member_data = new MemberData($order['user_id']);
            $userinfo = $member_data->getRedis()->getUserAttributes([ "role_name", "level", "username","gold"]);

            $member_redis = new MemberRedis($order['user_id']);
            $member_mysql = new MemberMysql($order['user_id']);

            $config = ConfigData::getAppleGoods();
            $gold = $config[$order['goods_id']]['Coin'];

            //判断是否已经返利
            $bool_order = $user_pay->getOrderByOrderId(["user_id"=>$order['user_id'],"trade_status"=>2,"goods_id"=>$order['goods_id']]);
            if(!$bool_order) $gold += $config[$order['goods_id']]['FirstCharge'];

            //修改用户金币
            $member_mysql->updateUserInfo(['gold'=>$userinfo['gold']+$gold]);
            $member_redis->incUserAttr( 'gold', $gold);
            $user_pay->modById(['trade_status'=>2,'trade_no'=>$trade_no],$order['id']);

            $alipay = new AlipayDelegates();
            if (!$old_order) {//首冲奖励
                $alipay->fistReward($order['user_id']);
                StatisticsLog::pay($userinfo,$order['user_id'],$out_trade_no,$order['amount'],90001);
            }


            //是否送月卡
            if($gold>=300) {
                $common_redis = new CommonRedis();
                $card = $common_redis->getMonthCard($order['user_id']);
                if(!$card){
                    $alipay->monthCard($order['user_id']);
                    StatisticsLog::pay($userinfo,$order['user_id'],$out_trade_no,$order['amount'],90002);
                }

            }

            //日志
            Log::gold($order['user_id'],$gold,"苹果支付购买{$gold}个金币");
            //统计日志
            StatisticsLog::applepay($userinfo,$order['user_id'],$out_trade_no,$order['amount'],$order['goods_id']);
            StatisticsLog::appleConsume($userinfo,$order['user_id'],$gold);
            return ['error'=>0, 'out_trade_no'=>$params['out_trade_no'],'gold' => intval($gold)];

        } else {
                return ['error'=>$response['status'], 'out_trade_no'=>$params['out_trade_no']];

        }



    }


    protected function curlPost($url,$apple_receipt){
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $url);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_HEADER, 0);
        curl_setopt($curl_handle, CURLOPT_POST, true);
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $apple_receipt);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl_handle, CURLOPT_SSL_VERIFYPEER, 0);
        $response_json = curl_exec($curl_handle);
        $response = json_decode($response_json, true);
        curl_close($curl_handle);
        return $response;
    }
}