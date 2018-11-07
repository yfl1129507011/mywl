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

}