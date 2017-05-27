<?php
namespace Moon\Modules\Admin\controllers;


/**
 *
 * 作者: 袁周文(yuanzhouwen@supernano.com)
 * 时间: 2016-11-23 01:12:12
 *
 * vim: set expandtab sw=4 ts=4 sts=4
 * $Id: Controller.php 86 2012-07-30 09:30:42Z yuanzhouwen $
 */
class MainController extends BaseAdminController {

    public function topAction() {
        $topArray     = array(
            array('name' => '红包', 'url' => '/admin/main/left?menu=red/list'),
            array('name' => '礼包码', 'url' => '/admin/main/left?menu=giftcode'),
            array('name' => '邮件','url'=>'/admin/main/left?menu=email'),
            array('name' => '用户', 'url' => '/admin/main/left?menu=user'),
            array('name' => '系统', 'url' => '/admin/main/left?menu=system'),
            array('name' => '好友', 'url' => '/admin/main/left?menu=friend'),
        );
        $this->assign('topItems',$topArray);
        $this->display('top');
    }

    public function leftAction() {
        $menuArray = array(
            'red'   => array(
                array('name' => '活动列表', 'url' => '/admin/red-activity/activity-list'),
            ),
            'giftcode'   => array(
                array('name' => '礼包码生成', 'url' => '/admin/gift-code/code-gen'),
            ),
            'user'   => array(
                array('name' => '资源修改', 'url' => '/admin/user/user-modify'),
                array('name' => '发送道具', 'url' => '/admin/prop/send-prop'),
                array('name' => '发送月卡', 'url' => '/admin/prop/send-card'),
                array('name' => '订单查询', 'url' => '/admin/order/index'),
                array('name' => '异常用户', 'url' => '/admin/prop/plus-user'),
                array('name' => '封禁用户', 'url' => '/admin/prop/banned-user'),
            ),
            'email'     => array(
                array('name' =>'邮件发送','url' =>'/admin/email/email-send')
            ),
            'system'   => array(
                array('name' => '系统公告', 'url' => '/admin/system/notice-modify'),
            ),
            'friend'   => array(
                array('name' => '好友', 'url' => '/admin/friend/friendlist'),
            ),
        );
        $menuInit = $_GET;
        if($menuInit){
            $menuInit = explode('/', $menuInit['menu']);
            if (!$menuInit[0]) {
                $menuInit[0] = 'system';
            }
        }else{
            $menuInit[0] = 'system';
        }

        $menuItems = $menuArray[$menuInit[0]];
        $this->assign('menuItems', $menuItems);

        $this->display('left');
    }

    public function indexAction() {
        $this->display('index');
    }

    public function mainAction() {
        $this->display('main');
    }

    public function borderAction() {
        $this->display('border');
    }
}
