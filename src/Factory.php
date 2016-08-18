<?php
namespace CjsCurl;


class Factory {

    public static function getHttpRequest() {
        //todo
    }

    public static function __callStatic($method, $parameters)
    {
        $instance = new Curl;
        return call_user_func_array(array($instance, $method), $parameters);
    }

}