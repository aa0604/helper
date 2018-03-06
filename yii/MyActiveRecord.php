<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2017/9/11
 * Time: 15:51
 */

namespace xing\helper\yii;

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

}