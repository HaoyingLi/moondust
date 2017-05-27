<?php

/**
 *
 * 作者: 袁周文(yuanzhouwen@supernano.com)
 * 时间: 2016-11-23 01:12:12
 *
 * vim: set expandtab sw=4 ts=4 sts=4
 * $Id: Controller.php 86 2012-07-30 09:30:42Z yuanzhouwen $
 */
namespace Moon\Modules\Admin\controllers;

use Moon\Lib\Alipay\Alipay;
use Moon\Lib\FHttp;
use Moon\Lib\FRequest;
use Moon\Lib\FResponse;
use Moon\Model\Mysql\RedMysql;

class RedActivityController extends BaseAdminController {

    public function ActivityListAction(){
        $redtable = new RedMysql();
        $redActivityList = $redtable->getAllActivity();

        $this->assign('redActivityList',$redActivityList);

        $this->display('red/red_list');
    }

    public function createActivityAction(){
        $this->display('red/red_detail');
    }

    public function addActivityAction()
    {
        $redtable = new RedMysql();
        if( FRequest::getPostString('ok') ){
            //验证发放密码
            $pwd = FRequest::getPostString('pwd');
            if(md5($pwd) !=='3afc0f9b52bb799ba72ec8058b2b40c4'){
                echo '<script>alert("密码错误");history.go(-1);</script>';
            }

//            //循环创建活动
//            $total = FRequest::getPostString();
//
//










            $data = [
                'activity_name'=>FRequest::getPostString('activity_name'),
                'begin_time'=>FRequest::getPostString('begin_time'),
                'end_time'=>FRequest::getPostString('end_time'),
                'limit_num'=>FRequest::getPostInt('limit_num'),
                'status'=>FRequest::getPostInt('status'),
                'prize_type'=>FRequest::getPostInt('prize_type'),
                'total_money'=>FRequest::getPostFloat('total_money'),
                'total_num'=>FRequest::getPostInt('total_num'),
                'merchant_link'=>FRequest::getPostString('merchant_link'),
            ];
            if(FRequest::getPostInt('red_id')){
                $redtable->updateRedActivity($data,FRequest::getPostInt('red_id'));
            }else{
                $res = $redtable->setRedActivity($data);
            }

            $prize_type = $data['prize_type']==1 ? 'fixed' : 'random';
            $sign_arr = [
                    "coupon_name"=>FRequest::getPostString('activity_name'),
                    "prize_type"=>$prize_type,
                    "total_money"=>FRequest::getPostFloat('total_money'),
                    "total_num"=>FRequest::getPostInt('total_num'),
                    "prize_msg"=>FRequest::getPostString('activity_name'),
                    "start_time"=>FRequest::getPostString('begin_time'),
                    "end_time"=>FRequest::getPostString('end_time'),
                    "merchant_link"=>FRequest::getPostString('merchant_link')
            ];
            $biz_content = json_encode($sign_arr);
            $timestamp = date('Y-m-d H:i:s');
            $app_id = '2016101002078763';
            $sign_biz = [
                'timestamp'=>$timestamp,
                'method'=>'alipay.marketing.campaign.cash.create',
                'app_id'=>'2016101002078763',
                'sign_type'=>'RSA',
                'version'=>'1.0',
                'biz_content'=>$biz_content,
                'charset'=>'UTF-8',
                'prize_type'=>$prize_type,
            ];
            ksort($sign_biz);
            $sign = urlencode(Alipay::genSign($sign_biz));
            $sign_biz['sign'] = $sign;
            $url = 'https://openapi.alipaydev.com/gateway.do';
            $result = FHttp::post($url,'prize_type='.$prize_type.'&charset=UTF-8&timestamp='.$timestamp.'&method=alipay.marketing.campaign.cash.create&app_id='.$app_id.'&sign_type=RSA&sign='.$sign.'&version=1.0&biz_content='.$biz_content);
print_r($result);die;

            //生成红包

           // FResponse::redirect('/admin/red-activity/activity-list');

        }else{
            $redInfo = [];
            if(FRequest::getInt('red_id')){
                $redInfo = $redtable->getRedActivity(['red_id'=>FRequest::getInt('red_id')]);
                $this->assign('redInfo',$redInfo[0]);
            }
            $this->display('red/red_detail');
        }
    }

    public function delete()
    {
        $red_id = FRequest::getInt('red_id');
        if(!$red_id) FResponse::redirect('/admin/red-activity/activity-list');

        $redtable = new RedMysql();
        $redtable->deleteActivity($red_id);
        FResponse::redirect('/admin/red-activity/activity-list');
    }
}
