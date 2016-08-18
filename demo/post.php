<?php
require dirname(__DIR__) . '/vendor/autoload.php';

//登录
$username = "13798999993";
$pwd = "ffb7d13e54589ee5bad905ca4d6ab8b0";
$postData = array(
    'account'=>$username,
    'pwd'=>$pwd,
    'type'=>1
);
$url = 'http://api.dev.qianguopai.com/1.0/user/login';
$curlObj = \CjsCurl\Curl::boot()->setHeaders(array('Content-Type'=>'application/x-www-form-urlencoded', 'APP-TYPE'=>'buy',))->post($url, $postData);

//var_export($curlObj->getOption());

if(!$curlObj->getErrno()) {
    $content = $curlObj->getResponse();
    echo $content . PHP_EOL;
} else {
    echo "error no: " . $curlObj->getErrno() . PHP_EOL;
    echo "error msg: " . $curlObj->getErrmsg() . PHP_EOL;
}


