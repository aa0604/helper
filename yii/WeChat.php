<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2019/1/9
 * Time: 13:16
 */

namespace xing\helper\yii;


use xing\helper\weChat\WeChatService;
use Yii;

class WeChat extends WeChatService
{

    public $weChatService;
    public $debug = true;
    private $cacheTimeConfig = 7000;
    private $cacheConfigs = [];

    /**
     * @param $weChatConfig
     * @return WeChatService
     */
    public function init()
    {
        return $this->weChatService = parent::start($this->weChatConfig);
    }

    /**
     * check if current user authorized
     * @return bool
     */
    public function isAuthorized()
    {
        $hasSession = Yii::$app->session->has($this->sessionParam);
        $sessionVal = Yii::$app->session->get($this->sessionParam);
        return ($hasSession && !empty($sessionVal));
    }


    /**
     * @param $url
     * @param null $code
     * @return $this
     */
    public function getAuthorizeUrl($url)
    {
        Yii::$app->session->set($this->returnUrlParam, $url);
        $app = $this->getInstance();
        return $app->oauth->redirect()->getTargetUrl();
    }

    /**
     * check if client is wechat
     * @return bool
     */
    public function isWechat()
    {
        return strpos($_SERVER["HTTP_USER_AGENT"], "MicroMessenger") !== false;
    }

    /**
     * @param array $APIs
     * @return array|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function buildConfig(array $APIs)
    {
        $key = 'WeCaht:' . implode(',', $APIs);
        $config = Yii::$app->cache->get($key);
        if (empty($config)) {
            $this->cacheConfigs[$key] = $config = self::getInstance()->jssdk->buildConfig($APIs, $this->debug, false, false);
            Yii::$app->cache->set($key, $this->cacheConfigs, $this->cacheTimeConfig);
        }
        return $config;

    }
}