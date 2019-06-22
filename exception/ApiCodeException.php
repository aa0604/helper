<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2017/9/9
 * Time: 14:04
 */

namespace xing\helper\exception;

use Yii;
use common\map\api\ResponseMap;

class ApiCodeException extends \Exception
{

    /**
     * ApiCodeException constructor.
     * @param int $code
     * @param string $message
     */
    public function __construct($code = 0, $message = '')
    {
        $this->message = ResponseMap::$codes[$code] ?? '';
        $message && $this->message .= ' ' . $message;
        parent::__construct($this->message, $code);
    }
}