<?php

namespace Moon\Lib\Framework;


use Moon\Config\Code;

class BaseController {

    /**
     * origin request data
     * @var string
     */
    protected $origin_data;

    /**
     * converted request data
     * @var mixed
     */
    protected $params;

    protected $encrypt = 0;
    protected $checkSign = 0;

    public function __construct() {
        $this->init();
    }

    public function init() {
        $config = MConfig::config();

        //redis connect
        try{
            RedisConnection::getConnection( $config['redis'] );
        }catch(\Exception $e){
            $result['code'] = $e->getCode();
            $this->response( $result );
        }

        $input = file_get_contents("php://input");
        if( $this->encrypt ) $input = $this->xor_dec( $input );

        //验签
        $pos = strrpos($input,'&');//获取最后一个&符号的位置      //input参数格式 param=xx&sign=xx
        $param = substr(substr($input,0,$pos),6);
        $sign = substr(substr($input, $pos+1),5);
        $server_sign = md5( $param.$config['secret_key'] );
        if( $this->checkSign && $server_sign != $sign ){
            $result['code'] = Code::SIGN_ERROR;
            $this->response( $result );
        }

        $this->origin_data = $param;
        $this->params = json_decode( $param, true );
        foreach ( $this->params as $key => $value ){
            if( is_array( $value ) ) continue;
            $this->params[ $key ] = is_array( json_decode( $value, true ) ) ? json_decode( $value, true ) : $value;
            if( $key == 'version' ){
                $_GET['version'] = $value;
            }
        }
    }

    protected function auth() {
        // User
        $this->_user = Service_User::getUserByUId( $this->params['uid'] );
        if (empty($this->_user))
        {
            $result['code'] = Code::NOT_FIND_ACCOUNT;
            $this->response($result);
        }
        // 验证Session
        if ($this->_user['uuid'] != $this->params['uuid'] || !$this->params['uuid'])
        {
            $result['code'] = Code::SESSION_OUT_DATE;
            $this->response($result);
        }
    }

    protected function checkVersion() {
        //检测客户端是否是最新版本并兼容老版本
        if ($this->params['version'] && !Service_Version::checkClientIsNewVersion( $this->params['pid'], $this->params['version'] ) && $this->verify_ip() && $this->verify_deviceid() )
        {
            $result['code'] = Code::MUST_UPDATE_CLIENT;
            $this->response($result);
        }
    }

    /**
     * check params exist
     * @param $params_need
     * @return bool
     */
    public function checkParamsExist( $params_need ){
        //must be a array
        if( !is_array( $params_need ) ) return false;
        //no params need return true
        if( empty( $params_need ) ) return true;

        //find params' keys
        if( empty( $this->params ) ) return false;
        $param_keys = array_keys( $this->params );

        //check difference set
        $unfind_keys_arr = array_diff( $params_need, $param_keys );
        if( !empty( $unfind_keys_arr ) ) return false;
        else return true;
    }

    /**
     * check params
     * @param $params_need
     * @return bool
     */
    public function checkParams( $params_need ){
        if( !$this->checkParamsExist( $params_need ) ) $this->jsonResponse( [
            'code' => Code::PARAMS_ERROR,
            'msg' => 'Params Error'
        ] );
    }

    /**
     * json response
     * @param $data
     */
    public function jsonResponse( $data ){
        header('content-type:application/json;charset=utf8');
        $json = json_encode( $data );
        $response = $this->xor_enc( $json );
        echo $response;
        die;
    }

    /**
     * json response
     * @param $data
     */
    public function response( $data ){
        // 如没反馈错误则返回正常
        if (!isset($data['code'])){
            $data['code'] = Code::SUCCESS;
        }

        $data['server_unixtime'] = SYS_UNIXTIME;//获取服务器时间
//        $data['cost'] = Helper::use_time($this->tag); //接口耗时
//        $data['archivesNo'] = Config_Game::$archivesNo;

        header('content-type:application/json;charset=utf8');
        $json = json_encode( $data );
        $response = $this->xor_enc( $json );
        echo $response;
        die;
    }

    /**
     * xor encode
     * @param $str
     * @return string
     */
    public function xor_enc($str)
    {
        if( $this->encrypt ){
            $len = strlen($str);
            for($i=0;$i<$len;$i++){
                $string[$i] = chr(ord($str[$i])^13);
            }
            return base64_encode( $str );
        }
        return $str;

    }

    /**
     * xor decode
     * @param $str
     * @return string
     */
    public function xor_dec($str)
    {
        if( $this->encrypt ){
            $str = base64_decode( $str );
            $len = strlen($str);
            for($i=0;$i<$len;$i++){
                $string[$i] = chr(ord($str[$i])^13);
            }
            return $str;
        }
        return $str;

    }

}