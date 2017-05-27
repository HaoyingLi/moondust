<?php

namespace Moon\Modules\Member\Controllers;

use Moon\Lib\Framework\Logic\Container;
use Moon\Lib\Framework\BaseController;
use Moon\Logic\G1;
use Moon\Logic\G2;
use Moon\Modules\Member\Logic\Group;

class TestController extends BaseController {

    protected $encrypt = 0;

    public function testAction() {
        $container = new Container();
        $container->bind( 'Group', function( $container, $moduleName ) {
            return new Group( $container->make($moduleName) );
        } );

        $container->bind('G1', function($container) {
            return new G1();
        });
        $container->bind('G2', function($container) {
            return new G2();
        });

        $group1 = $container->make('Group', ['G1']);

        $group2 = $container->make('Group', ['G2']);
    }

}