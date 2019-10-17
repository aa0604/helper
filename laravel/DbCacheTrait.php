<?php


namespace xing\helper\laravel;


use Illuminate\Support\Facades\Cache;

trait DbCacheTrait
{

    public static $cacheOneTime = 3600; // 缓存时间
    public static $cacheFindOne = false;


    /**
     * 获取缓存key
     * @param $val
     * @return string
     * @throws \Exception
     */
    private static function getCacheKey($val)
    {
        $table = static::getTable();
        is_array($val) && $val = implode(',', $val);
        return 'AR:'.$table . ':PK:' . $val;
    }

    public function find()
    {
        if (static::$cacheFindOne && !is_array($where)) {
            $key = static::getCacheKey($where);
            $data = Yii::$app->cache->get($key);
            if (!empty($data)) return $data;
            $data = parent::find($where);
            Cache::put($key, $data, static::$cacheOneTime);
            return $data;
        }
        return parent::findOne($where);
    }
}