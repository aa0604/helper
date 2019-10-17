<?php


namespace xing\helper\laravel;


use Illuminate\Support\Facades\Cache;

trait CacheTrait
{
    public static $cacheTime = 3600; // 缓存时间

    public static function boot()
    {
        static::saved(function ($self) {
            if (isset($self::$allCacheKey) && $self::$allCacheKey) foreach ($self::$allCacheKey as $key) {
                $self::delCache($key);
            }
        });

        static::deleted(function ($self) {
            $primary = $self->getKeyName();
            $self::delCache($self->{$primary});
        });
    }

    public static function getCacheKey($key)
    {
        return (new self)->getTable() . $key;
    }

    public static function setCache($key, $val, $time = null)
    {
        return Cache::put(static::getCacheKey($key), $val, $time);
    }

    public static function getCache($key)
    {
        return Cache::get(static::getCacheKey($key));
    }

    public static function delCache($key)
    {
        return Cache::forget(static::getCacheKey($key));
    }
}