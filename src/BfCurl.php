<?php
namespace CjsCurl;

/**
 * 并发请求类
 * @package CjsCurl
 */
class BfCurl
{

    public static function getCurlHandle($options = []) {
        $ch = curl_init();
        foreach ((array)$options as $option=>$v) {
            curl_setopt($ch, $option, $v);
        }
        return $ch;
    }


    public static function doRequest($handleAry = []) {
        $ret = [];
        $mh = curl_multi_init();
        foreach ($handleAry as $handle) {
            if($handle) {
                curl_multi_add_handle($mh, $handle);
            }
        }
        //执行
        $still_running = null;
        do {
            $mrc = curl_multi_exec($mh, $still_running);
        } while (CURLM_CALL_MULTI_PERFORM === $mrc);

        while ($still_running && CURLM_OK === $mrc) {
            if (curl_multi_select($mh) == -1) {
                usleep(1);
            }
            do {
                $mrc = curl_multi_exec($mh, $still_running);
            } while (CURLM_CALL_MULTI_PERFORM === $mrc);
        }

        if (CURLM_OK === $mrc) {//并发没有问题，获取结果
            foreach ($handleAry as $chKey => $ch) {
                $errstr = curl_error($ch);
                if ($errstr) {
                    $errno = curl_errno($ch)?:999999;//有时候发生错误时，错误号也是0
                    $ret[$chKey] = DataRet::getInstance()->setErrmsg($errstr)->setErrno($errno);
                } else {
                    $ret[$chKey] = DataRet::getInstance()->setResult(curl_multi_getcontent($ch));
                }
            }
        }

        foreach ($handleAry as $handle2) {
            if($handle2) {
                curl_multi_remove_handle($mh, $handle2);
            }
        }

        curl_multi_close($mh);
        return $ret;

    }

}