<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2019/10/3
 * Time: 5:14
 */

namespace xing\helper\yii\api;

/**
 * 
 * Trait UserRateLimitTrait
 * @property array $rateLimit 速率设置
 * @property int $allowance
 * @property int $allowance_updated_at
 * @package xing\helper\yii\api
 */
trait UserRateLimitTrait
{
    
    protected $rateLimit = [60, 60];
    
    public function getRateLimit($request, $action)
    {
        return $this->rateLimit; // $rateLimit requests per second(1秒一次请求)
    }

    public function loadAllowance($request, $action)
    {
        return [$this->allowance, $this->allowance_updated_at];
    }

    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $this->allowance = $allowance;
        $this->allowance_updated_at = $timestamp;
        $this->save();
    }
}