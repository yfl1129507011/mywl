<?php
/**
 * Created by PhpStorm.
 * User: acewill
 * Date: 2018/11/5
 * Time: 18:29
 */

function _curl($url, $data=null, $timeout=0){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    if(!empty($data)){
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    if ($timeout > 0) { //超时时间秒
        curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
    }
    $output = curl_exec($curl);
    $error = curl_errno($curl);
    curl_close($curl);

    if($error){
        return false;
    }
    return $output;
}

$postData = array();
$postData['username'] = 'test';
//$postData['client_ip'] = $_SERVER['REMOTE_ADDR'];
$url = 'http://39.105.168.42/trusted';
$data = _curl($url,$postData);
echo $data;  //  nsZpo0abSwG8Yoo-f4B7LA==:XLvYbruy6crnWe6X9rZu1O6E