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

    public static $cookie = '';

    /**
     * 访问url
     * @param $url
     * @param array $post
     * @return string
     */
    public static function post($url, $post = [], $header = [])
    {
//        hr($url);
        $ch = static::init($url);
        if (!empty($post)) {
            $fields_string = is_array($post) ? http_build_query($post) : $post;
//            curl_setopt($ch, CURLOPT_POST, count($post)) ;
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string) ;
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        }

        curl_setopt ($ch, CURLOPT_URL, $url);
        if ($header) curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

//        curl_setopt ($ch, CURLOPT_COOKIEJAR, static::$cookie); // 存放Cookie信息的文件名称
        curl_setopt ($ch, CURLOPT_COOKIEFILE, static::$cookie); // 读取上面所储

//        curl_setopt ($ch, CURLOPT_ENCODING, "" ); //设置为客户端支持gzip压缩
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE); // 获取的信息以文件流的形式
        curl_setopt ($ch, CURLOPT_HEADER, 0); // 显示返回的Header区域内容

        //设置抓取跳转（http 301，302）后的页面
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        //设置最多的HTTP重定向的数量
        curl_setopt($ch, CURLOPT_MAXREDIRS, 2);

        $html = curl_exec($ch) ;
        curl_close($ch) ;

        return $html;
    }
    /**
     * @param $file_url
     * @param $save_to
     * @return bool
     */
    public static function downloadFile($url, $savePath)
    {
        $ch = static::init($url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $fileContent = curl_exec($ch);
        curl_close($ch);
        $downloadedFile = fopen($savePath, 'w');
        fwrite($downloadedFile, $fileContent);
        fclose($downloadedFile);
        return true;
    }
    
    public static function getFile($url)
    {
        $ch = static::init($url);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $fileContent = curl_exec($ch);
        curl_close($ch);
        return $fileContent;
    }

    public static function getImageType($url)
    {
        $ch = static::init($url);
// 获取头部信息
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_NOBODY, 1);

        curl_exec($ch);
        curl_close($ch);
        $head = ob_get_contents();
        ob_end_clean();

        $regex = '/Content-Type:\s([\w-\/]+)/i';
        preg_match($regex, $head, $matches);

        return $matches[1] ?? null;
    }
    
    private static function init($url)
    {

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL, $url);
        //SSL证书
        if (preg_match('/https:/i',$url)){
            curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,!empty(static::$cert) ? true : false);
            !empty(static::$cert) && curl_setopt($ch,CURLOPT_CAINFO, static::$cert);
        }
        curl_setopt ($ch, CURLOPT_TIMEOUT, static::$time); // 设置超时限制防止死循环
        
        return $ch;

    }
}