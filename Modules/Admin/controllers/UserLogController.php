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

use Moon\Helper\DojoHelper;
use Moon\Lib\Alipay\Alipay;
use Moon\Lib\FHttp;
use Moon\Lib\FMongoDB;
use Moon\Lib\FRequest;
use Moon\Lib\FResponse;
use Moon\Lib\Log\LogSequence;
use Moon\Model\Mysql\RedMysql;
use Moon\Model\Redis\DojoFixRedis;

class UserLogController extends BaseAdminController {

    public function userLogAction(){
        $this->display('red/red_list');
    }

    public function ajaxGoldlogAction(){
        $start_time = $_REQUEST['start_time'];
        $end_time = $_REQUEST['end_time'];
        $user_id = $_REQUEST['user_id'];
        if (!$user_id) {
            $this->ajaxReturn( [ 'code' => 0, 'data' => [] ] );
        }
        $mongoDb = FMongoDB::getInstance();
        $filter = [
            'time' => ['$gt' => $start_time, '$lte' => $end_time],
            'user_id' => $user_id
        ];
        $options = [
            'projection' => ['_id' => 0]
        ];
        $log = $mongoDb->query("user_gold_log", $filter, $options);
        $log = $log->toArray();

        echo $log;
        die;
    }

    public function ajaxExplogAction(){
        $start_time = $_REQUEST['start_time'];
        $end_time = $_REQUEST['end_time'] ? $_REQUEST['end_time'] : time();
        $user_id = $_REQUEST['user_id'];
        if (!$user_id) {
            $this->ajaxReturn( [ 'code' => 0, 'data' => [] ] );
        }
        $mongoDb = FMongoDB::getInstance();
        $filter = [
            'time' => ['$gt' => $start_time, '$lte' => $end_time],
            'user_id' => $user_id
        ];
        $options = [
            'projection' => ['_id' => 0]
        ];
        $log = $mongoDb->query("user_exp_log", $filter, $options);
        $log = $log->toArray();

        echo $log;
        die;
    }

    public function ajaxProplogAction(){
        $start_time = $_REQUEST['start_time'];
        $end_time = $_REQUEST['end_time'] ? $_REQUEST['end_time'] : time();
        $user_id = $_REQUEST['user_id'];
        if (!$user_id) {
            $this->ajaxReturn( [ 'code' => 0, 'data' => [] ] );
        }
        $mongoDb = FMongoDB::getInstance();
        $filter = [
            'time' => ['$gt' => $start_time, '$lte' => $end_time],
            'user_id' => $user_id
        ];
        $options = [
            'projection' => ['_id' => 0]
        ];
        $log = $mongoDb->query("user_prop_log", $filter, $options);
        $log = $log->toArray();

        echo $log;
        die;
    }

    public function ajaxPetlogAction(){
        $start_time = $_REQUEST['start_time'];
        $end_time = $_REQUEST['end_time'] ? $_REQUEST['end_time'] : time();
        $user_id = $_REQUEST['user_id'];
        if (!$user_id) {
            $this->ajaxReturn( [ 'code' => 0, 'data' => [] ] );
        }
        $mongoDb = FMongoDB::getInstance();
        $filter = [
            'time' => ['$gt' => $start_time, '$lte' => $end_time],
            'user_id' => $user_id
        ];
        $options = [
            'projection' => ['_id' => 0]
        ];
        $log = $mongoDb->query("user_prop_log", $filter, $options);
        $log = $log->toArray();

        echo $log;
        die;
    }

    public function testUpdateRankAction(){
        $string = '{"code":0,"request_token":"fwsydIgPFBPCaQsQKLSEwTrcPn6g4PZ2","data":{"guardian":[{"user_id":"robotMmIc9HPU42aR23hTeNlfRHJEPKVZ48","fight":1077,"rank":1,"state":0},{"user_id":"robotYvtCoo3l8FHtPGe8DkEy2dfm0ZPVAY","fight":1074,"rank":2,"state":0},{"user_id":"robotdB2l27tUSUYMwPLYUlVV0U2QTvWoUv","fight":1071,"rank":3,"state":0},{"user_id":"robotO2TmXFhoA3VNwhAYfE6XZmzBD0i75x","fight":1068,"rank":4,"state":0},{"user_id":"robotBHE2Rnm3tnAZMFdICEv8gzg506VqUR","fight":1065,"rank":5,"state":0},{"user_id":"robot4e7xdyAvZxwMnzKHwDXyisobwJCC8x","fight":1062,"rank":6,"state":0},{"user_id":"robotssfviJ4Gn0Xo7FyL0dDygnSaTgk1QY","fight":1059,"rank":7,"state":0},{"rank":8,"head_img":"","role_name":"Moyiii","role_sex":"1","user_id":"b6b5e37a14c6227510f84099c20adda5","base":{"168708899C9883C802E6A8FFC006B693":15.841222082321,"C4D4CDD5782755D5B87C2B01E8D1CEDD":4.5371075253109,"64F31CFDCE4F2C0C89106236F98C2BC5":7.0354462200521,"FC1CCC703C6DFDDB7E91D079C1092379":2.2842344702363,"F0F1DE4E34D90DDE86929CB41CC084E7":2.9880164929192},"pet_mod_id":[200012,500001,100002,300011,100011],"fight":74,"level":"6","state":1,"pet_atk":[10.142,5.5495,0.24066666666667,0.736,2.2384],"pet_def":[10.142,5.5495,0.24066666666667,0.736,2.2384],"pet_hp":[10.142,5.5495,0.24066666666667,0.736,2.2384],"pet_level":[2,2,1,1,1]},{"user_id":"robotYvw6C0gdoMu2PB5Ntj8EhIt1456UbO","fight":1053,"rank":9,"state":0},{"user_id":"robotCSbI4ZdUSFPyS1TgHIjImSAGmfcF9n","fight":1050,"rank":10,"state":0}],"challenger":{"rank":8,"head_img":"","role_name":"Moyiii","role_sex":"1","user_id":"b6b5e37a14c6227510f84099c20adda5","base":{"168708899C9883C802E6A8FFC006B693":15.841222082321,"C4D4CDD5782755D5B87C2B01E8D1CEDD":4.5371075253109,"64F31CFDCE4F2C0C89106236F98C2BC5":7.0354462200521,"FC1CCC703C6DFDDB7E91D079C1092379":2.2842344702363,"F0F1DE4E34D90DDE86929CB41CC084E7":2.9880164929192},"pet_mod_id":[200012,500001,100002,300011,100011],"fight":74,"level":"6","state":1,"pet_atk":[10.142,5.5495,0.24066666666667,0.736,2.2384],"pet_def":[10.142,5.5495,0.24066666666667,0.736,2.2384],"pet_hp":[10.142,5.5495,0.24066666666667,0.736,2.2384],"pet_level":[2,2,1,1,1]}}}';
        $this->params = json_decode(file_get_contents("php://input"), true);
        $info = json_decode($string,true);
        $challenger = $info['data']['challenger'];
        $challenger['user_id'] = $this->params['user_id'];
        $challenger['fight'] = $this->params['fight'];
        $challenger['rank'] = $this->params['rank'];

        DojoHelper::updateChallengerInfo($this->params['geohash'],$challenger);
        LogSequence::push();
        //查询原来排名
        $dojoFixRedis = new DojoFixRedis($this->params['geohash']);
        $rank_list = $dojoFixRedis->getDojoFixValue();
        print_r( $rank_list);
        die;
    }
    
}
