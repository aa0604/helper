<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2018/1/21
 * Time: 21:10
 */

namespace xing\helper\yii;


use xing\helper\exception\ModelYiiException;
use yii\db\ActiveRecord;
use yii\elasticsearch\ActiveQuery;
use Yii;

trait MyActiveRecordTrait
{

    public static $query;
    public static $pageSize;
    public static $pageSizeDefalut = 20;

    public static $cacheOneTime = 3600; // 缓存时间
    public static $cacheFindOne = false;


    /**
     * 读取一条数据
     * @param $where
     * @return static|ActiveRecord|null|$this
     */
    public static function findOne($where)
    {
        $key = !is_array($where) ? $where : json_encode($where);
        if (static::$cacheFindOne) {
            $data = Yii::$app->cache->get($key);
            if (!empty($data) && !($data instanceof \__PHP_Incomplete_Class)) return $data;
            $data = parent::findOne($where);
            Yii::$app->cache->set($key, $data, static::$cacheOneTime);
            return $data;
        } else {
            // 当前进程的缓存
            static $caches;
            if (!empty($caches[$key] ?? null)) return $caches[$key];
            return $caches[$key] = parent::findOne($where);

        }
    }
    /**
     * 业务读取
     * @param $where
     * @return null|static
     * @throws \Exception
     */
    public static function logicFindOne($where)
    {
        // 如果小于0，会报错
        if ($where < 0 && empty($where)) throw new \Exception('数值不可小于0');
        $data = static::findOne($where);
        if (empty($data)) throw new \Exception('没有这条数据 '. preg_replace('/(.*)\\\/U', '', get_called_class())  . json_encode($where), static::$codeEmpty . (!is_array($where) ? $where : ''));
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

        $model = static::getInstance();
        $data = static::find()->select([$returnKey])->where([$model->primaryKey()[0] => $id])->one();
        return $data ? $data->{$returnKey} : '';
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
        return isset($params[$formName]) ? $params[$formName] : $params;
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
     * @param string $select
     * @return array|\yii\db\ActiveRecord[]|$this[]
     */
    public static function getLists(array $params = array(), $select = '*')
    {
        static::$pageSize = $pageSize = intval(isset($params['per-page'])
            ? $params['per-page']
            : static::$pageSizeDefalut);

        $page = intval(isset($params['page']) ? $params['page'] : 1);
        if (isset($params['page'])) unset($params['page']);
        if (isset($params['token'])) unset($params['token']);
        if (isset($params['per-page'])) unset($params['per-page']);

        $offset = $page * $pageSize - $pageSize;
        $query = static::getCondition($params)->offset($offset)->limit($pageSize);
        ($select != '*' && !empty($select)) && $query->select($select);
        static::$query = & $query;
        return $query->all();
    }

    /**
     * @return array
     */
    public static function getPagination()
    {
        $count = static::$query->count();
        return new \yii\data\Pagination([
            'totalCount' => $count,
            'pageSize' => static::$pageSize,
//            'maxPage' => (string) intval($count / static::$pageSize + 1),
        ]);
    }


    /**
     * 获取参数
     * 注：适用于子类继承此类来获取参数
     * 注：条件，排序可在些类完成
     * @param array $params
     * @param null $select
     * @return static|mixed|\yii\db\ActiveQuery
     */
    public static function getCondition(array $params = array(), $select = '*')
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
        return $model::find()->filterWhere($where)->orderBy($order);
    }
    /**
     * @return static|$this|\yii\db\ActiveQuery
     */
    public static function getInstance()
    {
        static $cache;
        $className = get_called_class();
        return isset($cache[$className]) ? $cache[$className] : new $className;
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

    /**
     * LIKE 反向查询
     * @param \yii\db\Query $query
     * @param $filedName
     * @return mixed
     */
    public function likeReverseSearch($query, $filedName, $like)
    {

        $select = implode(',', array_keys($this->attributeLabels())). "REVERSE({$filedName})";
        $query->select($select)->andFilterWhere(['like', "REVERSE({$filedName})", "REVERSE('%{$like}'')"]);
        return $query;
    }


    /**
     * @param $page
     * @param int $pageSize
     * @return \yii\db\ActiveQuery|ActiveQuery
     */
    public static function getModel($page = 1, $pageSize = null)
    {
        empty($pageSize) && $pageSize = static::$pageSizeDefalut;
        $offset = $pageSize * $page - $pageSize;
        return static::find()->offset($offset)->limit($pageSize);
    }

    /**
     * @param int $page
     * @param null $pageSize
     * @param array $autoWhere
     * @return \yii\db\ActiveQuery|ActiveQuery
     */
    public static function getSearch($autoWhere = [], $page = 1, $pageSize = null)
    {
        $serach = static::getModel($page, $pageSize);
        $model = new static;
        $model->load($autoWhere, '');
        foreach ($model as $k => $v) $serach->andFilterWhere([$k => $v]);
        return $serach;
    }
}