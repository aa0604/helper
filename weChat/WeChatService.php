<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2019/1/9
 * Time: 13:06
 */

namespace xing\helper\weChat;

use EasyWeChat\Factory;
use xing\helper\resource\HttpHelper;

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

    public $result;

    /**
     * @param $weChatConfig
     * @return WeChatService
     */
    public static function start($weChatConfig)
    {
        $class = new self;
        $class->weChatConfig = $weChatConfig;
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

    /**
     * @return \EasyWeChat\MiniProgram\Application
     */
    public function getMiniProgram()
    {
        return Factory::miniProgram($this->weChatConfig);
    }



//上传素材到微信

    public function uploadImg($path)
    {

        $access_token = $this->getToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?access_token='.$access_token.'&type=image';


        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SAFE_UPLOAD, true);
        $data = array('file' => new \CURLFile(realpath($path)));
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1 );
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        $error = curl_error($curl);

        unlink($file);

        return $result;

    }
    /**
     * 获取小程序的openId
     * @param $code
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \Exception
     */
    public function getMiniProgramOpenId($code)
    {
        // 获取openId
        $weChat = $this->getMiniProgram();
        $res = $this->result = $weChat->auth->session($code);
        if (isset($res['errmsg'])) throw new \Exception('code不合法或忆失效：' . $res['errmsg']);
        return $res['openid'];
    }

    /**
     * 获取token
     * @param bool $isRefresh
     * @return array
     * @throws \EasyWeChat\Kernel\Exceptions\HttpException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \EasyWeChat\Kernel\Exceptions\RuntimeException
     * @throws \Psr\SimpleCache\InvalidArgumentException
     */
    public function getToken($isRefresh = false)
    {
        return $this->getInstance()->access_token->getToken($isRefresh)['access_token'];
    }

    /**
     * 获取seesionKey
     * @param $code
     * @return mixed
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     */
    public function getSessionKey($code)
    {
        $weChat = $this->getMiniProgram();
        $res = $weChat->auth->session($code);
        return $res['session_key'] ?? null;
    }

    /**
     * 解密
     * @param $encryptedData
     * @param $iv
     * @param $sessionKey
     * @return mixed
     * @throws \Exception
     */
    public function decryptData($encryptedData, $iv, $sessionKey)
    {
        if (strlen($sessionKey) != 24) throw new \Exception('非法', -41001);
        $aesKey = base64_decode($sessionKey);

        if (strlen($iv) != 24) throw new \Exception('非法', -410020);
        $aesIV = base64_decode($iv);

        $aesCipher = base64_decode($encryptedData);

        $result = openssl_decrypt($aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);

        $dataObj = json_decode( $result );
        if( $dataObj  == NULL ) throw new \Exception('aes 解密失败', -41003);
        if( $dataObj->watermark->appid != $this->weChatConfig['app_id'])
            throw new \Exception('aes 解密失败', -41003);
        return $dataObj;
    }
}