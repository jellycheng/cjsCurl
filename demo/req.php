<?php
/**
 * php -S 127.0.0.1:9898 -t demo
 * http://127.0.0.1:9898/req.php?a=1&b=hello
 */

$ret = [
        'method'=>$_SERVER['REQUEST_METHOD'],
        'get'=>$_GET,
        'post'=>$_POST,
        'body'=>file_get_contents("php://input")
];

echo json_encode($ret, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
