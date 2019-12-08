<?php


namespace xing\helper\number;


class ComputeHelper
{
    /**
     * 精确加法
     * @param $a
     * @param $b
     * @param string $scale
     * @return string
     */
    public static function add($a,$b,$scale = '2') {
        return bcadd($a,$b,$scale);
    }


    /**
     * 精确减法
     * @param $a
     * @param $b
     * @param string $scale
     * @return string
     */
    public static function sub($a,$b,$scale = '2') {
        return bcsub($a,$b,$scale);
    }

    /**
     * 精确乘法
     * @param $a
     * @param $b
     * @param string $scale
     * @return string
     */
    public static function mul($a,$b,$scale = '2') {
        return bcmul($a,$b,$scale);
    }

    /**
     * 精确除法
     * @param $a
     * @param $b
     * @param string $scale
     * @return string|null
     */
    public static function div($a,$b,$scale = '2') {
        return bcdiv($a,$b,$scale);
    }

    /**
     * 精确求余/取模
     * @param $a
     * @param $b
     * @return string|null
     */
    public static function mod($a,$b) {
        return bcmod($a,$b);
    }

    /**
     * 比较大小
     * @param [type] $a [description]
     * @param [type] $b [description]
     * @return int 大于 返回 1 等于返回 0 小于返回 -1
     */
    public static function comp($a,$b,$scale = '5') {
        return bccomp($a,$b,$scale); // 比较到小数点位数
    }

}
