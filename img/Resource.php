<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2018/8/26
 * Time: 8:41
 */

namespace xing\helper\img;


class Resource
{

    /**
     * 快速检查远程文件是否存在
     * @param $url
     * @return bool
     */
    public static function exitFile($url)
    {
        if (empty($url)) return false;
        $opts=array(
            'http'=>array(
                'method'=>'HEAD',
                'timeout'=>2
            ));
        @file_get_contents($url,false,stream_context_create($opts));
        return stripos($http_response_header[0] ?? '', '200 OK') !== false;
    }
}