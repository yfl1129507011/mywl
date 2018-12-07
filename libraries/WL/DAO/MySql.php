<?php
/**
 * Created by PhpStorm.
 * User: acewill
 * Date: 2018/12/6
 * Time: 18:25
 */

class WL_DAO_MySql {
    public static $_affectedRow;

    /**
     * @param $table
     * @param $data
     * @param $requiredFields
     * @param $allowedFields
     * @param array $shardKey  分库标识
     * @throws Exception
     */
    public static function add($table, $data, $requiredFields, $allowedFields, $shardKey=array()){
        $fields = array_keys($data);  # 获取字段名称
        $result = array_diff($fields, $allowedFields);  # 查看是否有非法字段名称
        if($result) throw new Exception('Invalid fields:'.implode(',',$result));
        $result = array_diff($requiredFields, $fields); # 查看必要字段名称是否存在
        if ($result) throw new Exception(implode(',', $result).'is required');

        try{
            $db = new DB_Table($table, $shardKey);
            $db->save($data);
            return $db->lastInsertId();
        }catch (PDOException $e){
            echo $e->getMessage();
        }
    }
}