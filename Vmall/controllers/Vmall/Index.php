<?php
/**
 * Created by PhpStorm.
 * User: acewill
 * Date: 2018/11/7
 * Time: 16:26
 */
class Vmall_IndexController extends Yaf_Controller_Abstract{

    public function init(){

    }

    public function indexAction(){
        // $this->getView(); 展示模板视图
        Yaf_Dispatcher::getInstance()->disableView();
        //echo $this->getModuleName();  // 获取模块名称  Index
        echo $this->getViewPath();

    }

    public function demoAction(){
        //Yaf_Dispatcher::getInstance()->disableView();
        /*$menu = Config::get('Vmall.menu.list.Boss_Index.index');
        var_dump($menu);*/
        //echo Manager_src_Entity_Manager::DEFAULT_CASHIER_ID;
        /*$curClass = new ReflectionClass('Manager_src_Entity_Manager');
        echo '<pre>';
        print_r($curClass->getConstants());*/
        $var = Consts::get('Manager', 'Manager_src_Entity_Manager', 'DEFAULT_SHOP_MANAGER_LEVEL');
        var_dump($var);
        echo $this->_request->getActionName();
    }

    public function homeAction(){
        Yaf_Dispatcher::getInstance()->disableView();
        echo $this->_request->getActionName();
    }
}