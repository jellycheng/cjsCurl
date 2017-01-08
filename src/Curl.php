<?php
namespace CjsCurl;

class Curl extends Errorable{

    const REQUEST_URL_NO_EXISTS = 9999;

    protected $options = array(
                                CURLOPT_TIMEOUT=>5,
                                CURLOPT_CONNECTTIMEOUT => 15,
                                CURLOPT_RETURNTRANSFER=>1,
                                CURLOPT_FOLLOWLOCATION => true,
                                CURLOPT_HEADER=>false,
                                CURLOPT_USERAGENT => 'Mozilla/5.0 (PHP Curl/1.0 (+https://github.com/jellycheng/cjsCurl))',
                            );

    protected $url = null;
    protected $ch = '';
    protected $headers=[];
    protected $responseContent = '';

    public function __construct($url = null) {
        $this->setUrl($url);
    }

    protected function boot() {
       return $this;
    }

    /**
     * http://php.net/manual/zh/function.curl-setopt.php
     * @param $key
     * @param $value
     * @return $this
     */
    public function setOption($key, $value='')
    {
        if(is_array($key)) {
            foreach($key as $_k=>$_v) {
                $this->setOption($_k, $_v);
            }
        } else if(!is_null($key)) {
            $this->options[$key] = $value;
        }
        return $this;
    }

    public function getOption($key=null)
    {
        if(is_null($key)) {
            return $this->options;
        }
        if(isset($this->options[$key])) {
            return $this->options[$key];
        }
        return '';
    }

    public function delOption($key)
    {
        if(isset($this->options[$key])) {
            unset($this->options[$key]);
        }
        return $this;
    }

    public function setHeaders($key, $value='')
    {
        if(is_array($key)) {
            foreach($key as $_k=>$_v) {
                $this->setHeaders($_k, $_v);
            }
        } else if(!is_null($key)) {
            $this->headers[$key] = $value;
        }

        return $this;
    }

    public function getHeaders($key=null)
    {
        if(is_null($key)) {
            return $this->headers;
        }
        if(isset($this->headers[$key])) {
            return $this->headers[$key];
        }
        return '';
    }

    protected function setRequestHeaders() {
        $headers = array();
        if(empty($this->headers)) {
            return $this;
        }
        foreach ($this->headers as $key => $value) {
            $headers[] = $key.': '.$value;
        }
        $this->setOption(CURLOPT_HTTPHEADER, $headers);
        return $this;
    }

    public function setUserAgent($user_agent)
    {
        $this->setOption(CURLOPT_USERAGENT, $user_agent);
        return $this;
    }

    public function setReferrer($referrer)
    {
        $this->setOption(CURLOPT_REFERER, $referrer);
        return $this;
    }

    public function setRequestMethod($method) {
        switch (strtoupper($method)) {
            case 'HEAD':
                $this->setOption(CURLOPT_NOBODY, true);
                break;
            case 'GET':
                $this->setOption(CURLOPT_HTTPGET, true);
                break;
            case 'POST':
                $this->setOption(CURLOPT_POST, true);
                break;
            default:
                $this->setOption(CURLOPT_CUSTOMREQUEST, $method); //PUT , DELETE,
                break;
        }
        return $this;
    }

    public function setUrl($url) {
        if(!$url) {
            $url = null;
        }
        $this->url = $url;
        return $this;
    }

    public function getUrl() {
        if($this->url) {
            $url = $this->url;
        } else {
            $url = $this->getOption('CURLOPT_URL');
            if($url) {
                $this->url = $url;
                $this->delOption('CURLOPT_URL');
            }
        }
        return $url;
    }

    public function get($url = null, $getData = null) {
        if($url) {
            $this->setUrl($url);
        }
        if($getData) {
            if(is_array($getData)) {
                $_getParam = http_build_query($getData);
            } else {
                $_getParam = (string)$getData;
            }
            $url = $this->getUrl();
            if($url) {
                if(preg_match('/\?/', $url)) {
                    $url .= '&' . $_getParam;
                } else {
                    $url .= '?' . $_getParam;
                }
                $this->setUrl($url);
            }
        }
        $this->setOption(CURLOPT_HTTPGET, true);
       return $this->request();
    }

    public function post($url=null, $postData=null) {
        if($url) {
            $this->setUrl($url);
        }
        if(empty($postData)) {
            $postData = '';
        } else if(is_array($postData)) {
            $postData = http_build_query($postData);
        }
        $this->setOption(CURLOPT_POST, true);
        $this->setOption(CURLOPT_POSTFIELDS, $postData);
        return $this->request();
    }

    public function request() {
        $url = $this->getUrl();
        if(!$url) {
            $this->setErr(self::REQUEST_URL_NO_EXISTS, "请求地址不能为空");
            return $this;
        }
        $this->checkSsl($url);
        $this->ch = curl_init($url);
        $this->setRequestHeaders();
        $option = $this->getOption(null);
        if(is_array($option)) {
            if(function_exists('curl_setopt_array')) {
                curl_setopt_array($this->ch, $option);
            } else {
                foreach($option as $k=>$v) {
                    curl_setopt($this->ch, $k, $v);
                }
            }
        }
        $content = curl_exec($this->ch);
        if ($error = curl_errno($this->ch)) {
            $this->setErr($error, curl_error($this->ch));
            return $this;
        }
        curl_close($this->ch);
        return $this->setResponse($content);
    }

    protected function checkSsl($url=null) {
        $url = $url?:$this->getUrl();
        if ($url && true === strstr($url, 'https://', true)) {
            $this->setOption(CURLOPT_SSL_VERIFYPEER, 0);
            $this->setOption(CURLOPT_SSL_VERIFYHOST, 2);
            $this->setOption( CURLOPT_DNS_USE_GLOBAL_CACHE, 0);
        }
    }

    protected function setResponse($content) {
        $this->responseContent = $content;
        return  $this;
    }
    public function getResponse() {
        return $this->responseContent;
    }

    public function __call($method, $parameters)
    {
        if ($method == 'boot') return;

        throw new \BadMethodCallException("Call to undefined method [{$method}]");
    }

    public static function __callStatic($method, $parameters)
    {
        $instance = new static;
        return call_user_func_array(array($instance, $method), $parameters);
    }

}