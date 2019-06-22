<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2017/9/2
 * Time: 17:26
 */

namespace xing\helper\exception;



class LanguageException extends \Exception
{

    public function __construct($message = "", $code = 0)
    {
        parent::__construct($message, $code);
        $this->message = $message;
    }
}