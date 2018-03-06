<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2018/1/21
 * Time: 21:10
 */

namespace xing\helper\yii;


trait MyActiveRecordTrait
{

    public static $query;
    public static $pageSize;


    /**
     * 字段和值作为条件
     * @param $fieldName
     * @param $val
     * @param null $returnKey
     * @return array|mixed|null|ActiveRecord
     */
    public static function readFieldOne($fieldName, $val, $returnKey = null)
    {
        $data = self::find()->where([$fieldName => $val])->one();
        if (empty($data)) return $data;
        return is_null($returnKey) ? $data : $data->{$returnKey};
    }

    /**
     * 返回字段值
     * @param $returnKey
     * @param $id
     * @return mixed|string
     */
    public static function readFieldValue($returnKey, $id)
    {
        if (empty($id)) return '';

        $data = static::findOne($id);
        return $data->{$returnKey} ?? '';
    }



    /**
     * @param array $search
     * @return mixed
     */
    public static function mySearch(array $search = array())
    {
        $params = static::getFormParams($search);
        return static::getInstance()->search($params)->getModels();
    }

    /**
     * 获取搜索参数
     * @param $params
     * @return mixed
     */
    public static function getParams($params)
    {
        $formName = static::getInstance()->formName();
        return $params[$formName] ?? $params;
    }

    /**
     * 返回表单搜索参数
     * @param $params
     * @return array
     */
    public static function getFormParams($params)
    {
        $formName = static::getInstance()->formName();
        return isset($params[$formName]) ? $params : [$formName => $params];
    }

    /**
     * 获取内容
     * @param array $params
     * @param bool $scenarios
     * @return static|ActiveRecord[]
     */
    public static function getLists(array $params = array(), $scenarios = null)
    {
        static::$pageSize = $pageSize = $params['pre-page'] ?? 15;
        $page = $params['page'] ?? 1;
        if (isset($params['page'])) unset($params['page']);
        if (isset($params['token'])) unset($params['token']);
        if (isset($params['per-page'])) unset($params['per-page']);

        $query = static::getCondition($params, $scenarios)->offset($page * $pageSize - $pageSize)->limit($pageSize);
        static::$query = & $query;
        return $query->all();
    }

    /**
     * @return array
     */
    public static function getPagination()
    {
        $count = static::$query->count();
        return [
            'totalCount' => (string) $count,
            'pageSize' => (string) static::$pageSize,
            'maxPage' => (string) intval($count / static::$pageSize + 1),
        ];
    }


    /**
     * 获取参数
     * 注：适用于子类继承此类来获取参数
     * 注：条件，排序可在些类完成
     * @param array $params
     * @param null $scenarios
     * @return static|mixed|\yii\db\ActiveQuery
     */
    protected static function getCondition(array $params = array(), $scenarios = null)
    {
        $model = static::getInstance();
        $model->load($params, isset($params[$model->formName()]) ? $model->formName() : '');
        $where = [];
        foreach ($model as $k => $v) isset($params[$k]) && $where[$k] = $params[$k];
        $order = [];
        if (isset($params['sort']) && !empty($params['sort'])) {
            $order = $params['sort'];
            unset($where['sort']);
        } elseif (isset($model->primaryKey()[0]) && !empty($model->primaryKey()[0])) {
            $order = [$model->primaryKey()[0] => SORT_DESC];
        }
        return $model::find()->where($where)->orderBy($order);
    }
    /**
     * @return static|mixed|\yii\db\ActiveQuery
     */
    public static function getInstance()
    {
        static $cache;
        $className = get_called_class();
        return $cache[$className] ?? new $className;
    }


    /**
     * 返回下拉框所需数据
     * @param string $fieldName
     * @param array $where
     * @return array
     */
    public static function dropDownList($fieldName = 'name', $where = [], $array = ['' => '请选择'])
    {
        $data = static::find()->where($where)->all();
        if (empty($data)) return [];
        return \xing\helper\FormHelper::dropDownData($data, static::primaryKey()[0], $fieldName, $array);
    }

    /**
     * 返回 toArray 的结果集
     * @param \yii\db\ActiveRecord[] $data
     * @return array
     */
    public static function goArrays($data)
    {
        if (empty($data)) return $data;

        foreach ($data as $k => $v) $data[$k] = $v->toArray();
        return $data;
    }

}