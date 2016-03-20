<?php
/**
 * 框架全局请求封装
 * 整理了一些框架内部使用的参数
 * @package MST_ActionController
 */
class MST_ActionController_Request extends MST_DataSet {

	private
		$_request = null;

	public
		$post = null,
		$get = null,
		$files = null;

	public function __construct() {
		$this->prepare();
		$this->post = new MST_DataSet($_POST, true);
		$this->get  = new MST_DataSet($_GET, true);
		$this->files  = new MST_DataSet($_FILES,true);
		parent::__construct($this->_request);
		$this->setFlags(ArrayObject::ARRAY_AS_PROPS);
	}

    /**
     * 预处理函数，用于构造request对象
     */
    private function prepare() {
		$ip = $this->getRequestIp();
		$this->_request = array(
			'protocol' => strtolower(substr($_SERVER['SERVER_PROTOCOL'], 0, strpos($_SERVER['SERVER_PROTOCOL'], '/'))),
			'host' => $_SERVER['HTTP_HOST'],
			'port' => $_SERVER['SERVER_PORT'],
			'uri' => MST_Core::getRequestUri(),
			'method' => strtolower($_SERVER['REQUEST_METHOD']),
			'ip' => $ip,
		);
		$this->_request['url'] = "{$this->_request['protocol']}://{$this->_request['host']}{$this->_request['uri']}";
		$this->_request['parse'] = parse_url($this->_request['url']);
		$this->_request = array_merge($_REQUEST, $this->_request);
	}

    /**
     * 获取请求的IP
     * @param bool $isLong 是否返回整形数据
     * @param int $type 0 适配透明代理 | 1 适配匿名代理 | 2 适配无代理
     * @return int
     */
    public function getRequestIp($isLong = false, $type = 0) {
		$ip = 0;
		if ($type == 0) {
			if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			elseif (!empty($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP'];
			elseif (!empty($_SERVER['REMOTE_ADDR'])) $ip = $_SERVER['REMOTE_ADDR'];
		}
		elseif ($type == 1) {
			if (!empty($_SERVER['HTTP_CLIENT_IP'])) $ip = $_SERVER['HTTP_CLIENT_IP'];
			elseif (!empty($_SERVER['REMOTE_ADDR'])) $ip = $_SERVER['REMOTE_ADDR'];
		}
		elseif ($type == 2) {
			if (!empty($_SERVER['REMOTE_ADDR'])) $ip = $_SERVER['REMOTE_ADDR'];
		}
		if ($isLong && $ip !== 0) return ip2long($ip);
		else return $ip;
	}

    /**
     * 验证POST请求数据
     * @param null $prefix
     * @return bool
     */
    public function isPost($prefix = null) {
		$isPost = strtolower($_SERVER['REQUEST_METHOD']) == 'post';
		if ($prefix != null) {
			$code = MST_Core::generateValidCode($prefix);
			$isPost = isset($this->post[$code[0]]) && $this->post[$code[0]] == $code[1];
		}
		return $isPost;
	}

    /**
     * 是否Ajax请求
     * @return bool
     */
    public function isAjax() {
		return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
	}

    /**
     * 是否Flash请求
     * @return bool
     */
    public function isFlash() {
		return isset($_SERVER['HTTP_USER_AGENT']) && $_SERVER['HTTP_USER_AGENT'] == 'Shockwave Flash';
	}

    /**
     * 则正匹配uri
     * @param $pattern
     * @param null $match
     * @param int $flag
     * @param int $offset
     * @return int
     */
    public function pureUriMatch($pattern, & $match = null, $flag = 0, $offset = 0) {
		return preg_match($pattern, $this->pure_uri, $match, $flag, $offset);
	}

    /**
     * 获取$_GET数据 [三元式带默认值]
     * @param null $keys
     * @param null $default
     * @return array
     */
    public function g($keys = null, $default = null) {
		return static::depth_get($this->get, $keys, $default);
	}

    /**
     * 获取$_POST数据 [三元式带默认值]
     * @param null $keys
     * @param null $default
     * @return array
     */
    public function p($keys = null, $default = null) {
		return static::depth_get($this->post, $keys, $default);
	}

    /**
     * 获取数据[三元式带默认值]
     * @param $data
     * @param $key
     * @param $default
     * @return array
     */
    static public function depth_get($data, $key, $default) {
        if (isset($data[$key])) {
            if (is_array($default)) {
                return array_merge($default, $data[$key]);
            } else {
                return $data[$key];
            }
        }

        return $default;
    }
}
