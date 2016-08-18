<?php
require dirname(__DIR__) . '/vendor/autoload.php';



$url = 'http://api.dev.qianguopai.com/';
$curlObj = \CjsCurl\Curl::boot()->get($url);

if(!$curlObj->getErrno()) {
    $content = $curlObj->getResponse();
    echo $content . PHP_EOL;
} else {
    echo "error no: " . $curlObj->getErrno() . PHP_EOL;
    echo "error msg: " . $curlObj->getErrmsg() . PHP_EOL;
}

