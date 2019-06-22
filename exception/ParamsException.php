<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2017/12/15
 * Time: 19:26
 */

namespace xing\helper\exception;


class ParamsException extends \Exception
{

    /**
     * ParamsException constructor.
     * @param string $message 只传参数名
     * @param int $code
     */
    public function __construct($paramsName = "", $code = 0)
    {
        parent::__construct($paramsName, $code);
        $this->message = $paramsName . ' ' . '参数错误';
    }
}