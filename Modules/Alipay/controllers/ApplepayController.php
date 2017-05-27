<?php
/**
 * Created by PhpStorm.
 * User: xiaojian
 * Date: 2016/11/22
 * Time: 14:06
 */

namespace Moon\Modules\Alipay\controllers;


use Moon\Lib\Framework\BaseController;
use Moon\Modules\Alipay\delegates\ApplepayDelegates;

class ApplepayController extends BaseController
{
    public function __construct()
    {
        $this->origin_data = file_get_contents("php://input");
        $this->convertRequestParams();
    }

    public function payAction()
    {
        $this->paramsCheck( ['goods_id', 'user_id'] );

        $applepay = new ApplepayDelegates();
        $res = $applepay->pay( $this->params );

        $this->jsonResponse(['code' => 0, 'data' => ['out_trade_no' => $res]]);

    }

    public function payReportAction()
    {
        $this->paramsCheck( ['report','out_trade_no'] );

        $applepay = new ApplepayDelegates();
        $res = $applepay->payReport( $this->params );

        if ($res['error'] != 0) $this->jsonResponse(['code' => $res['error'], 'data' => ['out_trade_no'=>$res['out_trade_no']]]);

        $this->jsonResponse(['code' => 0, 'data' => ['gold'=>$res['gold'],'out_trade_no'=>$res['out_trade_no']]]);

    }
}