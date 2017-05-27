<?php

namespace Moon\Lib\Framework;

use Moon\Config\Code;

class RedisConnection {

    public static $address_hash = [];

    public static function getConnection( $redis_config ) {
        $ip = $redis_config['host'];
        $port = $redis_config['port'];
        $hash = md5( $ip.':'.$port );
        if( isset( self::$address_hash[ $hash ] ) ) return self::$address_hash[ $hash ];
        else {
            $redis = new \Redis();

            //pconnect
            if( isset( $redis_config['connect'] ) && $redis_config['connect'] == 'pconnect' ) {
                $connect_result = $redis->pconnect( $redis_config['host'], $redis_config['port'], $redis_config['timeout'] );
                if( !$connect_result ){
                    throw new \Exception("Redis Connection Error", Code::REDIS_CONNECT_ERROR);
                }
            }
            //connect
            else {
                $connect_result = $redis->connect( $redis_config['host'], $redis_config['port'], $redis_config['timeout'] );
                if( !$connect_result ){
                    throw new \Exception("Redis Connection Error", Code::REDIS_CONNECT_ERROR);
                }
            }

            //auth
            if( !empty($redis_config['password']) ) {
                $auth_result = $redis->auth( $redis_config['password'] );
                if( !$auth_result ){
                    throw new \Exception("Redis Auth Error.", Code::REDIS_AUTH_ERROR);
                }
            }

            self::$address_hash[ $hash ] = $redis;
        }

        return self::$address_hash[ $hash ];
    }

}