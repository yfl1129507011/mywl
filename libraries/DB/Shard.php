<?php
/**
 * Created by PhpStorm.
 * User: acewill
 * Date: 2018/12/7
 * Time: 14:16
 */
class DB_Shard {

    public static $rules = array(
        'mod',  // 按字段的值求模切分
        'range',  // 按范围切分
        'direct', // 直接按字段切分
        'distribute',  // 根据配置按cid分库，cardid分表
    );

    public static function getDB($shardConfig, $shardKey){
        if (!in_array($shardConfig['rule'], self::$rules)){
            throw new Exception('Invalid Shard rule:' . $shardConfig['rule']);
        }
        if (!is_array($shardConfig['key'])){
            $shardConfig['key'] = array($shardConfig['key']);
        }
        foreach ($shardKey as $k=>$v){
            if (!in_array($k, $shardConfig['key'])){
                throw new Exception('Invalid Shard key:' . $k . '. Expected Shard key:' . implode(',', $shardConfig));
            }
        }
        $shardValue = array_values($shardKey);
    }
}