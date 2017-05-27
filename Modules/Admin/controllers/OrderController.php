<?php
/**
 * Created by PhpStorm.
 * User: xiaojian
 * Date: 2016/12/3
 * Time: 0:32
 */

namespace Moon\Modules\Admin\controllers;


use Moon\Model\Mysql\UserPayMysql;

class OrderController extends BaseAdminController
{
    public function indexAction(){
        $order = new UserPayMysql();

        if($_POST){
            $orders=$order->getOrderByUserId($_POST['userId']);
        }else{
            $orders = [];
        }

        $this->assign("orders",$orders);

        $this->display("order/index");
    }


}