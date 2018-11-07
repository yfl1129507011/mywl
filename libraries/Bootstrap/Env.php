<?php
/**
 * Created by PhpStorm.
 * User: acewill
 * Date: 2018/11/5
 * Time: 18:24
 */

class Bootstrap_Env {
    private static $_env = array();
    protected static $_instance = null;

    public static function &getInstance(){
        if (!self::$_instance instanceof self){
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    protected function __construct()
    {
        // [libraries, Manager, Vmall]
        $appNames = array_map('basename', glob(ROOT_PATH.'/*', GLOB_ONLYDIR));
        $this->_set('apps', $appNames);
        $this->_set('root_path', ROOT_PATH);
        $this->_set('app_path', APP_PATH);
    }

    // 对外提供的变量设置接口
    public static function set($key, $value=null){
        $env = self::getInstance();
        $env->_set($key, $value);
    }

    public static function setBenchmark($key, $time=null){
        $env = self::getInstance();
        $benchmark = $env->_get('benchmark');
        if(is_null($time)) $time = microtime(true);
        if (!isset($benchmark[$key])){
            $benchmark[$key] = $time;
        }
        $env->_set('benchmark', $benchmark);
    }

    protected function _set($key, $value){
        if (empty($key)) return false;
        if (is_array($key)){ # 如果是数组则直接加入到全局配置数组中
            self::$_env = array_merge(self::$_env, $key);
        }else{
            self::$_env[$key] = $value;
        }
        return true;
    }

    public static function get($key=null){
        $env = self::getInstance();
        if ('root_path' == $key){
            return ROOT_PATH;
        }elseif('app_path' == $key){
            return ROOT_PATH.'/'.$env->_get('app_name');
        }

        return $env->_get($key);
    }

    protected function _get($key=null){
        if (empty($key)) return self::$_env;
        return self::$_env[$key];
    }
}