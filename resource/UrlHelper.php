<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2019/1/11
 * Time: 18:45
 */

namespace xing\helper\resource;


class UrlHelper
{

    public static function addParams($url, $params = null)
    {
        if (empty($url) || empty($params)) return $url;

        $string = http_build_query($params);
        return $url . (preg_match('/\?/', $url) ? '&' : '?') . $string;
    }
}