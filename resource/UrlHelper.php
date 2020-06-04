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

    public static function getUrls(string $html, string $notMatch = '', string $match = '') : array
    {
        preg_match_all('/<a(.*)href=[\'"](.*)[\'"]{1}/isU', $html, $arr);
        if (empty($arr[2])) return [];
        # 检查url
        foreach ($arr[2] as $k => $url) {
            $url = preg_replace('/#(.*)/', '', $url);
            if (empty($url)
                || strpos($url, 'javascript') !== false
                || (!empty($match) && !preg_match("/$match/", $url))
                || (!empty($notMatch && preg_match("/$notMatch/", $url)))
            ) unset($arr[2][$k]);
        }
        return array_values(array_unique($arr[2]));
    }

    /**
     * 补全网址
     * @param string $sUrl
     * @param string $url
     * @return string
     */
    public static function complement(string $sUrl, string $url)
    {
        $URI_PARTS = parse_url($sUrl);
        //目录获取
        if ( $URI_PARTS["path"] )	$t = explode("/", $URI_PARTS["path"]);
        if (isset($t[count($t)-1]))  unset($t[count($t)-1]);
        if ( !empty($t) )	$URI_PARTS["path"] = implode('/', $t );

        if ( $URI_PARTS["scheme"] )	$URI_PARTS["scheme"] .= '://';
        $dir = $URI_PARTS["scheme"].$URI_PARTS["host"].$URI_PARTS["path"];
        //对根目录补全地址
        if ( substr($url,0,1) == '/')	$url =$URI_PARTS["scheme"].$URI_PARTS["host"].$url;
        //对相对目录补全地址(无限级)
        elseif  ( substr($url,0,3) == '../')
        {
            $t_arr = explode("../", $url);
            $sdir = explode('/', $dir);
            for ( $i= 1; $i < count( $t_arr ); $i++ )	unset($sdir[count($sdir)-1]);

            $sdir = implode('/', $sdir);
            $url = $sdir.preg_replace("/\.\.\//isu","/",$url);
        }
        //对 ./ 的目录补全地址
        elseif  ( substr($url,0,2) == './') $url = $dir.substr( $url, 1, strlen ($url) );
        elseif (!preg_match('/\/\//i', $url ) ) $url = $dir.'/'.$url;
        return $url;
    }
}