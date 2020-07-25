<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2017/9/11
 * Time: 15:51
 */

namespace xing\helper\yii;

use xing\helper\exception\ModelYiiException;
use yii\db\ActiveRecord;
use Yii;

/**
 * Class MyActiveRecord
 * @package common\db
 */
class MyActiveRecord extends ActiveRecord
{

    use MyActiveRecordTrait;
    # 场景：用户更新
    const SCENARIO_UPDATE_USER = 'update-user';
    # 场景：用户增加数据
    const SCENARIO_INSERT_USER = 'insert-user';

    public static $codeEmpty = 60;


    public static function getTableComment()
    {
        $sql = 'SELECT
 table_comment
 FROM
 information_schema.TABLES
 WHERE
 table_name = \'' . static::tableName() . "'";
        return static::getDb()->createCommand($sql)->queryOne()['table_comment'] ?? '';
    }

    /**
     * 表写锁
     * @return int
     * @throws \yii\db\Exception
     */
    public static function loockTableWrite($tables = [])
    {
        if (!empty($tables)) {
            $sql = 'lock tables `' . static::tableName() . '` write';
            foreach ($tables as $table) $sql .= " `$table` write,";
            $sql = trim(',', $sql);
        } else $sql = 'lock tables `' . static::tableName() . '` write';
        return static::getDb()->createCommand($sql)->execute();
    }
    /**
     * 表读锁
     * @return int
     * @throws \yii\db\Exception
     */
    public static function loockTableRead($tables = [])
    {
        if (!empty($tables)) {
            $sql = 'lock tables `' . static::tableName() . '` READ';
            foreach ($tables as $table) $sql .= " `$table` READ,";
            $sql = trim(',', $sql);
        } else $sql = 'lock tables `' . static::tableName() . '` READ';
        return static::getDb()->createCommand($sql)->execute();
    }

    public static function unlockTable()
    {
        return static::getDb()->createCommand('unlock tables')->execute();
    }

}