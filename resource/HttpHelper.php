<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2017/11/20
 * Time: 15:03
 */

namespace xing\helper\resource;


class HttpHelper
{
    public static $cert = '';

    public static $time = 30;

    /**
     * 访问url
     * @param $url
     * @param array $post
     * @return string
     */
    public static function post($url, $post = [], $headers = [])
    {
//        $url = 'http://www.baidu.com';
        $ch = curl_init() ;
        if (!empty($post)) {
            $fields_string = is_array($post) ? http_build_query($post) : $post;
//            die($fields_string);
//            is_array($post) && curl_setopt($ch, CURLOPT_POST, count($post)) ;

            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string) ;
//            die($fields_string);
            curl_setopt($ch, CURLOPT_POST, TRUE);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }

        if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        //SSL证书
        if (preg_match('/https:/i',$url)){
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,!empty(static::$cert) ? true : false);
            !empty(static::$cert) && curl_setopt($ch,CURLOPT_CAINFO, static::$cert);
        }

        curl_setopt ($ch, CURLOPT_URL, $url);

        curl_setopt ($ch, CURLOPT_TIMEOUT, static::$time); // 设置超时限制防止死循环
//        curl_setopt ($ch, CURLOPT_ENCODING, "" ); //设置为客户端支持gzip压缩
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true); // 获取的信息以文件流的形式
        curl_setopt ($ch, CURLOPT_HEADER, false); // 显示返回的Header区域内容

        //设置抓取跳转（http 301，302）后的页面
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        //设置最多的HTTP重定向的数量
        curl_setopt($ch, CURLOPT_MAXREDIRS, 2);

        $html = curl_exec($ch) ;
        if ($no = curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);
            //$no错误码7为连接不上，28为连接上了但请求返回结果超时
            if(in_array(intval($no), [7, 28], true)) {
                throw new \Exception('连接或请求超时' . $error, $no);
            }
        }
        return $html;
    }
    /**
     * @param $file_url
     * @param $save_to
     * @return bool
     */
    public static function downloadFile($url, $savePath)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $fileContent = curl_exec($ch);
        curl_close($ch);
        $downloadedFile = fopen($savePath, 'w');
        fwrite($downloadedFile, $fileContent);
        fclose($downloadedFile);
        return true;
    }
}