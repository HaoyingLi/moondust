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
use Moon\Lib\Log\Log;
use Moon\Lib\Log\LogSequence;
use Moon\Model\Redis\CommonRedis;
use Moon\Model\Redis\MemberRedis;
use Moon\Model\Redis\PetRedis;

class UserController extends BaseAdminController {

    public function userModifyAction(){
        $this->display( 'user/user-modify' );
    }

    public function ajaxGetUserInfoAction(){
        $short_id = $_POST['short_id'];

        global $_CONF;
        $mysql_list = $_CONF['mysql'];

        $user_arr = [];
        $hash = [];
        foreach( $mysql_list as $key => $config ) {
            $address_hash = md5( $config['host'].':'.$config['port'] );
            if( isset( $hash[ $address_hash ] ) ) continue;
            else $hash[ $address_hash ] = 1;

            $table = new FTable( 'user', $config );
            $result = $table->fields( [ 'user_id', 'level', 'exp', 'role_name', 'role_sex', 'gold' ] )->where( [ 'auto_id' => $short_id ] )->select();
            if( !empty( $result ) && is_array( $result ) ) {
                foreach( $result as $user ) {
                    $user['db'] = $key;
                    $user_arr[] = $user;
                }
            }
        }

        $this->ajaxReturn( [ 'code' => 0, 'data' => $user_arr ] );
    }

    public function ajaxGetUserResourceAction(){
        $db_id = $_POST['db_id'];
        $user_id = $_POST['user_id'];

        global $_CONF;
        $mysql_list = $_CONF['mysql'];
        $config = $mysql_list[ $db_id ];

        $user_table = new FTable( 'user', $config );
        $user_result = $user_table->fields( [ 'level', 'exp', 'gold' ] )->where( [ 'user_id' => $user_id ] )->find();

        $resource_table = new FTable( 'user_resource', $config );
        $resource_result = $resource_table->where( [ 'user_id' => $user_id ] )->find();

        //mysql查找用户宠物id
        $pet_table = new FTable( 'user_pet', $config );
        $pet_res = $pet_table->fields(['id'])->where( [ 'user_id' => $user_id ] )->select();
        $pet_id_result = [];
        foreach( $pet_res as $key => $val ) {
            $pet_id_result[] = $val['id'];
        }
        sort($pet_id_result);

        //redis查找用户宠物id
        $pet_redis = new PetRedis( $user_id );
        $redis_pet_ids = $pet_redis->getUserPetIds();
        sort($redis_pet_ids);

        $result = array_merge( $user_result, $resource_result );
        $result[ 'mysql_pet_id' ] = implode( "\r\n", $pet_id_result );
        $result[ 'redis_pet_id' ] = implode( "\r\n", $redis_pet_ids);

        $redis_attr = [
            'dust',
            'candy_water',
            'candy_fire',
            'candy_wind',
            'candy_poison',
            'candy_wood',
            'candy_electric',
            'candy_stone',
            'food',
            'wood',
            'iron',

            'level',
            'exp',
            'gold'
        ];
        $member_redis = new MemberRedis( $user_id );
        $user_info_redis = $member_redis->getUserAttributes( $redis_attr );
        foreach( $user_info_redis as $key => $val ){
            $result[ $key.'_redis' ] = $val;
        }

        $this->ajaxReturn( [ 'code' => 0, 'data' => $result ] );
    }

    public function ajaxModifyUserResourceAction(){
        $db_id = $_POST['db_id'];
        $user_id = $_POST['user_id'];

        $dust = $_POST['dust'];
        $candy_water = $_POST['candy_water'];
        $candy_fire = $_POST['candy_fire'];
        $candy_wind = $_POST['candy_wind'];
        $candy_poison = $_POST['candy_poison'];
        $candy_wood = $_POST['candy_wood'];
        $candy_electric = $_POST['candy_electric'];
        $candy_stone = $_POST['candy_stone'];
        $food = $_POST['food'];
        $wood = $_POST['wood'];
        $iron = $_POST['iron'];

        global $_CONF;
        $mysql_list = $_CONF['mysql'];
        $config = $mysql_list[ $db_id ];

        $resource_table = new FTable( 'user_resource', $config );
        $resource_result = $resource_table->where( [ 'user_id' => $user_id ] )->find();

        $update = [
            'dust' => $dust,
            'candy_water' => $candy_water,
            'candy_fire' => $candy_fire,
            'candy_wind' => $candy_wind,
            'candy_poison' => $candy_poison,
            'candy_wood' => $candy_wood,
            'candy_electric' => $candy_electric,
            'candy_stone' => $candy_stone,
            'food' => $food,
            'wood' => $wood,
            'iron' => $iron,
        ];

        //记录此次修改增加的资源数量
        $log_data = [];
        foreach( $update as $key => $val ) {
            if( !isset( $resource_result[ $key ] ) ) {
                $this->ajaxReturn( [ 'code' => 1, 'data' => '出错' ] );
                die;
            }
            $log_data[ $key ] = $val - $resource_result[ $key ];
        }

        $result = $resource_table->update( $update, [ 'user_id' => $user_id ] );

        if( !empty( $result ) ) {
            $redis = new MemberRedis( $user_id );
            $redis->updateAttributes( $update );

            //资源获取日志
            Log::resource( $user_id, $log_data, $update,'GM工具修改发放' );
            LogSequence::push();

            $this->ajaxReturn( [ 'code' => 0, 'data' => '修改成功' ] );
        }
        else{
            $this->ajaxReturn( [ 'code' => 1, 'data' => '修改失败' ] );
        }

    }

    public function ajaxModifyUserAction(){
        $db_id = $_POST['db_id'];
        $user_id = $_POST['user_id'];

        $level = $_POST['level'];
        $exp = $_POST['exp'];
        $gold = $_POST['gold'];

        global $_CONF;
        $mysql_list = $_CONF['mysql'];
        $config = $mysql_list[ $db_id ];

        $user_table = new FTable( 'user', $config );
        $user_result = $user_table->fields( [ 'level', 'exp', 'gold' ] )->where( [ 'user_id' => $user_id ] )->find();
        if( empty( $user_result ) ) {
            $this->ajaxReturn( [ 'code' => 1, 'data' => '查无此人' ] );
            die;
        }

        $update = [];
        if( $user_result['level'] != $level ) $update['level'] = $level;
        if( $user_result['exp'] != $exp ) $update['exp'] = $exp;
        if( $user_result['gold'] != $gold ) $update['gold'] = $gold;

        //等级不能为空或者为负数
        if ('' == $update['level'] || $update['level'] < 0) {
            unset($update['level']);
        }

        if( !empty( $update ) ) {
            $result = $user_table->update( $update, [ 'user_id' => $user_id ] );

            if( !empty( $result ) ) {
                $redis = new MemberRedis( $user_id );
                $redis->updateAttributes( $update );

                //修改等级和经验日志
                if( isset( $update['level'] ) || isset( $update['exp'] ) ) Log::experience( $user_id, $user_result['exp'], $exp, $user_result['level'], $level, 'GM工具修改' );
                //修改金币日志
                if( isset( $update['gold'] ) ) Log::gold( $user_id, $gold-$user_result['gold'], 'GM工具修改' );
                LogSequence::push();

                $this->ajaxReturn( [ 'code' => 0, 'data' => '修改成功' ] );
            }
            else {
                $this->ajaxReturn( [ 'code' => 1, 'data' => '修改失败' ] );
            }
        }
        else{
            $this->ajaxReturn( [ 'code' => 0, 'data' => '没有任何修改' ] );
        }

    }

    public function ajaxFlushUserPetAction(){
        $db_id = $_POST['db_id'];
        $user_id = $_POST['user_id'];

        global $_CONF;
        $mysql_list = $_CONF['mysql'];
        $config = $mysql_list[ $db_id ];

        //数据库查询该用户所有宠物
        $user_table = new FTable( 'user_pet', $config );
        $user_result = $user_table->fields( '*' )->where( [ 'user_id' => $user_id ] )->select();
        $pets = [];
        foreach( $user_result as $key => $val ) {
            $pets[ $val['id'] ] = json_encode( $val, JSON_UNESCAPED_UNICODE );
        }

        //重新生成用户token，踢下线
        $common_redis = new CommonRedis();
        $common_redis->generateUserToken( $user_id );

        //用户宠物数据导入缓存
        $redis = new PetRedis( $user_id );
        $redis->updatePets( $pets );

        $this->ajaxReturn( [ 'code' => 0, 'data' => 'over' ] );
    }

}
