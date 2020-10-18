<?php


namespace xing\helper\yii;

use yii\db\Transaction;

/**
 * Class YiiTransaction
 * @package xing\helper\yii
 * @property Transaction[] $transaction
 */
class YiiTransaction
{
    static $transaction = [];

    /**
     * @param $class
     */
    public static function addTransaction($class)
    {
        $transaction[] = $class::getDb()->beginTransaction();
    }

    public static function commit()
    {
        foreach (static::$transaction as $db) $db->commit();
    }

    public static function rollBack()
    {
        array_reverse(static::$transaction);
        foreach (static::$transaction as $db) $db->rollBack();
        static::$transaction = [];
    }
}