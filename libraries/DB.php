<?php
/**
 * Created by PhpStorm.
 * User: acewill
 * Date: 2018/12/4
 * Time: 15:13
 */

class DB {
    private static $_conns = array();
    # 数据库资源句柄
    private $_dbh;
    # 数据源名称
    private $_dsn;
    # 记录执行时间
    private $_bTime;
    # 记录影响的行数
    private $_affectedRow;

    /**
     * DB constructor.
     * @param $dsn
     * @param $user
     * @param $password
     * @param string $charset
     */
    private function __construct($dsn, $user, $password, $charset='utf8')
    {
        $this->_dsn = $dsn;
        try{
            $this->_dbh = new PDO($dsn, $user, $password);
            $this->_dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->_dbh->exec("SET NAMES $charset");
        }catch (PDOException $e){
            echo $e->getMessage();exit();
        }
    }

    /**
     * @param $dsn
     * @param $user
     * @param $password
     * @param bool $forceConnect 是否强制连接
     * @param string $charset
     * @return mixed
     */
    public static function connect($dsn, $user, $password, $forceConnect=false, $charset='utf8'){
        if($forceConnect || !array_key_exists($dsn, self::$_conns)){
            self::$_conns[$dsn] = new DB($dsn, $user, $password, $charset);
        }

        return self::$_conns[$dsn];
    }

    /**
     * 开启事务
     * @return bool
     */
    public function begin(){
        return $this->_dbh->beginTransaction();
    }

    /**
     * 提交事务
     * @return bool
     */
    public function commit(){
        return $this->_dbh->commit();
    }

    /**
     * 是否开启事务
     * @return bool
     */
    public function inTransaction(){
        return $this->_dbh->inTransaction();
    }

    /**
     * 事务回滚
     * @return bool
     */
    public function rollBack(){
        return $this->_dbh->rollBack();
    }

    /**
     * 获取单条记录
     * @param $query
     * @param array $params
     * @return mixed
     */
    public function fetchRow($query, $params=array()){
        $this->_bTime = microtime(true);
        $stmt = $this->_dbh->prepare($query);
        $stmt->execute($params);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row;
    }

    /**
     * 获取多条记录
     * @param $query
     * @param array $params
     * @return array
     */
    public function fetchAll($query, $params=array()){
        $this->_bTime = microtime(true);
        $stmt = $this->_dbh->prepare($query);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $rows;
    }

    /**
     * 获取记录的第一行第一列
     * @param $query
     * @param array $params
     * @return array|mixed
     */
    public function fetchOne($query, $params=array()){
        $this->_bTime = microtime(true);
        $stmt = $this->_dbh->prepare($query);
        $result = $stmt->execute($params);
        $row = array();
        if($result){
            $row = $stmt->fetchColumn();
        }

        return $row;
    }

    /**
     * 语句执行
     * @param $query
     * @param array $params
     * @return bool
     */
    public function exec($query, $params=array()){
        $this->_bTime = microtime(true);
        $stmt = $this->_dbh->prepare($query);
        $result = $stmt->execute($params);
        $this->_affectedRow = $stmt->rowCount();

        return $result;
    }

    /**
     * 获取最后记录id
     * @return string
     */
    public function lastInsertId(){
        return $this->_dbh->lastInsertId();
    }

    /**
     * 关闭连接
     * @param null $dsn
     */
    public function close($dsn=null){
        if($dsn){
            self::$_conns[$dsn] = NULL;
        }else{
            $this->_dbh = NULL;
        }
    }

    /**
     * 返回受影响的行数
     * @return mixed
     */
    public function getAffectedRow(){
        return $this->_affectedRow;
    }
}

$dsn = "mysql:host=localhost;dbname=fenlon;";
$user = "root";
$dbh = DB::connect($dsn, $user, '123456');

$query = "select * from test where id=?";
$params = array(1);

$result = $dbh->fetchRow($query, $params);
var_dump($result);