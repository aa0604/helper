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
     * @param string $fieldName
     * @param string $fieldValueName
     * @return array|null
     */
    public static function dropDownData($data, $optionName = 'id', $optionValue= 'name')
    {
        if (empty($data)) return null;
        $array = [];
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