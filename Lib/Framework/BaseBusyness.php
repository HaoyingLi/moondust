<?php

namespace Moon\Lib\Framework;


use Moon\Lib\Framework\MConst\FrameworkCode;

abstract class BaseBusyness
{

    /**
     * acceptable params' name
     * @var array
     */
    private $params_receive = [];

    /**
     * necessary params' name
     * @var array
     */
    private $params_need = [];

    protected $params = [];

    /**
     * busyness status
     * @var int
     */
    protected $status = 0;

    const STATUS_NORMAL = 0;
    const STATUS_WORKING = 1;
    const STATUS_ERROR = 2;
    const STATUS_END = 3;

    protected $response = [];

    /**
     * main busyness logic
     * @return mixed
     */
    abstract function work();

    abstract function setParamsReceive();

    abstract function setParamsNeed();

    /**
     * receive busyness params
     * @param $params
     */
    public function __construct( $params ) {
        $this->params_receive = $this->setParamsReceive();
        $this->params_need = $this->setParamsNeed();
        foreach( $this->params_receive as $param_name ) {
            if( isset( $params[ $param_name ] ) ) $this->params[ $param_name ] = $params[ $param_name ];
        }
        $this->logic();
    }

    /**
     * valid all conditions before logic
     * change busyness status if it's successful
     * @return bool
     */
    private function valid() {
        if( !is_array( $this->params ) ) return $this->error( FrameworkCode::BUSYNESS_PARAMS_ERROR, 'Busyness Params Error' );
        if( !$this->checkParamsExist() ) return $this->error( FrameworkCode::BUSYNESS_PARAMS_ERROR, 'Busyness Params Error' );
        $this->status = self::STATUS_WORKING;
        return true;
    }

    /**
     * main busyness logic call
     * @return string
     */
    public function logic(){
        if( $this->status === self::STATUS_NORMAL ){
            $this->valid();
        }
        if( $this->status === self::STATUS_WORKING ){
            $this->response = $this->work();
        }
        return $this->response;
    }

    /**
     * record error info and change busyness status
     * @param        $response_code
     * @param string $response_msg
     * @param array  $response_data
     * @return bool
     */
    public function error( $response_code, $response_msg = '', $response_data = [] ) {
        $this->status = self::STATUS_ERROR;
        $this->response = [ 'code' => $response_code, 'msg' => $response_msg, 'data' => $response_data ];
        return false;
    }

    /**
     * params exist check
     * @return bool
     */
    private function checkParamsExist(){
        if( !is_array( $this->params_need ) ) return false;
        if( empty( $this->params_need ) ) return true;

        //check difference set
        if( empty( $this->params ) ) return false;
        $param_keys = array_keys( $this->params );
        $unfind_keys_arr = array_diff( $this->params_need, $param_keys );
        if( !empty( $unfind_keys_arr ) ) return false;
        else return true;
    }

}