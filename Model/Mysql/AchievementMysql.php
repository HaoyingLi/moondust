<?php
/**
 *
 * User: 李灏颖 @lynn (lihaoying@supernano.com)
 * Date: 2016/10/6 13:10
 *
 */
namespace Moon\Model\Mysql;


use Moon\Lib\Framework\BaseMysql;
use Moon\Lib\FTable;
use Moon\Lib\Log\MysqlSequence;

class AchievementMysql extends BaseMysql{

    public function __construct( $user_id ) {
        parent::__construct( $user_id );
    }

    /**
     * 获取用户成就
     * @return array
     */
    public function getUserAchievementAll() {
        $table = new FTable( 'user_achievement', $this->db_config );
        $data = $table->fields( [ 'achievement_id','get_time' ] )->where( ['user_id' => $this->user_id ] )->select();
        return $data;
    }

    /**
     * 插入用户成就
     * @param int $achievement_id
     * @return bool
     */
    public function insertUserAchievement( $achievement_id ) {
        return MysqlSequence::insert( $this->db_index, 'user_achievement', [ 'user_id' => $this->user_id, 'achievement_id' => $achievement_id, 'get_time' => date('Y-m-d H:i:s') ] );
    }

}