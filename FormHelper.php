<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2017/9/27
 * Time: 14:21
 */

namespace xing\helper;

class FormHelper
{

    /**
     * 从数据中返回下拉框需要的数组
     * @param $data
     * @param string $optionName
     * @param string $optionValue
     * @param array $array
     * @return array
     */
    public static function dropDownData($data, $optionName = 'id', $optionValue= 'name', $array = [])
    {
        if (empty($data)) return [];
        foreach ($data as $k => $v) {
            if (is_object($v)) {
                $array[$v->{$optionName}] = $v->{$optionValue};
            } else {
                $array[$v[$optionName]] = $v[$optionValue];
            }
        }
        return $array;
    }
}
