<?php

$redis_conf = require( __DIR__ . '/Service/redis.php');
$white_list = require( __DIR__ . '/Config/white_list.php');

return [
    'encrypt' => 0,
    'redis' => $redis_conf[0],
    'white_list' => $white_list,
    'secret_key' => 'MPxa/KFffvH)Hi?BNfD6d69Rsh+hd6HA',
];