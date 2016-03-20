<?php
/**
 * Class MST_Path_Info
 * pathinfo 自定义结构
 * @internal
 * @package MST
 */
class MST_Path_Info {

    public
        $dirname,
        $basename,
        $extension,
        $filename;

    public function __construct($path) {
        $info = pathinfo($path);
        if (!$info) $info = array();
        $info = array_merge(array(
            'dirname' => false,
            'basename' => false,
            'extension' => false,
            'filename' => false,
        ), $info);

        $this->dirname = $info['dirname'];
        $this->basename = $info['basename'];
        $this->extension = $info['extension'];
        $this->filename = $info['filename'];
    }
}

/**
 * Class MST_URI
 * URI处理类
 * @package MST
 */
class MST_URI {

    public
        $scheme,
        $host,
        $path,
        $port,
        $query;

    protected
        $uri,
        $query_params;

    protected static
        $_instance;

    public function __construct($url = null) {
        $uri = parse_url($url);

        if (!$uri) $uri = array();

        $uri = array_merge(array(
            'scheme' => false,
            'host' => false,
            'path' => false,
            'query' => false,
            'port' => false,
        ), $uri);

        $this->uri = $uri;

        $this->scheme = $uri['scheme'];
        $this->host = $uri['host'];
        $this->path = $uri['path'];
        $this->query = $uri['query'];
        $this->port = $uri['port'];
    }

    /**
     * 解析URI
     * @param $pure_uri 原始URI
     * @return MST_URI
     */
    public static function parse($pure_uri) {
        if (empty(self::$_instance)) {
            self::$_instance = new self($pure_uri);
        }
        return self::$_instance;
    }

    /**
     * 获取query数组
     * @return null|array
     */
    public function get_query_params() {
        if (empty($this->query_params)) {
            parse_str($this->query, $this->query_params);
        }
        $this->query_params = filter_var_array($this->query_params, FILTER_SANITIZE_STRING);
        return $this->query_params;
    }

    /**
     * 获取pathinfo结构体
     * @return MST_Path_Info
     */
    public function get_path_info() {
        return new MST_Path_Info($this->path);
    }

    /**
     * 生成url
     * @param bool $relative_path 是否使用相对路径
     * @return string
     */
    public function build_url($relative_path = true) {
        if (!$this->scheme && $relative_path) {
            return $this->path.($this->query ? '?'.$this->query : '');
        }
        if (!$this->scheme) {
            $current_uri = new self(self::get_current_url());
            $this->scheme = $this->scheme ? $this->scheme : $current_uri->scheme;
            $this->host = $this->host ? $this->host : $current_uri->host;
            $this->port = $this->port ? $this->port : $current_uri->port;
        }
        return $this->scheme . '://'.$this->host.($this->port ? ':'.$this->port : '').$this->path.($this->query ? '?'.$this->query : '');
    }

    /**
     * 获取当前请求的URL
     * @return string
     */
    public static function get_current_url() {
        return 'http' . (isset($_SERVER['HTTPS']) || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https') ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    }
}

