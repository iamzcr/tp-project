<?php
class Wx_WechatJSON {
    const
        QR_SCENE = 'QR_SCENE',
        QR_LIMIT_SCENE = 'QR_LIMIT_SCENE',
        QR_LIMIT_STR_SCENE = 'QR_LIMIT_STR_SCENE',
        IMAGE = 'image',
        VOICE = 'voice',
        VIDEO = 'video',
        NEWS = 'news',
        MPNEWS = 'mpnews',
        THUMB = 'thumb',
        PERMANENT = 'permanent',
        LIMIT = 'limit',
        API_URL_PREFIX = 'https://api.weixin.qq.com/cgi-bin',
        AUTH_API_URL = 'https://api.weixin.qq.com/sns/oauth2/access_token',
        AUTH_URL = 'https://open.weixin.qq.com/connect/oauth2/authorize',
        PAY_URL = 'https://api.weixin.qq.com/pay',
        API_CUSTOMER_SERVICE_URL = 'https://api.weixin.qq.com/customservice',
        API_DATA_CUBE_URL = 'https://api.weixin.qq.com/datacube',
        API_CARD_URL = 'https://api.weixin.qq.com/card',
        APP_ID = 'appid',
        APP_SECRET = 'secret',
        TIMEOUT = 'timeout',
        JSON = 'json',
        POST = 'post',
        GET = 'get',
        API_TYPE_CGI = 'cgi',
        API_TYPE_PAY = 'pay',
        API_TYPE_DATA = 'datacube',
        API_TYPE_SERVICE = 'customservice',
        API_TYPE_CARD = 'wx_card',
        API_TYPE_JS = 'jsapi';

    public
        $_error_number = 0,
        $_error,
        $_APPID,
        $_APPSECRET;

    protected
        $_cache = array(),
        $_options,
        $_auth_access_token,
        $_access_token,
        $_timeout = 30;

    static protected
        $_no_need_token_apis = array(
        '/showqrcode',
    ),
        $_instance;

    /**
     * 单例模式
     * @param array $options
     * @return Wechat_API
     */
    static public function getInstance(array $options = array()) {
        if (empty(self::$_instance)) {
            self::$_instance = new static($options);
        }
        return self::$_instance;
    }

    /**
     * @param array $options {Wechat_API::APP_ID:"", Wechat_API::APP_SECRET:"", Wechat_API::TIMEOUT:""}
     */
    public function __construct(array $options = array()) {
        $this->_options = array(
            'timeout' => $this->_timeout,
        );
        $_options = array_merge($this->_options, $options);
        $this->_APPID = C('appid');
        $this->_APPSECRET = C('appsecret');
//        $this->_APPID = $_options['appid'];
//        $this->_APPSECRET = $_options['secret'];
        $this->_timeout = $_options['timeout'];
    }

    /**
     * 提交请求
     * @param $url
     * @param array $params
     * @param string $type Wechat_API::POST|Wechat_API::GET
     * @return bool|mixed
     */
    public function request($url, $params = array(), $type = self::POST, $format_result = true) {
        $ch = curl_init();
        if ($type == self::GET) {
            $url = $url.'?'.http_build_query($params);
        }
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_TIMEOUT => $this->_timeout,
            CURLOPT_USERAGENT => 'wechat_client/0.1.'.rand(1,6),
            CURLOPT_HEADER => 0,
            CURLOPT_FOLLOWLOCATION => 1,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_SSL_VERIFYPEER => 0,
            CURLOPT_SSLVERSION => 1,
//            CURLOPT_VERBOSE => 1,
        ));
        if ($type == self::POST) {
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        }
        if ($type == self::JSON) {
            //微信的破接口竟然不支持unicode转义符，违反JSON协定，只能把JSON字符中的unicode转回来
            if(version_compare(PHP_VERSION,'5.5.0','<')) {
                //php5.4
                $data = preg_replace('/\\\\u([a-f0-9]{4})/e', "json_decode('\"$0\"', 1)", json_encode($params));
            } else {
                //php5.6
                $data = preg_replace_callback('/\\\\u([a-f0-9]{4})/', function ($matches) {
                    return json_decode("\"$matches[0]\"", 1);
                }, json_encode($params));
            }
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        }
        $res = curl_exec($ch);
        $this->_error_number = curl_errno($ch);
        $this->_error = curl_error($ch);
        curl_close($ch);

        if ($this->_error_number) {
            return false;
        }
        return ($format_result ? $this->parseResult($res) : $res);
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

    public function getAccessToken() {
//        $cache = $this->cache($this->_APPID.':'.'access_token');
//        if ($cache) {
//            return $cache;
//        }
        $res = $this->request(self::API_URL_PREFIX.'/token', array(
                'grant_type' => 'client_credential',
                self::APP_ID => $this->_APPID,
                self::APP_SECRET => $this->_APPSECRET,
            ), self::GET);
        if ($res) {
            $this->cache($this->_APPID.':'.'access_token', $res['access_token']);
            return $res['access_token'];
        }
        return false;
    }

    /**
     * 预留缓存接口 (强烈建议实现此接口，用于缓存access_token，每次查询会节省很多时间)
     * @param $key 缓存索引key
     * @param null $value 缓存值
     * @param int $timeout 缓存超时时间
     * @return bool|mixed
     */
    public function cache($key, $value = null, $timeout = 7200) {
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

    /**
     * 调用具体的接口 注意:使用创建数据时使用Wechat_API::JSON
     * @param $api_name REST规格接口名称
     * @param array $params 接口参数
     * @param string $type Wechat_API::GET|Wechat_API::POST|Wechat_API::JSON
     * @return bool|mixed
     */
    public function call($api_name, $params = array(), $type = self::GET, $api_type = self::API_TYPE_CGI) {
        switch(true) {
            case $api_type == self::API_TYPE_PAY :
                $url = self::PAY_URL.$api_name;
                break;
            case $api_type == self::API_TYPE_DATA:
                $url = self::API_DATA_CUBE_URL.$api_name;
                break;
            case $api_type == self::API_TYPE_SERVICE:
                $url = self::API_CUSTOMER_SERVICE_URL.$api_name;
                break;
            case $api_type == self::API_TYPE_CARD:
                $url = self::API_CARD_URL.$api_name;
                break;
            default :
                $url = self::API_URL_PREFIX.$api_name;
        }
        if (in_array($api_name, self::$_no_need_token_apis)) {
            $res = $this->request($url, $params, $type);
            if ($res) {
                return $res;
            }
        }
        $this->_access_token = $this->getAccessToken();
        if ($this->_access_token) {
            if ($type == self::JSON || $api_type == self::API_TYPE_DATA) {
                $url = $url.'?access_token='.$this->_access_token;
            } else {
                $params['access_token'] = $this->_access_token;
            }
            $res = $this->request($url, $params, $type);
            if ($res) {
                return $res;
            }
        }
        return false;
    }

    /**
     * 生成二维码
     *
     * @param int $scene_id 场景ID 临时二维码int32 | 永久二维码 < 10000
     * @param string $type 二维码类型 Wechat_API::QR_LIMIT_SCENE | Wechat_API::QR_SCENE 临时二维码|永久二维码
     * @return bool|mixed
     */
    public function GetQrCode($scene = '', $type = self::QR_LIMIT_SCENE) {
        $params = array(
            'expire_seconds' => 1800,
            'action_name' => $type,
            'action_info' => array(
                'scene' => array()
        ));
        if($type == self::QR_LIMIT_SCENE) {
            $params['action_info']['scene'] = array('scene_id' => $scene,);
        } else {
            $params['action_info']['scene'] = array('scene_str' => $scene,);
        }
        $res = $this->call('/qrcode/create', $params, self::JSON);
        if ($res && isset($res['ticket'])) {
            $res = $this->request('https://mp.weixin.qq.com/cgi-bin/showqrcode', array(
                'ticket' => $res['ticket'],
            ), self::GET, false);
            if ($res) {
                return $res;
            }
        }
        return false;
    }


    public function GetQrCodeTicket($scene = '', $type = self::QR_LIMIT_SCENE) {
        $params = array(
            'expire_seconds' => 1800,
            'action_name' => $type,
            'action_info' => array(
                'scene' => array()
            ));
        if($type == self::QR_LIMIT_SCENE) {
            $params['action_info']['scene'] = array('scene_id' => $scene,);
        } else {
            $params['action_info']['scene'] = array('scene_str' => $scene,);
        }
        $res = $this->call('/qrcode/create', $params, self::JSON);
        if ($res  && isset($res['ticket'])) {
            return $res['ticket'];
        }
        return false;
    }


    public function materialUpload($file_full_path, $type = self::THUMB, $limit = self::PERMANENT, $video = array()) {
        $this->_access_token = $this->getAccessToken();
        $res = false;
        if ($this->_access_token) {
            $url = 'https://api.weixin.qq.com/cgi-bin/media/upload?';
            if($limit == self::PERMANENT)
                $url = 'http://api.weixin.qq.com/cgi-bin/material/add_material?';
            $url = $url.'access_token='.$this->_access_token.'&type='.$type;
            $data = array('media' => '@'.$file_full_path);
            if($type == self::VIDEO && count($video)) {
                $data = array_merge($data, array(
                    'description' => json_encode($video),
                ));
            }
            $res = $this->request($url, $data, self::POST);
        }
        return $res;
    }

    public function getMaterial($media_id) {
        $this->_access_token = $this->getAccessToken();
        if ($this->_access_token) {
            $url = 'https://api.weixin.qq.com/cgi-bin/media/get';
            $res = $this->request($url, array(
                'access_token' => $this->_access_token,
                'media_id' => $media_id,
            ), self::GET, false);
            if ($res) {
                $res_json = json_decode($res, 1);
                if (!$res_json) {
                    return $res;
                }
                $this->_error_number = $res_json['errcode'];
                $this->_error = $res_json['errmsg'];
            }
        }
        return false;
    }

    public function MediaUpload($file_full_path, $type = self::THUMB) {
        $this->_access_token = $this->getAccessToken();
        $res = false;
        if ($this->_access_token) {
            $url = 'http://file.api.weixin.qq.com/cgi-bin/media/upload?';
            $url = $url.'access_token='.$this->_access_token.'&type='.$type;
            $res = $this->request($url, array(
                'media' => '@'.$file_full_path,
            ), self::POST);
        }
        return $res;
    }

    public function CardlogoUpload($file_full_path) {
        $this->_access_token = $this->getAccessToken();
        $res = false;
        if ($this->_access_token) {
            $url = 'http://api.weixin.qq.com/cgi-bin/media/uploadimg?';
            $url = $url.'access_token='.$this->_access_token.'&type=image';
            $res = $this->request($url, array(
                'media' => '@'.$file_full_path,
            ), self::POST);
        }
        return $res;
    }


    public function GetMedia($media_id) {
        $this->_access_token = $this->getAccessToken();
        if ($this->_access_token) {
            $res = $this->request('http://file.api.weixin.qq.com/cgi-bin/media/get', array(
                'access_token' => $this->_access_token,
                'media_id' => $media_id,
            ), self::GET, false);
            if ($res) {
                $res_json = json_decode($res, 1);
                if (!$res_json) {
                    return $res;
                }
                $this->_error_number = $res_json['errcode'];
                $this->_error = $res_json['errmsg'];
            }
        }
        return false;
    }

}
