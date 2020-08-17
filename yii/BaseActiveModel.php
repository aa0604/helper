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

    /**
     * 自动设置场景
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {

        if (defined('MODEL_SCENARIOS')
            && isset($this->scenarios()[MODEL_SCENARIOS])
            && $this->getScenario() != static::SCENARIO_DEFAULT
        ) $this->setScenario(MODEL_SCENARIOS);

        return parent::beforeSave($insert);
    }
}