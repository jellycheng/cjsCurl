<?php
namespace CjsCurl;

class Errorable {
    protected $errno=0;
    protected $errmsg = '';

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
        $this->errmsg = $errmsg;
        $this->errno  = $errno;
        return $this;
    }

    protected function clearErr()
    {
        $this->errmsg = '';
        $this->errno  = 0;
    }

}