<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2019/8/7
 * Time: 18:07
 */

namespace xing\helper\weChat;


use xing\helper\resource\HttpHelper;

class CustomerImService
{

    public static function valid($signature, $timestamp, $nonce, $token)
    {

        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        return $tmpStr == $signature;
    }

    public static function sendText($text, $fromUsername, $appid, $secret){
        $accessToken = static::getAccessToken($appid, $secret);
        /*
         * POST发送https请求客服接口api
         */
        $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=" . $accessToken;

        $data = [
            "touser" => $fromUsername,
            "msgtype" => "text",
            "text" => ["content" => $text]
        ];
        $json = json_encode($data,JSON_UNESCAPED_UNICODE);  //PHP版本5.4以上
        
        
        return HttpHelper::post($url, $json);
        //以'json'格式发送post的https请求
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($json)){
            curl_setopt($curl, CURLOPT_POSTFIELDS,$json);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($curl, CURLOPT_HTTPHEADER, $headers );
        $output = curl_exec($curl);
        if (curl_errno($curl)) {
            echo 'Errno'.curl_error($curl);//捕抓异常
        }
        curl_close($curl);

    }
    /* 调用微信api，获取access_token，有效期7200s*/
    public static function getAccessToken($appid, $secret){

        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appid}&secret={$secret}"; //替换成自己的小程序id和secret
        $weixin = file_get_contents($url);
        $jsondecode = json_decode($weixin);
        $array = get_object_vars($jsondecode);
        $token = $array['access_token'];
        return $token;
    }
}