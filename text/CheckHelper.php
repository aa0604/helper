<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2018/1/5
 * Time: 11:55
 */

namespace xing\helper\text;


class CheckHelper
{

    /**
     * 检查手机
     * @param $mobile
     * @param string $country  国家（预留参数）
     * @return int
     * @throws \Exception
     */
    public static function checkMobile($mobile, $country = 'china')
    {
        $regular = [
            'china' => '/^(1\d{10})|\+86(1[0-9]{10})$/'
        ];
        $preg = $regular[$country] ?? '';
        if (empty($preg)) throw new \Exception('没有这个国家的正则表达式：'.$country);
        return preg_match($preg, $mobile);
    }

    /**
     * 检查固话
     * @param $tel
     * @param string $country 国家（预留参数）
     * @return int
     * @throws \Exception
     */
    public static function checkTel($tel, $country = 'china')
    {
        $regular = [
            'china' => '/^(0\d{2,3})?(\d{7,8})$/'
        ];
        $preg = $regular[$country] ?? '';
        if (empty($preg)) throw new \Exception('没有这个国家的正则表达式：'.$country);
        return preg_match($preg, $tel);
    }

    /**
     * 检查邮箱
     * @param $email
     * @return int
     */
    public static function checkEmail($email)
    {
        return preg_match('/^([0-9A-Za-z\-_\.]+)@[0-9a-z\.]+\.\w{2,5}$/i', $email);
    }
}