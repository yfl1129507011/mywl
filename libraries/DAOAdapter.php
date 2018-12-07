<?php
/**
 * Created by PhpStorm.
 * User: acewill
 * Date: 2018/11/6
 * Time: 11:33
 */

class DAOAdapter{
    /**
     * 表记录状态
     */
    const STATUS_NORMAL = 1; # 正常记录
    const STATUS_REMOVE = 9; # 删除记录

    protected $hasOpenTransaction;
    private $_createdField;
    private $_updatedField;
    private $_statusField;
    private $_deletedField;

    private $_table;
    private $_pk;

    private $_allowed = array(); # 允许字段
    private $_required = array(); # 必须字段

    private $_dao_class = '';
    private $_entity_class = '';

    private $_has_cache;
    protected static $_tableCacheConfigs; # 表缓存配置
    private static $_cacheRequiredFields = array('table', 'key', 'allowDuplicateKey', 'mysql', 'fields');
    private static $_cacheExtraFields = array('servers', 'show_error', 'connect_time_ms', 'timeout', 'freetime', 'user', 'password');

    protected static $_transactionFlag = false;


    public function __construct()
    {
        $this->_dao_class = get_called_class();
        $dao_class = new ReflectionClass($this->_dao_class);
        $this->_createdField = $dao_class->getStaticPropertyValue('createdField', NULL);
        $this->_updatedField = $dao_class->getStaticPropertyValue('updatedField', null);
        $this->_statusField = $dao_class->getStaticPropertyValue('statusField', null);
        $this->_deletedField = $dao_class->getStaticPropertyValue('deletedField', null);
        $this->_table = $dao_class->getStaticPropertyValue('table', null);
        $this->_pk = $dao_class->getStaticPropertyValue('pk', null);
        $this->_allowed = $dao_class->getStaticPropertyValue('allowed', null);
        $this->_required = $dao_class->getStaticPropertyValue('required', null);
        $this->_has_cache = $dao_class->getStaticPropertyValue('hasCache', false);
        $this->hasOpenTransaction = false;
    }

    public function add($data, $shardKey=array()){
        if (!array_key_exists($this->_createdField, $data)){
            $data[$this->_createdField] = date('Y-m-d H:i:s');
        }
        if ($this->_statusField && !array_key_exists($this->_statusField, $data)){
            $data[$this->_statusField] = self::STATUS_NORMAL;
        }
        $result = array();
        if ($this->_has_cache){
            // 通过memcache进行缓存
            //$result = $this->setCache($data, $shardKey);
        }else{
            try{
                $result = WL_DAO_MySql::add($this->_table, $data, $this->_required, $this->_allowed, $shardKey);
            }catch(Exception $e){
                echo $e->getMessage();die;
            }
        }
        return $result;
    }

}