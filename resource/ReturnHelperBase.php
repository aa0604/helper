<?php
/**
 * Created by PhpStorm.
 * User: xing.chen
 * Date: 2019/1/11
 * Time: 13:54
 */

namespace xing\helper\resource;


class ReturnHelperBase
{

    public static $runTime;

    public static function showReturn($return, $msg = '', $status = '1', $code = '0')
    {
        static::showJson(static::return($return, $msg, $status, $code));
    }

    /**
     * 转换为字符串
     * @param $data
     * @return mixed
     */
    public static function transformation($data)
    {
        return $data;
    }
    /**
     * @param $return
     * @param string $msg
     * @param string $status
     * @param string $code
     * @return mixed
     *
     * @var array|\yii\db\ActiveRecord  $return
     */
    public static function return($return, $msg = 'ok', $status = '1', $code = '0')
    {
        $return = static::transformation($return);
        $return['status'] = (string) $status;
        $return['code'] = (string) $code;
        $return['message'] = (string) $msg;
        return $return;
    }

    /**
     * @param array $list
     * @param string $msg
     * @param string $status
     * @param string $code
     * @return mixed
     */
    public static function returnList($list, $msg = 'ok', $status = '1', $code = '0')
    {
        $return['status'] = (string) $status;
        $return['code'] = (string) $code ?: 0;
        $return['message'] = (string) $msg;
        $return['list'] = $list ?: [];
        if(empty($return['list'])) $return['code'] = (string) $code ?: '-1';
        if (!empty($return['list']) && is_array($return['list'])) $return['list'] = static::transformation($return['list']);
        $return['runTime'] = static::$runTime;
        return $return;
    }

    /**
     * @param $data
     * @param string $msg
     * @param string $status
     * @param string $code
     * @return mixed
     */
    public static function returnData($data, $msg = '', $status = '1', $code = '0')
    {
        $return = [
            'status' => (string) $status,
            'code' => (string) $code ?: 0,
            'message' => (string) $msg,
            'data' => $data ?: [],
        ];
        if(empty($return['data'])) $return['code'] = (string) $return['code'] ?: '-1';
        $return['data'] = static::transformation($return['data']);
        return $return;
    }

    /**
     * 中断程序并显示JSON数据
     * @param $data
     */
    public static function showJson($data)
    {
        exit(json_encode($data));
    }

    public static function showData($data, $status = '1', $msg = '', $code = '')
    {
        static::showJson(static::returnData($data, $msg, $status, $code));
    }

    public static function jsonSuccess($msg, $url = '', $code = '')
    {
        static::showJson(static::return(['url' => $url], $msg, '1', $code));
    }

    public static function jsonError($msg, $url = '', $code = '')
    {
        static::showJson(static::return(['url' => $url], $msg, '0', $code));
    }


    /**
     * 返回错误信息
     * @param $msg
     * @param int $code
     * @return array
     */
    public static function error($msg, $code = 0)
    {
        $data = [
            'status' => '0',
            'message' => $msg,
            'code' => (string) $code,
            'data' => [],
        ];
        return $data;
    }

    public static function allowOrigin($domain = '')
    {
        empty($HTTP_ORIGIN) && $HTTP_ORIGIN = $_SERVER['HTTP_ORIGIN'] ?? '';

        header("Access-Control-Allow-Origin: $HTTP_ORIGIN" );
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With");
        header("Access-Control-Allow-Credentials:true");
        header("Access-Control-Max-Age:86400000");
    }
}