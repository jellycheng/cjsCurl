<?php
require dirname(__DIR__) . '/vendor/autoload.php';

//登录
$username = "";
$pwd = "";
$postData = array(
    'account'=>$username,
    'pwd'=>$pwd,
    'type'=>1
);
$url = 'http://api.dev.qianguopai.com/1.0/user/login';
$curlObj = \CjsCurl\Curl::boot()->post($url, $postData);

if(!$curlObj->getErrno()) {
    $content = $curlObj->getResponse();
    echo $content . PHP_EOL;
} else {
    echo "error no: " . $curlObj->getErrno() . PHP_EOL;
    echo "error msg: " . $curlObj->getErrmsg() . PHP_EOL;
}


