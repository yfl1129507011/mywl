<?php
/**
 * Created by PhpStorm.
 * User: acewill
 * Date: 2018/11/9
 * Time: 17:20
 */
class ErrorController extends Yaf_Controller_Abstract{
    public function errorAction($exception){
        Yaf_Dispatcher::getInstance()->disableView();
        var_dump($exception->getMessage());
    }
}