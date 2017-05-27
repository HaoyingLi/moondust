<?php
/**
 *
 * User: 李灏颖 @lynn (lihaoying@supernano.com)
 * Date: 2017/3/30 16:11
 *
 */

namespace Moon\Modules\Member\Logic;


use Moon\Lib\Framework\Logic\LogicInterface;

class Group {

    public $module;

    public function __construct( LogicInterface $logic, LogicInterface $logic2 = null ) {
        echo 'construct Group\r\n';
        echo "<br>";
        $this->module = $logic;
    }

    public function logic(){
        return $this->module->logic();
    }

}