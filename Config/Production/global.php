<?php

$redis_list = require(__DIR__ . '/redis.php');
$mysql_list = require(__DIR__ . '/mysql.php');
$kafka_list = require(__DIR__ . '/kafka.php');
$version = require(__DIR__ . '/version.php');
$mongodb = require(__DIR__ . '/mongodb.php');

return [
    'product' => 'dev',
    'redis' => $redis_list,
    'mysql' => $mysql_list,
    'kafka' => $kafka_list,
    'mongodb'=>$mongodb,
    'special_redis' => [
        'common_redis' => [
            0 => [
                'host' => 'localhost',
                'port' => '6379',
                'connect' => 'connect',
                'password' => '666666',
                'timeout' => 3
            ],
            1 => [
                'host' => 'localhost',
                'port' => '6379',
                'connect' => 'connect',
                'password' => '666666',
                'timeout' => 3
            ],
            2 => [
                'host' => 'localhost',
                'port' => '6379',
                'connect' => 'connect',
                'password' => '666666',
                'timeout' => 3
            ]
        ],
        'dojo_redis' => [
            0 => [
                'host' => 'localhost',
                'port' => '6381',
                'connect' => 'connect',
                'password' => '666666',
                'timeout' => 3
            ]
        ]
    ],
    'special_mysql' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'dbname' => 'lbs3',
        'user' => 'root',
        'pass' => ''
    ],
    'statistics' => [
        'ios' =>[ 'appId' => 100120081, 'channelId' => 1901 ],
        'android' => [ 'appId' => 100110081, 'channelId' => 9001 ]
    ],
    'version_info' => $version
];