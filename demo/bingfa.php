<?php
/**
 * 并发示例
 */
require __DIR__ . '/common.php';

$startTime = microtime(true);

//并发请求多渠道广告
$res = \CjsCurl\BfCurl::doRequest(['login'=>\CjsCurl\BfCurl::getCurlHandle(
                                                                        [CURLOPT_URL=>"http://devapi.nfangbian.com/login/index",
                                                                            CURLOPT_HEADER=>0,
                                                                            CURLOPT_RETURNTRANSFER=>1,
                                                                        ]
                                    ),
                                    'baidu'=>\CjsCurl\BfCurl::getCurlHandle(
                                                                            [CURLOPT_URL=>"http://www.baidu.com",
                                                                                CURLOPT_HEADER=>0,
                                                                                CURLOPT_RETURNTRANSFER=>1,
                                                                            ]
                                    ),
                                    'php'=>\CjsCurl\BfCurl::getCurlHandle(
                                                                        [CURLOPT_URL=>"http://www.php.net",
                                                                            CURLOPT_HEADER=>0,
                                                                            CURLOPT_RETURNTRANSFER=>1,
                                                                        ]
                                    ),
//                                    'noturl'=>\CjsCurl\BfCurl::getCurlHandle(
//                                                                        [CURLOPT_URL=>"http://www.aaaaxxxx.com",
//                                                                            CURLOPT_HEADER=>0,
//                                                                            CURLOPT_RETURNTRANSFER=>1,
//                                                                        ]
//                                    ),
                                    'local_req'=>\CjsCurl\BfCurl::getCurlHandle(
                                                                        [CURLOPT_URL=>"http://127.0.0.1:9898/req.php?a=1&b=hello",
                                                                            CURLOPT_HEADER=>0,
                                                                            CURLOPT_RETURNTRANSFER=>1,
                                                                            CURLOPT_POST=>true,
                                                                            CURLOPT_POSTFIELDS=>http_build_query(['username'=>'jelly', 'pwd'=>123]),
                                                                        ]
                                    ),
]);



//file_put_contents(__DIR__ . '/bingfalog.log', var_export($res, true));

if(isset($res['login']) && $res['login'] instanceof \CjsCurl\DataRet) {
    echo $res['login']->getResult() . PHP_EOL;
} else {
    echo "请求login发生网络异常" . PHP_EOL;
}

if(isset($res['local_req']) && $res['local_req'] instanceof \CjsCurl\DataRet) {
    echo $res['local_req']->getResult() . PHP_EOL;
} else {
    echo "请求local_req发生网络异常" . PHP_EOL;
}


echo 'finish: ' . (microtime(true) - $startTime) . PHP_EOL;
