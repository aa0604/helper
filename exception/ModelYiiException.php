<?php
/**
 * 模型抛出异常类
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2017/9/9
 * Time: 14:37
 */

namespace xing\helper\exception;


class ModelYiiException extends \Exception
{

    /**
     * ModelException constructor.
     * @param \yii\db\ActiveRecord|\yii\mongodb\ActiveRecord|\yii\elasticsearch\ActiveRecord $model
     * @param int $code
     */
    public function __construct($model, $code = 0)
    {
        $message = implode(',', $model->getFirstErrors());
//        er(iconv("utf-8","gb2312//IGNORE",$message));
        parent::__construct($message, $code);
        $this->message = $message;
    }
}