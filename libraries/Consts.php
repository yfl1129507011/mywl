<?php
/**
 * Created by PhpStorm.
 * User: acewill
 * Date: 2018/11/7
 * Time: 18:39
 * 获取Entity文件目录下的类常量
 */
class Consts{

    public static function get($appName, $entityName, $constName){
        $key = self::generateKey($appName, $entityName, $constName);
        $ret = Yaf_Registry::get($key);
        if(empty($ret)){
            $entityFile = explode('_', $entityName);
            $entityFile = implode('/', array_slice($entityFile, 3));
            $file = ROOT_PATH.'/'.$appName.'/src/Entity/'.$entityFile.'.php';
            if (is_file($file)) {
                //require_once ($file);
                $curClass = new ReflectionClass($entityName);
                $constNames = $curClass->getConstants();
                if ($constNames) {
                    foreach ($constNames as $k => $v) {
                        $key = self::generateKey($appName, $entityName, $k);
                        Yaf_Registry::set($key, $v);
                        if ($k == $constName) {
                            $ret = $v;
                        }
                    }
                }
            }
        }
        return $ret;
    }

    protected static function generateKey($appName, $entityName, $constName){
        return $appName . '_' . $entityName . '_' . $constName;
    }
}