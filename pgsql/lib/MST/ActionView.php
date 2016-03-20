<?php
/**
 * Class MST_ActionView 显示层处理类
 *
 * @property MST_ActionController_Request $params
 * @method mixed helperClass#Method(mixed $args) 调用辅助类方法
 * @package MST
 */
class MST_ActionView {

    /**
     * view文件后缀
     */
    const EXT = '.phtml';
    /**
     * HTTP content type别名，指定是HTML格式输出header
     */
    const HTML = 'html';
    /**
     * HTTP content type别名，指定是XML格式输出header
     */
    const XML = 'xml';
    /**
     * HTTP content type别名，指定是JS格式输出header
     */
    const JS = 'js';
    /**
     * HTTP content type别名，指定是TEXT格式输出header
     */
    const TEXT = 'txt';
    /**
     * HTTP content type别名，指定是流格式[下载]输出header
     */
    const STREAM = 'stream';
    /**
     * 流程标识，标识是否已经进入view渲染流程
     */
    const IS_RENDER = 'VIEW_IS_RENDER';
    /**
     * 流程标识，标识是否进入了layout渲染
     */
    const IS_CONTENT = 'VIEW_IS_CONTENT';

    /**
     * @var MST_ActionView 单例模式的实例对象
     */
    private static $_instance = null;
    /**
     * @var bool|string 类别名，对view class的引用
     */
    private static $_entity = false;

    /**
     * @var array HTTP content type映射
     */
    protected static $_mimes = array(
			'html'		=> 'text/html',
			'htm'		=> 'text/html',
			'php'		=> 'text/html',
			'phtml'		=> 'text/html',
			'xhtml'		=> 'text/html',
			'xml'		=> 'application/xml',
			'rss'		=> 'application/xml',
			'js'		=> 'application/javascript',
			'json'		=> 'application/javascript',
			'txt'		=> 'text/plain',
			'stream'	=> 'application/octet-stream',
		);
    /**
     * @var array 已经注册的view辅助类映射
     */
    protected static $_registerHelpers = array();

    /**
     * @var array 渲染器模式
     */
    protected $_render = array(
			'mode' => null,
			'content' => null,
		);
    /**
     * @var array view渲染参数
     */
    protected $_options = array(
			'debug' => true,
			'locals' => null,
			'locals_prefix' => 'local',
			'clear' => false,
		);
    /**
     * @var array 输出缓存
     */
    protected $_outBuffer = array();

    /**
     * @var bool|string layout路径
     */
    public $layout = false;
    /**
     * @var string 输出content type映射 可能值 MST_ActionView::HTML|MST_ActionView::XML|MST_ActionView::JS|MST_ActionView::TEXT|MST_ActionView::STREAM
     */
    public $format = 'html';
    /**
     * @var MST_ActionController_Request|null 全局MST_ActionController_Request对象引用
     */
    public $params = null;
    /**
     * @var int HTTP输出状态码
     */
    public $status = -1;
	public $autoLoadHelper = false;

    /**
     * 指定渲染view的类名
     * @param string $className
     */
    static public function setEntity($className) {
		self::$_entity = $className;
	}

    /**
     * 单例模式，获取实例
     * @return MST_ActionView
     */
    final static public function instance() {
		if (empty($GLOBALS['VIEW_INSTANCE'])) {
			if (self::$_entity === false || !class_exists(self::$_entity))
				$GLOBALS['VIEW_INSTANCE'] = new self();
			else
				$GLOBALS['VIEW_INSTANCE'] = new self::$_entity();
		}
		return $GLOBALS['VIEW_INSTANCE'];
	}

	public function __construct() {

	}

	public function __destruct() {
//		if (self::$_instance != null)
//			self::$_instance == null;
	}

    /**
     * @param $method
     * @param $args
     * @return mixed
     * @deprecated
     */
    public function __call($method, $args) {
		if (strpos($method, '#')) {
			$flag = strtolower($method);
			if (!isset(self::$_registerHelpers[$flag])) {
				list($class, $method) = explode('#', $method);
				$class .= 'Helper';
				self::$_registerHelpers[$flag] = array($class, $method);
			}
			return call_user_func_array(self::$_registerHelpers[$flag], $args);
		}
	}

    /**
     * 填充内部变量
     * @param string $key
     * @param null|mixed $val
     * @return $this
     */
    public function assign($key, $val = null) {
		if (is_string($key)) {
			$this->$key = $val;
		}
		else {
			if (is_object($key))
				$key = get_object_vars($key);
			if (is_array($key))
				foreach ($key as $_k => $_v) {
					$this->$_k = $_v;
				}
		}
		return $this;
	}

    /**
     * 嵌套分级输出缓存
     */
    public function catchOutBuffer() {
		$level = ob_get_level();
		while ($level > 0) {
			$length = ob_get_length();
			if ($length > 0) {
				if ($this->_options['debug'])
					$this->_outBuffer[] = ob_get_contents();
				ob_end_clean();
			}
			$level--;
		}
	}

    /**
     * 配置渲染参数
     * @param string $key
     * @param mixed $val
     * @return $this
     */
    public function setOption($key, $val) {
		$this->_options[$key] = $val;
		return $this;
	}

    /**
     * 获取渲染参数
     * @param string $key
     * @return null|mixed
     */
    public function getOption($key) {
		return isset($this->_options[$key]) ? $this->_options[$key] : null;
	}

    /**
     * 配置渲染参数[多项版]
     * @param $options
     * @return $this
     */
    public function setOptions($options) {
		if ($options != null && is_array($options))
			foreach ($options as $key => $val)
				$this->_options[$key] = $val;
		return $this;
	}

    /**
     * 获取渲染参数[多项版]
     * @return array
     */
    public function getOptions() {
		return $this->_options;
	}

    /**
     * 进入view渲染流程
     * @param string $mode 渲染模式 可能值 MST_ActionController::FILE|MST_ActionController::VIEW|MST_ActionController::TEXT|MST_ActionController::WIDGET|MST_ActionController::CUSTOM_VIEW
     * @param string|array $content 渲染参数
     * @return bool
     */
    public function render($mode, $content) {
		if (defined(self::IS_RENDER)) return false;
		define(self::IS_RENDER, true);
		if (!isset($this->_options['layout'])) {
			switch ($mode) {
				case MST_ActionController::TEXT:
				case MST_ActionController::FILE:
					$this->layout = false;
			}
		}
		else
			$this->layout = $this->_options['layout'];
		$this->catchOutBuffer();
		if (!empty($this->_options['status']) && is_numeric($this->_options['status']) && $this->_options['status'] != $this->status)
			$this->status = $this->_options['status'];
		if (is_numeric($this->status) && $this->status > 0)
			header("{$_SERVER['SERVER_PROTOCOL']} {$this->status}");
		if (!empty($this->_options['format']))
			$this->format = $this->_options['format'];
		if (isset(self::$_mimes[$this->format])) {
			if ($this->format != self::STREAM)
				$contentType = 'Content-Type: '
					. self::$_mimes[$this->format] . '; charset='
					. PROJECT_ENCODE;
			else
				$contentType = 'Content-Type: '
					. self::$_mimes[self::STREAM];
		}
		else {
			$contentType = 'Content-Type: ' . $this->format;
		}
		$this->_render['mode'] = $mode;
		$this->_render['content'] = $content;
		header($contentType);
		if ($this->layout) {
            if (isset($this->params['theme']) && !empty($this->params['theme'])) {
                $base_path = dirname(dirname($this->_render['content']));
                $this->layout = $base_path.'/'.$this->layout;
                $this->import($this->layout, MST_Core::P_VIEW, self::EXT);
                return;
            }
			$this->import((string)$this->layout, MST_Core::P_LAYOUT, self::EXT);
		}
		else {
			$this->content();
		}
	}

    /**
     * layout特有方法，渲染layout后调用，用于渲染view
     * @return bool
     */
    public function content() {
		if ($this->_options['clear'] || defined(self::IS_CONTENT)) return false;
		define(self::IS_CONTENT, true);
		switch ($this->_render['mode']) {
			case MST_ActionController::TEXT :
				echo $this->_render['content'];
				break;
			case MST_ActionController::WIDGET :
				$this->widget($this->_render['content'], $this->_options['locals']);
				break;
			case MST_ActionController::CUSTOM_VIEW :
				$this->import($this->_render['content'][0], $this->_render['content'][1], self::EXT);
				break;
			default :
				$this->import($this->_render['content'], MST_Core::P_VIEW, self::EXT);
		}
		if (!MST_Core::inPro() && $this->_options['debug'] && $this->_outBuffer != null)
			echo '<div><pre>', implode('<br />', $this->_outBuffer), '</pre></div>';
	}

    /**
     * 嵌入view widget组件
     * @param $path 组件路径
     * @param null $localVars 组件闭包中的内部参数
     * @param string $ext 组件文件的后缀 默认为 phtml
     * @return $this
     */
    public function widget($path, $localVars = null, $ext = self::EXT) {
		$this->import($path, MST_Core::P_WIDGET, $ext, $localVars);
		return $this;
	}

    /**
     * 引入外部文件
     * @param $path 文件路径
     * @param int $alias 框架物理目录映射
     * @param string $ext 文件后缀
     * @param null $localVars include文件闭的内部变量
     * @throws 当发现文件不存在是输出警告
     */
    public function import(
		$path,
		$alias = MST_Core::P_VIEW,
		$ext = self::EXT,
		$localVars = null)
	{
		$path = MST_Core::getPathOf($path, $alias, $ext);
		if (is_file($path)) {
			if ($localVars != null && is_array($localVars))
				extract($localVars, EXTR_PREFIX_SAME, $this->_options['locals_prefix']);
			include $path;
		}
		else {
			MST_Core::warning(410, $path);
		}
	}

    /**
     * 输出表单远程验证隐藏域
     * 根据预定义的项目参数生成表单的随机验证hash，防止远程恶意提交
     * 接受表单提交的时候可以在controller中使用 MST_ActionController_Request::isPost方法验证表单的提交来源的合法性
     * <code>
     * if ($this->params->isPost('prefix')) {
     *      //code...
     * }
     * </code>
     * @param $prefix 表单数据的前缀
     * @param string $type 输出类型
     * 'input'：输出隐藏域
     * ‘jsobject’|‘jshash’：输出JSON字符串
     * @param array $params 额外的其他参数对
     * @param bool $isStr 是否返回字符串或直接输出
     * @return string|void
     */
    public function validCode($prefix, $type = 'input', array $params = null, $isStr = false) {
		$code = MST_Core::generateValidCode($prefix);
		if ($params == null) $params = array();
		$params[$code[0]] = $code[1];
		$str = '';
		switch (strtolower($type)) {
			case 'input' :
				$base = '<input type="hidden" name="%s" value="%s" />';
				foreach ($params as $key => $val) {
					$str .= sprintf($base, $code[0], $code[1]);
				}
				break;
			case 'jsobject' :
			case 'jshash' :
				$base = '\'%s\':\'%s\'';
				$strAry = array();
				foreach ($params as $key => $val) {
					$strAry[] = sprintf($base, $key, $val);
				}
				$str = '{' . implode(', ', $strAry) . '}';
				break;
		}
		if ($isStr)
			return $str;
		else
			printf($str);
	}

    /**
     * 输出页面css引用节点
     * @param $file css文件路劲[相对于public目录]
     * @return $this
     * @deprecated
     */
    public function stylesheet($file) {
		if (is_string($file))
			echo '<link type="text/css" rel="stylesheet" href="', HTTP_ENTRY, (HTTP_IN_PUBLIC ? '/css/' : '/public/css/'), trim($file, '/\/'), '" />', "\n";
		else if (is_array($file))
			array_walk($file, array($this, 'stylesheet'));
		return $this;
	}

    /**
     *  输出页面js引用节点
     * @param $file js文件路劲[相对于public目录]
     * @return $this
     * @deprecated
     */
    public function script($file) {
		if (is_string($file))
			echo '<script type="text/javascript" src="', HTTP_ENTRY, (HTTP_IN_PUBLIC ? '/js/' : '/public/js/'), trim($file, '/\/'), '"></script>', "\n";
		else if (is_array($file))
			array_walk($file, array($this, 'script'));
		return $this;
	}

    /**
     * 输出URL
     * @param $controller
     * @param $action
     * @param array $get
     * @param array $uriParams
     * @deprecated
     */
    public function urlfor($controller, $action, array $get = null, array $uriParams = null) {
		
	}
}
