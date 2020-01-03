<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2019/12/22
 * Time: 8:13
 */

namespace xing\helper\datetime;

class DatetimeFormatHelper
{

    /**
     * @param $diffTime
     * @return false|string
     */
    public static function diy($time, $foramt = 'Y-m-d H:i')
    {
        if (!is_numeric($time)) $time = strtotime($time);
        $diffTime = time() - $time;

        if ($diffTime < 60) {
            $return = '刚刚';
        } elseif ($diffTime < 60 * 60) {
            $min = floor($diffTime / 60);
            $return = $min . '分钟前';
        } elseif ($diffTime < 86400) {
            $h = floor($diffTime / (60 * 60));
            $return = $h . '小时前 ';
        } elseif ($diffTime < 86400 * 3) {
            $d = floor($diffTime / (86400));
            if ($d == 1)
                $return = '昨天';
            else
                $return = '前天';
        } elseif ($diffTime < 86400 * 7) {
            $return = intval($diffTime / 86400) . '天前';
        } elseif ($diffTime < 86400 * 30) {
            $return = intval($diffTime / 86400 / 7) . '周前';
        } elseif ($diffTime < 86400 * 365) {
            $return = intval($diffTime / 86400 / 30) . '个月前';
        } elseif ($diffTime < 86400 * 365 * 3) {
            $return = intval($diffTime / 86400 / 365) . '年前';
        } else {
            $return = date($foramt, $time);
        }
        return $return;
    }
}