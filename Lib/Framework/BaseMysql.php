<?php

namespace Moon\Lib\Framework;

abstract class BaseMysql{

    protected $table_name;

    abstract function getTableName( $arg );

    /**
     * @var \Redis
     */
    protected $connection;

    /**
     * @param array $redis_config
     * @return \redis
     * @throws \Exception
     */
    private function getRedis( $redis_config ) {
        $redis_connect = RedisConnection::getConnection( $redis_config );
        return $redis_connect;
    }

    /**
     * LbsRedis constructor.
     * @param array $redis_config
     */
    public function __construct( $redis_config ) {
        $this->connection = $this->getRedis( $redis_config );
    }

    /**
     * call origin redis extension functions.
     * @param $name
     * @param $arguments
     * @return mixed
     */
    public function __call( $name, $arguments ) {
        return call_user_func_array( [$this->connection,$name], $arguments );
    }

}