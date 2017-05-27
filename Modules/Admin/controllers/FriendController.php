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


use Moon\Lib\FTable;
use Moon\Model\Redis\CommonRedis;

class FriendController extends BaseAdminController {

    public function friendlistAction(){
        $db_id = $_REQUEST['db_id'] ? $_REQUEST['db_id'] : 0;
        $user_id = $_REQUEST['user_id'];
        if (!$db_id) {
            $this->display( 'friend/friend' );
            die;
        }
        $page = isset($_REQUEST['page']) ? $_REQUEST['page'] : 1;
        if ($page < 1) {
            $page = 1;
        }

        global $_CONF;
        $mysql_list = $_CONF['mysql'];
        $config = $mysql_list[ $db_id ];

        $friend_table = new FTable( 'user_friends', $config );
        $start = ($page - 1) * 20;
        $friend_list = $friend_table->where( [ 'user_id' => $user_id ] )->limit("$start,20")->page($page)->select();

        $user_table = new FTable( 'user', $config );

        $friend_list_r = [];
        foreach ($friend_list as $friend) {
            if (!isset($friend['friend_user_id'])) {
                continue;
            }
            $user_result = $user_table->fields( ['head_img', 'level', 'sex', 'role_sex', 'sign', 'role_name', 'nickname', 'location', 'time_last_login', 'reg_time'] )->where( [ 'user_id' => $friend['friend_user_id'] ] )->find();
            $friend_list_r[] = array_merge($user_result, $friend);
        }

//        $this->ajaxReturn( [ 'code' => 0, 'data' => $friend_list_r ] );
        $this->assign( 'friend_list', $friend_list_r );
        $this->assign( 'page', $page );
        $this->assign( 'user_id', $user_id );
        $this->assign( 'db_id', $db_id );
        $this->display( 'friend/friend' );
    }

    public function ajaxModifyNoticeAction(){
        $notice = $_POST['notice'];

        $common_redis = new CommonRedis();
        $common_redis->setNotice( $notice );

        $this->ajaxReturn( [ 'code' => 0, 'data' => $notice ] );
    }

}
