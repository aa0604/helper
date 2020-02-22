<?php

namespace xing\helper\log;

class LogHelper
{
    // 日志模式 hide 不输出 show 直接输出 
    public static $logModel = 'hide';
    
    public static $all = [];
    
    public static $msg = null;
    
    public static function add($msg)
    {
        static::$all[] = static::$msg = $msg;
        if (static::$logModel == 'show') {
            print_r($msg);
            echo PHP_EOL;
        }
    }
}