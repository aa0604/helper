<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2019/1/9
 * Time: 13:06
 */

namespace xing\helper\weChat;

use EasyWeChat\Factory;

/**
 * Class WeChatService
 * @property array $weChatConfig
 * @property \EasyWeChat\OfficialAccount\Application $device
 * @package xing\helper\weChat
 */
class WeChatService
{
    public $weChatConfig;

    public $device;

    public $userOptions = []; // 用户身份类参数

    public $sessionParam = 'wechatUser'; // 微信用户信息将存储在会话在这个密钥

    public $returnUrlParam = '';

    /**
     * @param $weChatConfig
     * @return WeChatService
     */
    public static function start($weChatConfig)
    {
        $class = new self;
        $class->weChatConfig = $config;
        return $class;
    }

    /**
     * @return Factory|\EasyWeChat\OfficialAccount\Application
     */
    public function getInstance()
    {
        if (!$this->device instanceof Factory)
        {
            $this->device = Factory::officialAccount($this->weChatConfig);
        }

        return $this->device;
    }

    public function getMiniProgram()
    {
        return Factory::miniProgram($this->weChatConfig);
    }
}