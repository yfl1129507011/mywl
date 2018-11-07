<?php
/**
 * Created by PhpStorm.
 * User: acewill
 * Date: 2018/11/7
 * Time: 16:26
 */
class Vmall_IndexController extends Yaf_Controller_Abstract{

    public function init(){
        echo 'init';
    }

    public function indexAction(){
        Yaf_Dispatcher::getInstance()->disableView();
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