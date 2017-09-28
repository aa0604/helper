<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2017/9/28
 * Time: 9:55
 */

namespace xing\helper\yii;

/**
 * Class ARObjectHelper
 * @package xing\helper\yii
 * @property \yii\db\ActiveRecord $model
 */
class ARObjectHelper
{

    public $model;

    /**
     * 初始化模型
     * @param $model
     * @return ARObjectHelper
     */
    public static function model($model)
    {
        $class = new self;
        $class->model = $model;
        return $class;
    }

    /**
     * 获取主键值
     * @return string|integer
     */
    private function getPrimaryVal()
    {
        return $this->model->{$this->getPrimaryKey()};
    }

    /**
     * 获取主键名
     * @return string
     */
    private function getPrimaryKey()
    {
        return $this->model->primaryKey()[0];
    }

    /**
     * 向下获取所有子id
     * @param $parentFieldName
     * @param null $parentId
     * @return string
     */
    public function getChildren($parentFieldName, $parentId = null)
    {
        static $nnn;
        if (++$nnn > 10) exit('无限循环？');
        $primaryKey = $this->getPrimaryKey();
        is_null($parentId) && $parentId = $this->getPrimaryVal();

        if (empty($parentId)) return '';
        $string = '';
        $data = $this->model::find()->where([$parentFieldName => $parentId])->asArray()->all();
        $keys = array_column($data, $primaryKey);
        $string .= (!empty($string) ? ',' : '') . implode(',', $keys);
        foreach ($data as $k => $v) {
            $ids = self::getChildren($parentFieldName, $v[$primaryKey]);
            !empty($ids) && $string .= (!empty($string) ? ',' : '') . $ids;
        }
        return $string;
    }
}