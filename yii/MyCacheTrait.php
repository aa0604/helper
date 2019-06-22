<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2018/1/21
 * Time: 23:14
 */

namespace xing\helper\yii;

use Yii;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

trait MyCacheTrait
{
    public static $cacheOneTime = 3600; // 缓存时间
    public static $cacheFindOne = false;

    /**
     * 获取key
     * @param $val
     * @return string
     * @throws \Exception
     */
    private static function getKey($val)
    {
        if (method_exists(static::className(), 'tableName')) {
            $table = static::tableName();
        } else if (method_exists(static::className(), 'collectionName')) {
            $table = implode('.', static::collectionName());
        } else {
            $table = static::className();
        }
        return 'AR:'.$table . ':PK:' . $val;
    }

    /**
     * 新增，保存
     * @param bool $runValidation
     * @param null $attributeNames
     * @return bool
     * @throws \Exception
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        if ($this::$cacheFindOne) $this->delCache();
        return parent::save($runValidation, $attributeNames);
    }

    /**
     * 删除数据
     * @return int|false
     * @throws \Exception
     */
    public function delete()
    {
        $this->delCache();
        return parent::delete();
    }

    /**
     * 删除缓存
     * @throws \Exception
     */
    public function delCache()
    {
        $key = static::getKey($this->{$this->primaryKey()[0]});
        if(Yii::$app->cache->exists($key)) Yii::$app->cache->delete($key);
    }

    /**
     * 根据主键id值删除缓存
     * @param $primaryId
     * @throws \Exception
     */
    public static function delCachePrimary($primaryId)
    {
        Yii::$app->cache->delete(static::getKey($primaryId));
    }

    /**
     * 读取一条数据
     * @param $where
     * @return static|ActiveRecord|null|$this[]
     */
    public static function findOne($where)
    {
        if (static::$cacheFindOne && !is_array($where)) {
            $key = static::getKey($where);
            $data = Yii::$app->cache->get($key);
            if (!empty($data)) return $data;
            $data = parent::findOne($where);
            Yii::$app->cache->set($key, $data, static::$cacheOneTime);
            return $data;
        }
        return parent::findOne($where);
    }
}