<?php

namespace xing\helper\exception;
use common\map\api\ResponseMap;
use Exception;

/**
 * Created by PhpStorm.
 * User: Nph
 * Date: 2017/6/27
 * Time: 8:19
 */
class ApiException extends Exception
{

    public function __construct($message = 0, $code = 0, Exception $previous = null)
    {
        if (!is_numeric($message)) throw new Exception('throw new 返回的应该是数字CODE');
        if (!isset(ResponseMap::$codes[$message])) throw new Exception('没有这个CODE');
        parent::__construct(ResponseMap::$codes[$message], $code, $previous);
    }

    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Exception';
    }
}