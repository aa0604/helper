<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2017/11/26
 * Time: 11:12
 */

namespace xing\helper\yii;


class BaseActiveModel extends \xing\helper\yii\MyActiveRecord
{
    use \xing\helper\yii\MyCacheTrait;

    public function generateSearchParams($params)
    {
        $formName = $this->formName();
        return [$formName => $params];
    }
}