<?php
/**
 *
 * User: 李灏颖 @lynn (lihaoying@supernano.com)
 * Date: 2017/3/30 16:11
 *
 */

namespace Moon\Modules\Member\Logic;

use Moon\Config\BusynessCode;
use Moon\Config\Code;
use Moon\Lib\Framework\BaseBusyness;

class Login extends BaseBusyness {

    public function setParamsReceive() {
        return [
            'uid',
            'deviceid'
        ];
    }

    public function setParamsNeed() {
        return [
            'uid',
            'deviceid'
        ];
    }

    public function work() {
//        $uid = Service_User::getUidByDeviceId($this->params['deviceid']);
//        if(!$uid){
//            if($this->params['uid'] == -1){
//                $this->params['uid'] = Service_User::addUser($this->_input);
//            }else{
//                throw new Exception('NOT_FIND_ACCOUNT', Config_Code::NOT_FIND_ACCOUNT);
//            }
//        }else{
//            $this->params['uid'] = $uid;
//        }
//
//        Service_User::setUserUUID($this->params['uid'], Service_User::getUUID());
//        // 获取回传数据
//        $result = Service_User::getUserAllInfoByUId($this->params['uid']);
//        // 记录登录平台
//        if ($result['user']['pid'] != $this->params['pid']) Service_User::setUserPid($this->params['uid'], $this->params['pid']);
$result['code'] = 1000;
        $result['msg'] = 'asdasd';
        $result['data'] = ['asd'=>111];

        return $result;
    }

}