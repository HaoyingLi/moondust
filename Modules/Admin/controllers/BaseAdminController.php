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

use Moon\Lib\FController;

use Moon\Model\Mysql\AdminMysql;
class BaseAdminController extends FController {

    public function __construct() {
        parent::__construct();
        $auth = $this->checkAuth();
        if($auth){
            return true;
        }else{
            return false;
        }

    }

    protected function checkAuth() {
        $admin = new AdminMysql();
        $auth_info = $admin->getSessionData();
        if (!$auth_info) {
            echo '<script type="text/javascript">top.location.href="/admin/auth/login";</script>';
            // header('location:http://a.yuanfenba.net/admin/auth/login');
        }
        return true;
    }


}
