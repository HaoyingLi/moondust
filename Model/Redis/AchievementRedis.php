<?php
/**
 *
 * User: 李灏颖 @lynn (lihaoying@supernano.com)
 * Date: 2016/10/6 13:10
 *
 */
namespace Moon\Model\Redis;


use Moon\Lib\Framework\MRedis;

class AchievementRedis extends MRedis{

    public function __construct( $user_id ) {
        parent::__construct( 'user', $user_id );
        $this->getTableName( $user_id );
    }

    public function getTableName( $user_id ){
        $this->table_name = 'user_achievement_'.$user_id;
    }

    /**
     * 获取用户已达成成就id      @lynn
     * @return array
     */
    public function getUserAchievementAll() {
        $result = $this->connection->hKeys( $this->table_name );
        return $result;
    }

    /**
     * 成就id获取用户已达成成就详情      @lynn
     * @param int $achievement_id
     * @return array
     */
    public function getUserAchievement( $achievement_id ) {
        $result = $this->connection->hGet( $this->table_name, $achievement_id );
        return $result;
    }

    /**
     * 设置用户达成成就数据       @lynn
     * @param array $param
     * @return mixed
     */
    public function updateUserAchievementAll( $param ) {
        $result = $this->connection->hMset( $this->table_name, $param );
        return $result;
    }

    /**
     * 增加用户达成成就记录   @lynn
     * @param int $achievement_id
     * @param string $date
     * @return mixed
     */
    public function updateUserAchievement( $achievement_id, $date = '' ) {
        if( $date === '' ) $date = date('Y-m-d H:i:s');
        $result = $this->connection->hSet( $this->table_name, $achievement_id, $date );
        return $result;
    }
}