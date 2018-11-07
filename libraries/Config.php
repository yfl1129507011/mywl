<?php
/**
 * Created by PhpStorm.
 * User: acewill
 * Date: 2018/11/2
 * Time: 14:58
 */
class Config {
    protected static $_instance = null;

    private $_values = array();

    public static function &getInstance(){
        if (!self::$_instance instanceof self){
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public static function set($key, $value){
        $config = &Config::getInstance();
        $config->_values[$key] = $value;
    }

    public static function get($key, $defaultValue=null){
        $config = &Config::getInstance();
        $value = $config->_get($key);
        if(!$value) return $defaultValue;
        return $value;
    }

    protected function _get($key){
        if ($this->_values[$key]){
            return $this->_values[$key];
        }

        $value = $this->_loadKey($key);
        Config::set($key, $value);

        return $value;
    }

    protected function _loadKey($key){
        $path = explode('.', $key);
        $cnt = count($path);
        $apps = Bootstrap_Env::get('apps');
        if(!in_array($path[0], $apps)) return null;
        $file = ROOT_PATH . $path[0] . '/config/' . $path[1] . '.php';
        $cnt -= 2;
        $rootKey = $path[0] . '.' . $path[1];
        if(!is_file($file)){
            $file = ROOT_PATH . $path[0] . '/config/' . $path[1] . '/' . $path[2] . '.php';
            if(!is_file($file)) return null;
            $cnt -= 1;
            $rootKey .= '.' . $path[2];
        }
        require_once($file);
        if (!isset($conf)) return null;
        if($cnt<1) return $conf;
        if (is_array($conf)){
            foreach ($conf as $_k=>$_v){
                Config::set($rootKey.'.'.$_k, $_v);
            }
        }

        if ($this->_values[$key]) {
            return $this->_values[$key];
        }

        $confLev = array();
        $data = array();
        while ($cnt--){
            $node = array_pop($path);
            array_unshift($confLev, $node);
            $_pattern = implode('.', $path);
            if(isset($this->_values[$_pattern])){
                $data = $this->_values[$_pattern];
                break;
            }
        }

        if($data){
            $ret = $data;
            foreach ($confLev as $_inx){
                if(isset($ret[$_inx])){
                    $ret = $ret[$_inx];
                }else{
                    $ret = null;break;
                }
            }

            return $ret;
        }

        return null;
    }
}