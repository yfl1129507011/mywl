<?php
/**
 * Created by PhpStorm.
 * User: acewill
 * Date: 2018/11/7
 * Time: 16:24
 */
$conf['regex'][] = array(
    'match' => "#.*#",
    'route' => array(
        'controller' => "Vmall_Index",
        'action' => "index"
    ),
    'map' => array(),
);

$conf['regex'][] = array(
    'match' => '#/(?)$#',
    'route' => array(
        'controller' => 'Vmall_Index',
        'action' => 'index'
    ),
    'map' => array(),
);

$conf['regex'][] = array(
    'match' => '#/home$#',
    'route' => array(
        'controller' => 'Vmall_Index',
        'action' => 'home'
    ),
    'map' => array(),
);