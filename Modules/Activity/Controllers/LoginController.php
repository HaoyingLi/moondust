<?php

namespace Moon\Modules\Activity\Controllers;

use Moon\Lib\Framework\BaseController;
use Moon\Lib\Framework\MConst\FrameworkCode;
use Moon\Modules\Member\Logic\Login;

class LoginController extends BaseController {

    protected $encrypt = 0;

    public function userLoginAction() {
        //检查参数
        $this->checkParams( [ 'username', 'password' ] );

        //登录
        $login_busyness = new Login( $this->params );
        $login_busyness_result = $login_busyness->logic();
        $result['login'] = $login_busyness_result;

        $result['code'] = FrameworkCode::REQUEST_SUCCESS;
        $this->jsonResponse( $result );
    }


}