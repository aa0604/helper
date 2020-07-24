<?php

namespace xing\helper\arr;

class ArrayHelper
{

    public static function array2string($array)
    {
        $string = '[' . PHP_EOL;
        foreach ($array as $k => $v) {
            $k = is_numeric($k) ? $k : "'$k'";
            $string .= "    $k => " .
                (is_array($v) 
                    ? static::array2string($v) 
                    : (is_numeric($v) ? $v : "'$v'")) 
                . ' ,' .PHP_EOL;
        }
        $string .= ']' . PHP_EOL;
        return $string;
    }
}