<?php

namespace Moon\Modules\Member\Controllers;

use Moon\Config\Code;
use Moon\Lib\Framework\BaseController;
use Moon\Lib\Framework\MConst\FrameworkCode;
use Moon\Modules\Member\Logic\Login;

class LoginController extends BaseController {

    protected $encrypt = 0;

    public function userLoginAction() {
        //检查参数
        $this->checkParams( [ 'uid', 'deviceid' ] );

        //登录
        $login_busyness = new Login( $this->params );
        $login_busyness_result = $login_busyness->logic();var_dump($login_busyness_result);
        if( $login_busyness_result['code'] == Code::SUCCESS ) {
            $result = $login_busyness_result['data'];
            $result['code'] = FrameworkCode::REQUEST_SUCCESS;
        }
        else{
            $result['code'] = Code::LOGIN_FAIL;
            $result['errmsg'] = Code::LOGIN_FAIL;
        }

        $this->response( $result );
    }


}