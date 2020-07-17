<?php


namespace xing\helper\datetime;


class DatetimeHelper
{

    public static function time2week($time)
    {
        return intval(static::time2Day($time) / 7);
    }
    public static function time2Day($time)
    {
        return intval($time / 86400);
    }
}