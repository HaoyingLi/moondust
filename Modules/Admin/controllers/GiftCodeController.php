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


use Moon\Helper\EmailHelper;
use Moon\Helper\MemberHelper;
use Moon\Lib\ConfigData;
use Moon\Lib\FTable;
use Moon\Model\Redis\CommonRedis;

class GiftCodeController extends BaseAdminController {

    public function codeGenAction() {
        if( empty( $_POST ) ) {
            $gift_config = ConfigData::getGiftCodeConfig();
            $gift_type = array_keys( $gift_config );

            $this->assign( 'gift_type', $gift_type );
            $this->display( "gift-code/generate" );
        }
        else {
            global $_CONF;
            $num = $_POST['gift_num'];
            $type = $_POST['gift_type'];
            $begin = strtotime( $_POST['begin_time'] );
            $end = strtotime( $_POST['end_time'] );

            if( empty( $num ) || empty( $type ) || empty( $begin ) || empty( $end ) ) {
                die( 'invalid params' );
            }

            $db_config = $_CONF['special_mysql'];
            $gift_code_table = new FTable( 'gift_code', $db_config );

            $now = date( 'YmdHis' );
            $expire = $end - strtotime( $now );
            if( $expire <= 0 ) die('invalid time');

            $filename = 'gift_code/type'.$type.'_num'.$num.'_'.$now.'.txt';
            $down_file_name = 'type'.$type.'_num'.$num.'_'.$now.'.txt';

            $common_redis = new CommonRedis();
            while ( $num-- ) {
                $code = MemberHelper::generateRandomString( 12 );
                $code_info = [
                    'code'       => $code,
                    'begin_time' => $begin,
                    'end_time'   => $end,
                    'type'       => $type,
                    'status'       => 0,
                    'create_time' => $now
                ];
                $res = $gift_code_table->insert( $code_info );
                if( $res ) {
                    $common_redis->setGiftCode( $code, $code_info, $expire );
                    file_put_contents( $filename, $code."\r\n", FILE_APPEND );
                }
            }

            Header( "Content-type:  application/octet-stream ");
            Header( "Accept-Ranges:  bytes ");
            Header( "Accept-Length: " .filesize($filename));
            header( "Content-Disposition:  attachment;  filename= {$down_file_name}");
            readfile($filename);
        }
    }

    public function ajaxGetGiftContentAction() {
        $type_id = $_POST['type'];

        $gift_config = ConfigData::getGiftCodeConfig();
        $gift_config = empty( $gift_config[ $type_id ] ) ? [ ] : $gift_config[ $type_id ];

        $resource_name = EmailHelper::$resource_name;

        $gift_string = "";

        if( !empty( $gift_config['ItemsId'] ) && is_array( $gift_config['ItemsId'] ) ) {
            foreach ( $gift_config['ItemsId'] as $key => $val ) {
                $prop = ConfigData::getPropById( $val );
                $gift_string .= $prop['Name']."  :  ".$gift_config['ItemsNum'][ $key ]."\r\n";
            }
        }

        if( !empty( $gift_config['ResId'] ) && is_array( $gift_config['ResId'] ) ) {
            foreach ( $gift_config['ResId'] as $key => $val ) {
                $gift_string .= $resource_name[ $val ]."  :  ".$gift_config['ResNum'][ $key ]."\r\n";
            }
        }

        $this->ajaxReturn( [ 'code' => 0, 'data' => $gift_string ] );
    }

}
