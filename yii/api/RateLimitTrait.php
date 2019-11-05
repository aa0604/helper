<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2019/10/3
 * Time: 5:21
 */

namespace xing\helper\yii\api;

use Yii;

trait RateLimitTrait
{

    public function getKey($ip)
    {
        return 'RL:' . $ip;
    }
    
    
    public function checkRateLimit($ip)
    {
        $redis = Yii::$app->cache;
        $key = $this->getKey($ip);
        
        $data = $redis->hgetall($key);
        print_r($data);
    }
}