<?php
/**
 * Created by PhpStorm.
 * User: acewill
 * Date: 2018/12/6
 * Time: 18:42
 */


class DB_Table {
    private $_dbh;  # 数据库操作实例
    private $_table; # 表名
    private $_dbname; # 库名
    private $_conf; # 数据库配置信息
    private $_charset; # 字符集
    private $_forceMaster = false; # 是否强制读取主库

    /**
     * DB_Table constructor.
     * @param $table
     * @param array $shardKey
     * @param bool $forceMaster
     * @throws Exception
     */
    public function __construct($table, $shardKey=array(), $forceMaster=true)
    {
        $conf = self::shardTable($table, $shardKey);
        $this->_table = $conf['table'];
        $this->_charset = $conf['charset'];
        $this->_conf = $conf;
        $this->_dbname = $this->_getDBName($conf);
        $this->_forceMaster = $forceMaster;
    }

    /**
     * 获取库名
     * @param $conf
     * @return bool|string
     */
    public function _getDBName($conf){
        $dsn = $conf['db']['dsn']?$conf['db']['dsn']:$conf['db_slave']['dsn'];
        $dsnArr = explode(';', $dsn);
        $dbname = '';
        foreach ($dsnArr as $key=>$val){
            if (strpos(strtolower($val), 'dbname') !== false){
                $dbname = substr($val, strpos($val, '=')+1);
                break;
            }
        }
        return $dbname;
    }

    /**
     * @param $table
     * @param array $shardKey
     * @return array
     * @throws Exception
     */
    public static function shardTable($table, $shardKey=array()){
        $appName = Bootstrap_Env::get('app_name');
        $module = substr($table, 0, strpos($table, '_'));
        $modules = Config::get($appName.'.db.modules');
        $tables = Config::get($appName.'.db.'.$module.'.tables');
        $slaveTables = Config::get($appName.'.db.'.$module.'.slave_tables');

        if (!in_array($module, $modules)){
            throw new Exception('Can not find db config for table'.$tables);
        }

        $result = array();
        $result['charset'] = $tables[$table]['charset']?$tables[$table]['charset']:'utf8';

        // 不分库的情况
        if (!$shardKey || !count($shardKey) || !array_key_exists($table, $tables)){
            $dsnConfig = Config::get($appName.'.db.'.$module);
            // DSN为多个数据源的数组，如果表没设置dsn，默认取第0个
            $tableMasterDSN = intval($tables[$tables]['dsn']);
            $result['db'] = $dsnConfig[$tableMasterDSN];
            if ($slaveTables && array_key_exists($table, $slaveTables)){
                // 从库的DSN配置
                $tableSlaveDSN = intval($slaveTables[$table]['dsn']);
                $result['db_slave'] = $dsnConfig[$tableSlaveDSN];
            }else{
                $result['db_slave'] = $result['db'];
            }
            $result['table'] = $table;
            return $result;
        }

        /*$shardConfig = Config::get($appName.'.db.'.$module.$table.'.shared');
        if($shardConfig){
            # 分库分表

        }*/
    }

    /**
     * 获取数据库操作句柄
     * @param bool $isMaster
     * @param bool $forceConnect
     * @return mixed
     */
    public function getDbh($isMaster=true, $forceConnect=false){
        $confName = 'db_slave';
        if ($this->_forceMaster || $isMaster){  # 连接主服务
            $confName = 'db';
        }
        $dbConf = $this->_conf[$confName];
        try{
            return DB::connect($dbConf['dsn'], $dbConf['user'], $dbConf['password'], $forceConnect, $this->_charset);
        }catch (Exception $e){
            if (!$forceConnect){
                # 强制连接
                $forceConnect = true;
                try{
                    return DB::connect($dbConf['dsn'], $dbConf['user'], $dbConf['password'], $forceConnect, $this->_charset);
                }catch (Exception $e){
                    echo $e->getMessage();die;
                }
            }else{
                echo $e->getMessage();die;
            }
        }
    }

    /**
     * @param $data
     * @param null $condition
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function save($data, $condition=null, $params=array()){
        $tempParams = array();
        $set = array();
        foreach ($data as $k=>$v){
            array_push($set, $k.'=?');
            array_push($tempParams, $v);
        }
        if ($condition){  # 更新
            $sql = "UPDATE $this->_dbname.$this->_table SET ".implode(',', $set)." WHERE $condition";
            $params = array_merge($tempParams, $params);
        }else{  # 添加
            $sql = "INSERT INTO $this->_dbname.$this->_table SET ".implode(',', $set);
            $params = $tempParams;
        }
        try{
            return self::getDbh()->exec($sql, $params);
        }catch(PDOException $e){
            try{
                return self::getDbh(true, true)->exec($sql, $params);
            }catch (PDOException $e){
                throw new Exception($e->getMessage());
            }
        }
    }

    public function lastInsertId(){
        try{
            return self::getDbh()->lastInsertId();
        }catch (PDOException $e){
            try{
                return self::getDbh(true, true)->lastInsertId();
            }catch (PDOException $e){
                throw new Exception($e->getMessage());
            }
        }
    }
}