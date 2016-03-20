<?php
class Wx_WechatOAuth {

    const
        JSON = 'json',
        POST = 'post',
        GET = 'get',
        APP_ID = 'appid',
        APP_SECRET = 'secret',
        API_URL_PREFIX = 'https://api.weixin.qq.com/sns';

    public
        $_error_number = 0,
        $_error,
        $_APPID,
        $_APPSECRET,
        $authorizer_appid,
        $component_access_token;

    protected
        $_cache = array(),
        $_options,
        $_openid,
        $_access_token,
        $_refresh_token,
        $_timeout = 30;

    static protected
        $_instance;

    /**
     * 单例模式
     * @param array $options
     * @return WechatOAuth
     */
    static public function getInstance(array $options = array()) {
        if (empty(self::$_instance)) {
            self::$_instance = new static($options);
        }
        return self::$_instance;
    }

    /**
     * @param array $options {WechatOAuth::APP_ID:"", WechatOAuth::APP_SECRET:""}
     */
    public function __construct(array $options = array()) {
        $this->_options = array(
            'timeout' => $this->_timeout,
        );
        $_options = array_merge($this->_options, $options);
        $this->_APPID = $_options['appid'];
        $this->authorizer_appid = $_options['authorizer_appid'];
        $platform = WechatPlatform::getInstance($_options);
        $this->component_access_token = $platform->getComponentAccessToken();
        $this->_timeout = $_options['timeout'];
    }

    /**
     * 提交请求
     * @param $url
     * @param array $params
     * @param string $type Webchat_API::POST|Webchat_API::GET
     * @return bool|mixed
     */
    public function request($url, $params = array(), $type = self::POST) {
        $ch = curl_init();
        if ($type == self::GET) {
            $url = $url.'?'.http_build_query($params);
        }
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_TIMEOUT => $this->_timeout,
            CURLOPT_USERAGENT => 'wordpress_wechat_client/0.1.'.rand(1,6),
            CURLOPT_HEADER => 0,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSLVERSION => 1,
        ));
        $res = curl_exec($ch);
        $this->_error_number = curl_errno($ch);
        $this->_error = curl_error($ch);
        curl_close($ch);

        if ($this->_error_number) {
            return false;
        }
        return $this->parseResult($res);
    }

    /**
     * 处理返回结果
     * @param $res
     * @return bool|mixed
     */
    protected function parseResult($res) {
        $res = json_decode($res, true);
        if (!empty($res)) {
            if (isset($res['errcode']) && $res['errcode']) {
                $this->_error_number = $res['errcode'];
                $this->_error = $res['errmsg'];
                return false;
            }
            return $res;
        }
        return false;
    }

    /**
     * 获取当前URL
     * @return string
     */
    static public function getCurrentUrl() {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if (isset($_SERVER['SERVER_PORT']) && $_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            if(isset($_SERVER["SERVER_NAME"]) && isset($_SERVER["REQUEST_URI"]))
                $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }

    /**
     * 获取访问token
     * @param bool $refresh 是否强制刷新
     * @return bool|mixed
     */
    public function getComponentOAuthAccessToken() {
        $code = isset($_GET['code']) ? $_GET['code'] : '';
        //$state = isset($_GET['state']) ? $_GET['state'] : '';
        $res = $this->request(self::API_URL_PREFIX.'/oauth2/component/access_token', array(
            'appid'=> $this->authorizer_appid,
            'code' => $code,
            'grant_type' => 'authorization_code',
            'component_appid' => $this->_APPID,
            'component_access_token' => $this->component_access_token,
        ), self::GET);
        if ($res) {
            $this->_openid = $res['openid'];
            $this->_refresh_token = $res['refresh_token'];
            return $res;
        } else {
            if ($this->_error_number == 40029) {
                $status = $this->refreshComponentOAccessToken();
                return $status;
            }
        }
       return false;
    }

    /**
     * 刷新访问token
     * @return mixed
     */
    public function refreshComponentOAccessToken() {
        $res = $this->request(self::API_URL_PREFIX.'/oauth2/component/refresh_token', array(
            'appid' => $this->authorizer_appid,
            'refresh_token' => $this->_refresh_token,
            'grant_type' => 'refresh_token',
            'component_appid' => $this->_APPID,
            'component_access_token' => $this->component_access_token,
        ), self::GET);
        if ($res) {
            $this->_openid = $res['openid'];
            return $res;
        }
        return false;
    }

    /**
     * 获取用户授权地址
     * @param array $options
     * @return string
     */
    public function getOaUrl($options = array()) {
        $_options = array(
            self::APP_ID => $this->authorizer_appid,
            'redirect_uri' => self::getCurrentUrl().'?showwxpaytitle=1',
            'response_type' => 'code',
            'scope' => 'snsapi_base', //snsapi_base | snsapi_userinfo
            'state' => 'STATE',
            'component_appid' => $this->_APPID
        );
        $params = array_merge($_options, $options);

        return 'https://open.weixin.qq.com/connect/oauth2/authorize?'.http_build_query($params);
    }

    /*
     * 获取用户消息
     */
    public function getUserInfo() {
        $accesstoken = $this->getComponentOAuthAccessToken();
        if($accesstoken) {
            $this->_access_token = $accesstoken['access_token'];
            $res = $this->request(self::API_URL_PREFIX.'/userinfo', array(
                'access_token' => $this->_access_token,
                'openid' => $this->_openid,
            ), self::GET);
            if ($res) {
                return $res;
            }
        }
        return false;
    }

    /**
     * 获取用户登陆二维码
     * @param array $options
     * @return string
     */
    public function getLoginUrl($options = array()) {
        $_options = array(
            'appid' => $this->_APPID,
            'redirect_uri' => self::getCurrentUrl(),
            'response_type' => 'code',
            'scope' => 'snsapi_login',
            'state' => 'STATE',
        );
        $params = array_merge($_options, $options);
        return 'https://open.weixin.qq.com/connect/qrconnect?'.http_build_query($params);
    }

    /**
     * 缓存接口Session实现
     * @param $key 缓存索引key
     * @param null $value 缓存值
     * @return bool|mixed
     */
    public function cache($key, $value = null) {
        if (!session_id()) {
            session_start();
        }
        if (empty($value)) {
            if (isset($_SESSION[$key])) {
                return $_SESSION[$key];
            }
            return false;
        }
        $_SESSION[$key] = $value;
        return false;
    }
}