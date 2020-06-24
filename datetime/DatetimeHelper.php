<?php


namespace xing\helper\datetime;


class DatetimeHelper
{

    public static function time2week($time)
    {
        return intval($time / 86400 / 7);
    }
}