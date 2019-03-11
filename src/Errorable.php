<?php
namespace CjsCurl;

class Errorable {

    protected $errno = 0; //0表正常，非0表示发生错误
    protected $errmsg = '';//错误信息

    protected function setErrno($errno) {
        $this->errno = $errno;
        return $this;
    }

    public function getErrno() {
        return $this->errno;
    }

    protected function setErrmsg($errmsg) {
        $this->errmsg = $errmsg;
        return $this;
    }

    public function getErrmsg() {
        return $this->errmsg;
    }

    protected function setErr($errno, $errmsg)
    {
        $this->errno  = $errno;
        $this->errmsg = $errmsg;
        return $this;
    }

    protected function clearErr()
    {
        $this->errno  = 0;
        $this->errmsg = '';

    }

}