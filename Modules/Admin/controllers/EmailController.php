<?php
/**
 * Created by PhpStorm.
 * User: yejunyan
 * Date: 2016/11/27
 * Time: 15:29
 */

namespace Moon\Modules\Admin\controllers;


use Moon\Helper\EmailHelper;

class EmailController extends BaseAdminController
{
    public function emailSendAction(){
        $this->display('email/email-send');
    }

    public function ajaxEmailSendAction(){
        $post = $_POST;


        //获取取用户方式
        $object = $post['object'];
        $times = $post['times'];
        $content = $post['content'];
        $title = $post['title'];
        $user_id = $post['user_id'];


        if(($content == null)||($title == null)) {
            $this->ajaxReturn(['code'=>0,'data'=>[]]);
            return;
        }

        $columns = [
            'gold'=>1,
            'dust'=>2,
            'candy_water'=>3,
            'candy_fire'=>4,
            'candy_wind'=>5,
            'candy_poison'=>6,
            'candy_wood'=>7,
            'candy_electric'=>8,
            'candy_stone'=>9
        ];

        //生成资源奖励
        $resourceKinds = array();
        foreach( $post as $key => $value ) {
            if( array_key_exists($key, $columns) && is_numeric( $value) ){
                $resourceKinds[$columns[$key]] = $value;
            };
        }

        $resources = [];
        if(!empty($resourceKinds)){
            $i = 0;
            foreach($resourceKinds as $id => $num){
                $resources[$i] = EmailHelper::initResourceReward($id,$num);
                $i++;
            }
        }

        //生成道具奖励
        $props = [];
        if( isset($post['arr_tool'])){
            $arrTool = $post['arr_tool'];
            foreach($arrTool as $Id => $num){
                $props[] = EmailHelper::initPropReward($Id,$num);
            }
        }



        if(1 == $object){
            //指定用户
            if($user_id == null){
                $this->ajaxReturn(['code'=>1,'data'=>[]]);
            }else {
                for ($k = 0; $k < $times; $k++) {
                    $date = date('Y-m-d H:i:s',time());
                    EmailHelper::initPrizeEmailInfo($user_id, $date, $content, $title, $resources, $props);
                }
                $this->ajaxReturn(['code' => 2, 'data' => []]);
            }

        } else if( 2 == $object) {
            //发全部邮件
            for ($k = 0; $k < $times; $k++) {
                EmailHelper::initModEmailInfo(time(), $content, $title, $resources, $props);
            }
            $this->ajaxReturn(['code'=>2,'data'=>[]]);

        } else {
            $this->ajaxReturn(['code'=>3,'data'=>[]]);
        }

    }
}