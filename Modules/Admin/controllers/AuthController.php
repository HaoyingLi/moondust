<?php
/**
 * Created by PhpStorm.
 * User: yuanzhouwen
 * Date: 2016/11/28
 * Time: 19:44
 */

namespace Moon\Modules\Admin\controllers;


use Moon\Lib\FController;
use Moon\Lib\FCookie;
use Moon\Lib\Framework\BaseController;
use Moon\Lib\FRequest;
use Moon\Lib\FTable;
use Moon\Model\Mysql\AdminMysql;

class AuthController  extends FController
{
    public function loginAction()
    {
        parent::__construct();
        if (FRequest::isPost()) {
            $username = trim($_POST['username']);
            $password = trim($_POST['password']);

            $managerTable = new AdminMysql();
            $encryptPassword = md5($password);
            $managerData = $managerTable->getManagerInfo($username);

            if (!$managerData) {
                $this->display('login');
            } else {
                if ($managerData['password'] == $encryptPassword) {
                    $auth_str = md5("{$managerData['username']}|{$managerData['password']}|{$managerData['gid']}");

                    // 更新登录时间
                    $managerTable->updateLastLoginTime($managerData['manager_id']);

                    FCookie::set('manager_auth', "{$managerData['manager_id']}\t{$auth_str}", 3600000);

                    header("location: /admin/main/index");
                    return true;
                } else {
                    echo '<script>alert("密码错误")</script>';
                    $this->display('login');
                }

            }


        }else{
            $this->display('login');
        }
    }

    public function logoutAction() {
        FCookie::set('manager_auth', null, -1);
        header("location: /admin/main/index");
    }
}