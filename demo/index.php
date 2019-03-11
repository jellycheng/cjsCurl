<?php
require __DIR__ . '/common.php';

echo "curl demo" . PHP_EOL;


$curlObj = \CjsCurl\Curl::boot()->get();

if(!$curlObj->getErrno()) {
    $content = $curlObj->getResponse();
    echo $content . PHP_EOL;
} else {
    echo "error no: " . $curlObj->getErrno() . PHP_EOL;
    echo "error msg: " . $curlObj->getErrmsg() . PHP_EOL;
}
