<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2017/7/11
 * Time: 15:19
 */

namespace xing\helper\yii;

use Codeception\Module\MongoDb;
use xing\helper\resource\ReturnHelperBase;
use Yii;
use common\map\api\ResponseMap;
use yii\db\ActiveRecord;

class ReturnHelper extends ReturnHelperBase
{
    /**
     * 转换为字符串
     * @param array|\yii\db\ActiveRecord[] $data
     * @param \MongoDB\BSON\ObjectID|array|\yii\db\ActiveRecord[] $data
     */
    public static function transformation($data)
    {
        if (empty($data) || is_string($data)) return $data;
        foreach ($data as $k => $v) {
            if (is_object($v) && method_exists($v, 'toArray')) $data[$k] = $v->toArray();
            if (is_array($data[$k])) {
                $data[$k] = self::transformation($data[$k]);
            } else {
                $data[$k] = (string) $data[$k];
            }
        }
        return $data;
    }
    /**
     * @param array|\yii\db\ActiveRecord $return
     * @param string $msg
     * @param string $status
     * @param string $code
     * @return mixed
     *
     * @var array|\yii\db\ActiveRecord  $return
     */
    public static function returnJson($return, $msg = 'ok', $status = 1, $code = 0)
    {
        if (is_object($return)) $return = $return->toArray();
        return parent::returnJson($return, $msg, $status, $code);
    }



    /**
     * @param ActiveRecord|array $list
     * @param string $msg
     * @param string $status
     * @param string $code
     * @return mixed
     */
    public static function returnList($list, $msg = 'ok', $status = 1, $code = 0)
    {
        if (is_object($list)) $list = $list->toArray();
        return parent::returnList($list, $msg, $status, $code);
    }
    /**
     * @param ActiveRecord|array $data
     * @param string $msg
     * @param string $status
     * @param string $code
     * @return mixed
     */
    public static function returnData($data, $msg = '', $status = 1, $code = 0)
    {
        if (is_object($data)) $data = $data->toArray();
        return parent::returnData($data, $msg, $status, $code);
    }

    /**
     * 中断程序并显示JSON数据
     * @param $data
     */
    public static function showJson($data)
    {
        $r = Yii::$app->response;
        Yii::$app->response->format= $r::FORMAT_JSON;
        parent::showJson($data);
    }
}