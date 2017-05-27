<?php
/**
 * Created by PhpStorm.
 * User: xiaojian
 * Date: 2016/11/27
 * Time: 13:54
 */

namespace Moon\Modules\Admin\controllers;


use Moon\Helper\UniqueID;
use Moon\Lib\ConfigData;
use Moon\Lib\FMongoDB;
use Moon\Lib\Log\MysqlSequence;
use Moon\Model\Mysql\EggMysql;
use Moon\Model\Mysql\MemberMysql;
use Moon\Model\Mysql\MonthCardMysql;
use Moon\Model\Mysql\PetMysql;
use Moon\Model\Mysql\PropMysql;
use Moon\Model\Mysql\UserBannedMysql;
use Moon\Model\Redis\CommonRedis;
use Moon\Model\Redis\EggRedis;
use Moon\Model\Redis\MemberRedis;
use Moon\Model\Redis\PetRedis;
use Moon\Model\Redis\PropRedis;
use Moon\Modules\Bag\delegates\BagDelegates;
use Moon\Modules\Pet\delegates\Pet;

class PropController extends BaseAdminController
{
    public function sendPropAction(){
        $this->display("prop/send_prop");
    }

    public function getPetAction(){
        $pets = ConfigData::getPetDemoAll();
        $type = $_GET['type'];
         $str_pet = '';
        foreach ($pets as $k => $pet) {
            if( $type == $pet['Class'] ) {
                $str_pet .= "<option value='".$pet['Id']."'>".$pet['Name']."</option>";
            }
        }
        if( $str_pet ) {
            echo json_encode(['code'=>1,'data'=>$str_pet]);
        }else {
            echo json_encode(['code'=>0,'data'=>'']);
        }
    }

    public function getPropAction(){
        $type = $_GET['type'];
        $tools = ConfigData::getProps();;
        $str_tool = '';
        foreach ($tools as $k => $tool) {
            if( $type == $tool['Class'] ) {
                $str_tool .= "<option value='".$tool['Id']."'>".$tool['Name']."</option>";
            }
        }
        if( $str_tool ) {
            echo json_encode(['code'=>1,'data'=>$str_tool]);
        }else {
            echo json_encode(['code'=>0,'data'=>'']);
        }
    }

    public function doSendAction(){
        $postData = $_POST;
        $bag=new BagDelegates();
        $pet_mysql = new PetMysql($postData["username"]);
        $pet_redis = new PetRedis($postData["username"]);
        if(isset($postData['arr_tool'])){
            $arrEgg=[];
            foreach ($postData['arr_tool'] as $tid => $num) {
                if(substr( $tid, 0, 1 ) == 9) {
                    $arrEgg[$tid] = $num;
                    unset($postData['arr_tool'][$tid]);
                }

            }
            $common_redis = new CommonRedis();
            if($arrEgg) {

                $egg_redis = new EggRedis($postData["username"]);
                $egg_mysql = new EggMysql($postData["username"]);

                $data = [];
                $num = 0;
                $itemsConf = ConfigData::getItems();
                foreach ($arrEgg as $key => $value) {
                    for ($i = 0; $i < $value; $i++) {
                        $id = UniqueID::create_unique_id('egg');
                        $data[$id] = json_encode(array(
                            "user_id" => $postData["username"],
                            "id" => $id,//索引
                            "egg_id" => $key,//id
                            "rarity" => $itemsConf[$key]['Value1'],//稀有度
                            "get_time" => date("Y-m-d H:i"),//获取时间
                            "hatch_time" => 0,
                            "is_hatch" => 0,//是否在孵化,0为孵化,1孵化中
                            "task_status" => 0,
                            "pet" => "",
                            "test_id"=>0
                        ));
                        $num++;
                    }
                }
                foreach ($data as $key => $value) {
                    $mysqlData = json_decode($value, true);
                    $result = [
                        'user_id' => $postData["username"],
                        "egg_id" => $mysqlData['egg_id'],//id
                        "rarity" => $mysqlData["rarity"],//稀有度
                        "get_time" => date("Y-m-d H:i"),//获取时间
                        "is_hatch" => 0,//是否在孵化,0未孵化
                        "task_status" => 0,
                        "id" => $mysqlData['id'],
                        "test_id"=>0
                    ];
                    $egg_mysql->addEgg($result);
                }
                $egg_redis->insertEggRedis($data);
                $common_redis->incUsedNum($postData["username"], $num);
            }

            $prop_redis = new PropRedis($postData["username"]);
            $prop_mysql = new PropMysql($postData["username"]);

            $arr_propId = array_keys($postData['arr_tool']);

            //获取要增加道具的原有数量
            $oldNums = $prop_redis->getPropNums($arr_propId);

            foreach ($postData['arr_tool'] as $k => $v) {
                $data[$k] = $oldNums[$k] + $v;
                if($data[$k]>999) return;
            }
            //增加道具
            foreach ($oldNums as $key => $value) {
                $result = [];
                $result['nums'] = $data[$key];
                if ($value === null || $value === false) {
                    $result['user_id'] = $postData["username"];
                    $result['prop_id'] = $key;
                    $prop_mysql->addProps($result);

                } else {
                    $prop_mysql->updateProps(["user_id" => $postData["username"], "prop_id" => $key],$result);
                }

            }
            $prop_redis->addRedisProps($data);

            //增加道具使用数
//            $common_redis->incBagUsedNum($postData["username"], array_sum($postData['arr_tool']));
        }
        if(isset($postData['arr_pet'])){
            foreach($postData['arr_pet'] as $k=>$v){
                for($i=0;$i<$v;$i++){
                    $petInfo = Pet::generatePet($postData["username"], $k);
                    $pet_mysql->addPet($petInfo);
                    $pet_redis->addUserPet($petInfo['id'], $petInfo);
                }
            }

        }
    }

    public function sendCardAction(){
        if($_POST['userId']){
            $redis = new CommonRedis();
            $redis->addCardRedis($_POST['userId']);
            $mysql=new MonthCardMysql($_POST['userId']);
            $mysql->addCardMysql();

        }
        $this->display("prop/send-card");
    }

    public function plusUserAction(){

        $_mongodb = FMongoDB::getInstance();
        $filter = [];
        $options = [
            'projection' => ['_id' => 0]
        ];
        $users = $_mongodb->query("user_plus", $filter, $options);
        $users = $users->toArray();
//        print_r($users);
        $data=[];
        foreach($users as $user){
            $user=json_encode($user);
            $user = json_decode($user,true);
            $user['count'] = 1;
            if(isset($data[$user['user_id']])){
                $data[$user['user_id']]['count'] +=1;
            }else{
                $data[$user['user_id']] = $user;
            }
        }
        $this->assign("users",$data);
        $this->display("prop/plus-user");
    }

    public function fenghaoAction(){
        $hour = $_GET['hour'];
        $user_id = $_GET['user_id'];
        $count = $_GET['count'];
        $data = [
            'user_id'=>$user_id,
            'time_long'=>$hour,
            'create_time'=>time(),
            'comment'=>'管理员封号',
            'count'=>$count
        ];
        $mysql = new UserBannedMysql();
        $mysql->delBanned($user_id);
        $mysql->add($data);
        $redis = new CommonRedis();
        $redis->setBanned($user_id,$hour*3600,$data);
        $redis->generateUserToken( $user_id );
        $_mongodb = FMongoDB::getInstance();
        $_mongodb->delete("user_plus",["user_id"=>$user_id]);
        header("Location:/admin/prop/plus-user");
    }

    public function getPropsAction(){
        $id = $_GET['user_id'];
        $propMysql = new PropMysql($id);
        $props=$propMysql->getUserProps();
        foreach($props as $val){
            $data[$val['prop_id']]=$val['nums'];
        }
        $redis = new PropRedis($id);
        $redis->addRedisProps($data);
        header("Location:/admin/prop/send-card");
    }

    public function bannedUserAction(){
        $mysql = new UserBannedMysql();
        $users=$mysql->getBanneds();
        foreach($users as $k=>$user){
            if((time()-$user['create_time'])/3600>=$user['time_long']) unset($users[$k]);else{
                $redis = new MemberRedis($user['user_id']);
                $info = $redis->getUserAttributes(['role_name','level']);
                $users[$k]['time'] = $user['time_long']-number_format((time()-$user['create_time'])/3600,2);
                $users[$k]['role_name'] = $info['role_name'];
                $users[$k]['level'] = $info['level'];

            }
        }
        $this->assign("users",$users);
        $this->display("prop/banned-user");
    }

    public function jiefengAction(){
        $redis = new CommonRedis();
        $redis->delBanned($_GET['id']);
        $mysql = new UserBannedMysql();
        $mysql->modBanned($_GET['id'],['time_long'=>0]);
        header("Location:/admin/prop/banned-user");
    }
}