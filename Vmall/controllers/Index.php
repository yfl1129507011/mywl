<?php
/**
 * Created by PhpStorm.
 * User: acewill
 * Date: 2018/11/2
 * Time: 15:21
 */
class IndexController extends Yaf_Controller_Abstract{
    public function indexAction(){
        Yaf_Dispatcher::getInstance()->disableView();
        // Config::get('fenlon');

        echo '<pre>';
        print_r(array_map('basename', glob(ROOT_PATH.'/*', GLOB_ONLYDIR)));
        die;

        $manager = new Manager_Index();
        echo $manager->getName();
    }
}