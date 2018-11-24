<?php
/**
 * Created by PhpStorm.
 * User: acewill
 * Date: 2018/11/24
 * Time: 14:55
 */
class Config_Cache {
    protected static $_configs = array();

    public static function getAppEnv(){
        $env = getenv('APPENV');
        $appenvFile = '/data/webapps/appenv';
        if(!$env && file_exists($appenvFile)){
            $appenv = parse_ini_file($appenvFile);
            $env = $appenv['deployenv'];
        }
        if(!$env) $env = 'dev';

        return $env;
    }

    public static function getBatchValue(){
        $env = '/'.self::getAppEnv();
        if (empty(self::$_configs)){
            self::$_configs = QConf::getBatchConf($env);
        }
    }

    public static function get($key){
        self::getBatchValue();

        return array_key_exists($key, self::$_configs)?self::$_configs[$key]:'';
    }
}