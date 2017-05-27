<?php
/**
 *
 * User: 李灏颖 @lynn (lihaoying@supernano.com)
 * Date: 2017/3/30 16:11
 *
 */

namespace Moon\Logic;

use Moon\Lib\Framework\Logic\LogicInterface;

class G2 implements LogicInterface {

    public function __construct() {
        echo 'construct g2\r\n';
        echo "<br>";
    }

    public function logic() {
        echo "G2\r\n";
        echo "<br>";
    }

}