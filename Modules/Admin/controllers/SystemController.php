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


use Moon\Model\Redis\CommonRedis;

class SystemController extends BaseAdminController {

    public function noticeModifyAction(){
        $common_redis = new CommonRedis();
        $current_notice = $common_redis->getNotice();
        $this->assign( 'current_notice', $current_notice );
        $this->display( 'system/notice-modify' );
    }

    public function ajaxModifyNoticeAction(){
        $notice = $_POST['notice'];

        $common_redis = new CommonRedis();
        $common_redis->setNotice( $notice );

        $this->ajaxReturn( [ 'code' => 0, 'data' => $notice ] );
    }

}
