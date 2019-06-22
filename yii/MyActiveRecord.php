<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2017/9/11
 * Time: 15:51
 */

namespace xing\helper\yii;

use xing\helper\exception\ModelYiiException;
use yii\db\ActiveRecord;

/**
 * Class MyActiveRecord
 * @package common\db
 */
class MyActiveRecord extends ActiveRecord
{

    use MyActiveRecordTrait;
    # 场景：用户更新
    const SCENARIO_UPDATE_USER = 'update-user';
    # 场景：用户增加数据
    const SCENARIO_INSERT_USER = 'insert-user';

    public static $codeEmpty = 60;

    /**
     * 业务读取
     * @param $where
     * @return null|static
     * @throws \Exception
     */
    public static function logicFindOne($where)
    {
        // 如果小于0，会报错
        if ($where < 0) throw new \Exception('数值不可小于0');
        $data = parent::findOne($where);
        if (empty($data)) throw new \Exception('没有这条数据 '. preg_replace('/(.*)\\\/U', '', get_called_class()), static::$codeEmpty . (!is_array($where) ? $where : ''));
        return $data;
    }

    /**
     * 用于在逻辑业务时的保存方法（不同的地方在于，此方法会抛出错误）
     * @return $this
     * @throws ModelYiiException
     */
    public function logicSave()
    {
        if (!$this->save()) throw new ModelYiiException($this);
        return $this;
    }
}