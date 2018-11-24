<?php
/**
 * Created by PhpStorm.
 * User: acewill
 * Date: 2018/11/2
 * Time: 11:37
 */
class Bootstrap extends Yaf_Bootstrap_Abstract {
    private $_config;

    # 注册配置信息
    public function _initBootstrap(){
        $this->_config = Yaf_Application::app()->getConfig();
        Yaf_Registry::set('application', $this->_config);
    }

    # 注册本地类前缀的自动加载文件目录
    public function _initLoader(){
        Yaf_Loader::getInstance(ROOT_PATH)->registerLocalNameSpace(array('Manager'));
        Bootstrap_Env::set('app_name', 'Vmall');
    }

    # 添加路由协议
    public function _initRoute(Yaf_Dispatcher $dispatcher){
        if (!defined('ROUTE_FILE')) return false;
        $routes = Config::get('Vmall.route.'.ROUTE_FILE.'.regex');
        if(is_array($routes)){
            foreach ($routes as $k=>$v){
                /*Yaf_Dispatcher::getInstance()->getRouter()->addRoute(
                    $k,new Yaf_Route_Regex($v['match'],$v['route'],$v['map'])
                    );*/
                $dispatcher->getRouter()->addRoute(
                    $k,new Yaf_Route_Regex($v['match'],$v['route'],$v['map'])
                    );
            }
        }
    }
}