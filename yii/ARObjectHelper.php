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
    public function getPrimaryVal()
    {
        return $this->model->{$this->getPrimaryKey()};
    }

    /**
     * 获取主键名
     * @return string
     */
    public function getPrimaryKey()
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
        if (++$nnn > 1000) throw new \Exception('无限循环？');

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

    /**
     * 获取一级父id
     * @param $parentFieldName
     * @param null $parentId
     * @return int|mixed|null|string
     */
    public function getTopParentId($parentFieldName, $parentId = null)
    {
        empty($parentId) && $parentId = $this->getPrimaryVal();
        if (empty($parentId)) return $parentId;

        $nnn = 0;
        $primaryKey = $this->getPrimaryKey();
        do {
            $data = $this->model::findOne($parentId);
            if (empty($data)) break;
            $parentId = $data->{$parentFieldName} ?: $data->{$primaryKey};
        } while (!empty($data->{$parentFieldName}) && $parentId != $data->{$primaryKey} && ++$nnn < 1000);
        return $parentId;
    }

    public function updateAllChildren($parentFieldName, $childFieldName, $parentId = null)
    {
        static $nnn;
        if (++$nnn > 500) throw new \Exception('无限循环？');

        # 更新本层级
        $this->updateChildren($parentFieldName, $childFieldName, $parentId);
        # 读取一层下级
        $data = $this->model::find()->where([$parentFieldName => $parentId])->asArray()->all();

        # 开始更新下级
        foreach ($data as $k => $v) {
            self::updateAllChildren($parentFieldName, $childFieldName, $v[$this->getPrimaryKey()]);
        }

    }

    /**
     * @param $parentFieldName
     * @param $childFieldName
     * @param $parentId
     * @throws \Exception
     */
    private function updateChildren($parentFieldName, $childFieldName, $parentId)
    {
        $childrenIds = $this->getChildren($parentFieldName, $parentId);
        if (empty($childrenIds)) return ;
        $data = $this->model::findOne($parentId);
        $data->{$childFieldName} = $childrenIds;
//        hr($data->id . '='.$childrenIds);
        if (!$data->save()) throw new \Exception(implode(',', $data->getFirstErrors()));

    }

    public function readLowestId($parentFieldName, $id)
    {
        $primaryKey = $this->getPrimaryKey();
        do {
            $data = $this->model::findOne([$parentFieldName => $id]);
            if (empty($data)) break;
            $id = $data->{$primaryKey};
        } while (!empty($data) && !empty($id));

        return $id;
    }
}
