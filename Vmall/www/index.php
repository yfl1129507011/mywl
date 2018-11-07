<?php
/**
 * Created by PhpStorm.
 * User: acewill
 * Date: 2018/11/2
 * Time: 11:32
 */

error_reporting(E_ALL^E_STRICT^E_NOTICE);

define('APP_PATH', realpath(dirname(__FILE__).'/../../Vmall').'/');

define('ROOT_PATH', realpath(dirname(__FILE__).'/../..').'/');

$app = new Yaf_Application(APP_PATH.'config/application.ini');

try{
    define('ROUTE_FILE', 'vmall');
    $app->bootstrap();  # 加载APP_PATH.'Bootstrap.php'类文件并自动运行前缀为_init的方法
    $app->run();
} catch (Exception $e) {
    var_dump($e->getMessage());
    exit();
}