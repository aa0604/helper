<?php


namespace xing\helper\yii;


use yii\elasticsearch\ActiveRecord;
use yii\helpers\Json;
use Yii;

class ElasticActiveBase extends ActiveRecord
{
    use MyActiveRecordTrait;

    public static function mapConfig()
    {
        return ['properties' => []];
    }

    /**
     * 追加值到数组
     * @param ActiveRecord $model
     * @param string $key
     * @param $val
     * @return int
     */
    public static function addArrayVal($model, $key, $val)
    {
        $result = static::scriptUpdate($model->getOldPrimaryKey(false), [
            'lang' => 'painless',
            'inline' => "if (!(ctx._source.{$key} instanceof List)) {ctx._source.{$key} = [params.newparam]} else {ctx._source.{$key}.add(params.newparam)}",
            'params' => [
                'newparam' => $val
            ]
        ]);
        return $result === false ? 0 : 1;
    }

    /**
     * 使用脚本的方式更新
     * @param $id
     * @param $body
     * @param array $options
     * @return bool|mixed|string
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\elasticsearch\Exception
     */
    public static function scriptUpdate($id, $body, $options = [])
    {

        return static::getDb()
            ->post([static::index(), static::type(), $id, '_update'], $options, Json::encode(['script' =>$body]));
    }

    public static function mapping()
    {
        return [
            static::type() => self::mapConfig(),
        ];
    }


    public static function getMapping()
    {
        $db = self::getDb();
        $command = $db->createCommand();
        return $command->getMapping(static::index());
    }

    public function formatLocation($location)
    {
        // 地理位置
        !is_array($location) && $location = $location ? json_decode($location, 1) : null;
        if (is_array($location)) {
            if (empty($location['lat'] ?? '') || empty($location['lon'] ?? '')) {
                $location = null;
            } else {
                $location['lat'] = (float) $location['lat'];
                $location['lon'] = (float) $location['lon'];
            }
        }
        return $location;
    }


    public static function getAutoDistance($lat, $lon, $userId, $sex = null, $page = 0)
    {
        $cacheKey = 'CUE:GAD:'.$userId;
        if (empty($distance = Yii::$app->cache->get($cacheKey))) {
            $distance = 50;
            while (!static::existsDistance($lat, $lon, $distance, $sex)) {
                $distance *= ++ $page;
                if ($distance > 500) return $distance;
            }
        }
        Yii::$app->cache->set($cacheKey, $distance, 1800);
        return  $distance;
    }

    /**
     * @param $lat
     * @param $lon
     * @param string $distance
     * @param int $pageSize
     * @param int $page
     * @return \yii\db\ActiveQuery|\yii\db\QueryInterface|\yii\elasticsearch\ActiveQuery
     */
    public static function getDistanceModel($lat, $lon, $distance = 50, $page = 1, $pageSize = 20)
    {
        $lat = floatval($lat);
        $lon = floatval($lon);
        $where = ['geo_distance' => ['distance' => $distance . 'km', 'location' => ['lat' => $lat, 'lon' => $lon]]];
        return static::getModel($page, $pageSize)->postFilter($where);
    }

    /**
     * @param $lat
     * @param $lon
     * @param $distance
     * @return bool
     */
    public static function existsDistance($lat, $lon, $distance, $sex = null, $min = 40)
    {
        $lat = floatval($lat);
        $lon = floatval($lon);
        $where = ['geo_distance' => ['distance' => $distance . 'km', 'location' => ['lat' => $lat, 'lon' => $lon]]];
        if (!is_null($sex)) $whre['sex'] = $sex;
        return static::getModel($min, 1)->postFilter($where)->exists();
    }
}