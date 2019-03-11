<?php
require __DIR__ . '/common.php';


$url = 'http://guihua.nfangbian.com/';
$curlObj = \CjsCurl\Curl::boot()->get($url);

if(!$curlObj->getErrno()) {
    $content = $curlObj->getResponse();
    echo $content . PHP_EOL;
} else {
    echo "error no: " . $curlObj->getErrno() . PHP_EOL;
    echo "error msg: " . $curlObj->getErrmsg() . PHP_EOL;
}

