<?php

namespace Moon\Lib\Framework;


class MConfig{

    /**
     * @var self
     */
    private static $MConfig;

    private $config = [];

    private $data = [];

    private $config_path = '';

    private $data_path = ROOT_DIR.'Data/';

    /**
     * MConfig constructor.
     * @param string $env_type
     */
    public function __construct( $env_type = 'DEV' ) {
        if( $env_type == 'PRO' ) $this->config_path = ROOT_DIR.'/Config/Production/';
        elseif( $env_type == 'SIM' ) $this->config_path = ROOT_DIR.'/Config/Simulation/';
        else $this->config_path = ROOT_DIR.'/Config/Development/';
    }

    /**
     * get server config file
     * @param $file_path
     * @param $file_name
     * @return mixed
     */
    public function getFile( $file_path, $file_name ) {
        $real_path = $this->config_path.$file_path.'/'.$file_name;
        $key = md5( $real_path );
        if( !isset( $this->config[ $key ] ) ) {
            $file = require_once( $real_path );
            $this->config[ $key ] = $file;
        }
        return $this->config[ $key ];
    }

    /**
     * get busyness config file
     * @param $file_name
     * @return mixed
     */
    public function getData( $file_name ) {
        $real_path = $this->data_path.$file_name;
        $key = md5( $real_path );
        if( !isset( $this->data[ $key ] ) ) {
            $file = require_once( $real_path );
            $this->data[ $key ] = $file;
        }
        return $this->data[ $key ];
    }

    /**
     * get config dir and obj by ENV
     * @param string $type
     * @return MConfig
     */
    private static function getMConfig( $type = ENV ) {
        if( empty( self::$MConfig ) ) self::$MConfig = new self( $type );
        return self::$MConfig;
    }

    /**
     * get server config file
     * @return mixed
     */
    public static function config() {
        return self::getMConfig()->getFile( '', 'global.php' );
    }

    /**
     * get data config file
     * @param $filename
     * @return mixed
     */
    public static function data( $filename ){
        return self::getMConfig()->getData( $filename );
    }

}