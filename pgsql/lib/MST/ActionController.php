<?php

if (!defined('IN_MST_CORE'))
	exit('MST_ActionController Can\'t be include single!');

if (!defined(MST_Core::LITE_MODE)) {
	MST_Core::import(array(
		'MST/ActionController/Request',
		'MST/ActionController/Router',
	), MST_Core::P_LIB);
}
/**
 * Class MST_ActionController 流程控制类
 * @property MST_ActionController_Request $params
 * @package MST
 */
abstract class MST_ActionController {

    /**
     * appcalition方法返回标识  不渲染直接结束渲染流程
     */
    const NO_RENDER			= false;
    /**
     * 流程标识 表示在流程中已经指定渲染模式
     */
    const IS_RENDER			= 'CONTROLLER_IS_RENDER';
    /**
     * 命名补充后缀 用于补全controller class全名
     */
    const PF_CONTROLLER		= 'Controller';
    /**
     * 命名补充后缀 用于补全action 方法全名
     */
    const PF_ACTION			= 'Action';
    /**
     * view渲染模式  输出文件下载
     * @todo 此模式还未实现
     */
    const FILE				= 'file';
    /**
     * view渲染模式  输出view文件
     */
    const VIEW				= 'view';
    /**
     * view渲染模式  只输出文字
     */
    const TEXT				= 'text';
    /**
     * view渲染模式  转接到另外一个action处理
     */
    const ACTION				= 'action';
    /**
     * view渲染模式  使用widget输出
     */
    const WIDGET				= 'widget';
    /**
     * view渲染模式  自定义渲染
     */
    const CUSTOM_VIEW			= 'custom_view';

	protected static
		$_request = null,
		$_instance = null,
		$_currentView = null;
	
	# @todo 此处的处理应该放到controller被实例化以后, dispatch应该有一个具体含义
    /**
     * 进入 controller 处理流程
     * @param array $config 框架配置
     * @param callable|null $beforeDispatch 框架入口预处理callback, MST_Core::start方法里面的$beforeDispatch 参数
     * <code>
     * MST_ActionController::dispatch(array $config, function(MST_ActionController_Request $request, & $controller){
     *      //code..
     * });
     * </code>
     */
    final static public function dispatch(array $config = null, $beforeDispatch = null) {
        global $data_cache;

		if (self::$_instance == null) {
			$request = new MST_ActionController_Request($config['request']);
			$router = new MST_ActionController_Router($config['routes']);
			$router->routing($request);
			$controller = $request['controller'];
			$controller = MST_String::camelize2($controller) . static::PF_CONTROLLER;
			if ($request['module'] != null) {
				$module = $request['module'];
				if (strpos($module, '/') !== false)
					$module = str_replace('/', '_', $module);
				$controller = $module . '_' . $controller;
			}
			if (is_callable($beforeDispatch)) {
				call_user_func_array($beforeDispatch, array(& $request, & $controller));
			}
            $data_cache['request'] = & $request;
			if (!class_exists($controller))
				MST_Core::error(202, $controller);
			else
				self::$_instance = new $controller();
		}
	}

    /**
     * @var bool 指定需要渲染的layout
     */
    public $layout = false;
    /**
     * @var string 制定需要输出的content type
     */
    public $format = 'html';
    /**
     * @var MST_ActionController_Request|null  全局MST_ActionController_Request对象的引用
     */
    public $params = null;
    /**
     * @var int 制定需要输出的HTTP 状态码
     */
    public $status = -1;
	public $autoLoadHelper = false;

    protected $comet = 0;
    /**
     * @var null|string 加载的view所有文件夹路劲，可以修改此项目达到区分不同view主题目录的效果
     */
    protected $viewPath = null;
    /**
     * @var string 默认的渲染view渲染模式
     */
    protected $defaultRender = self::VIEW;

    /**
     * 虚函数 作为controller一个预处理方法，在调用action方法之前必先调用此方法
     *
     * @return mixed|MST_ActionController::NO_RENDER 当返回 MST_ActionController::NO_RENDER 时不处理view渲染流程
     */
    abstract public function application();

    /**
     * 构造函数
     */
    private function __construct()
	{
        global $data_cache;
		if ($this->comet <= 0)
			ob_start(); // 开始缓冲了
		$this->params = & $data_cache['request'];
		$this->viewPath = trim(
			$this->params['module'] . '/' . $this->params['controller'], '/');
		if ($this->application() !== self::NO_RENDER || !defined(self::IS_RENDER))
			$this->action($this->params['action']);
	}

    /**
     * 析构函数
     * 渲染view流程从这里开始
     */
    public function __destruct() {
		if (!defined(self::IS_RENDER) && self::$_currentView != null) {
			switch ($this->defaultRender) {
				case self::VIEW :
				case self::TEXT :
				case self::ACTION :
				case self::WIDGET :
					#$this->defaultRender = $mode;
					break;
				default :
					$this->defaultRender = self::VIEW;
			}
			$this->render(
				$this->defaultRender,
				self::$_currentView
			);
		}
		if (self::$_instance != null)
			self::$_instance = null;
		if (self::$_request != null)
			self::$_request = null;
	}

    /**
     * 重定向到理到指定action
     *
     * 此重定向是在controller流程中实现的，不是HTTP的重定向
     * @param string $action 需要重定向到的action[短名称]
     * @return $this
     */
    protected function action($action) {
		$name = MST_String::camelize($action);
		$actName = $name . self::PF_ACTION;
		if (!method_exists($this, $actName))
			MST_Core::error(203, $actName);
		$actRef = new ReflectionMethod($this, $actName);
		if ($actRef->isPrivate() || $actRef->isProtected()
		 && !constant(MST_ActionController_Router::IS_MAP))
			MST_Core::error(203, $actName);
		if ($this->$actName() !== self::NO_RENDER && self::$_currentView == null)
			self::$_currentView = $action;
		return $this;
	}

	/**
	 * 输出，url跳转
	 */
    /**
     * 触发HTTP重定向
     *
     * 结束controller流程，不再进行view渲染流程
     *
     * @param string $url
     * @return $this|bool
     */
    protected function redirect($url) {
		if (defined(self::IS_RENDER)) return self::NO_RENDER;
		define(self::IS_RENDER, true);
		header('Location:'.linkUri($url));
		return $this;
	}

    /**
     * 调用渲染流程
     *
     * @param null|string $mode 渲染模式见 const MST_ActionController::FILE\MST_ActionController::VIEW\MST_ActionController::TEXT\MST_ActionController::ACTION\MST_ActionController::WIDGET\MST_ActionController::CUSTOM_VIEW
     * @param null $content 模式参数，一般是view层的路径
     * @param array $options 扩展参数,除路径外的另外一些参数，一般用于 MST_ActionController::CUSTOM_VIEW 模式下才需要
     * @return $this|bool
     */
    protected function render(
		$mode = null,
		$content = null,
		array $options = null)
	{
		if (defined(self::IS_RENDER)) return self::NO_RENDER;
		define(self::IS_RENDER, true);
		if ($mode == null) $mode = $this->defaultRender;
		if ($mode == self::VIEW)
			$content = $this->viewPath . '/' . $content;
		MST_ActionView::instance()
			->assign($this)
			->setOptions($options)
			->render($mode, $content);
		return $this;
	}

    /**
     * 自定义 view层调用
     *
     * @param string $file view文件路径
     * @param string $path view文件路径
     * @param array $options 扩展参数,除路径外的另外一些参数
     * @return $this|bool
     */
    protected function customRender($file, $path, array $options = null) {
		return $this->render(self::CUSTOM_VIEW, array($file, $path), $options);
	}

    /**
     * 指定当然需要渲染的view路径
     * @param $val
     */
    protected function setView($val) {
		self::$_currentView = $val;
	}

    /**
     * 指定扩展参数,除路径外的另外一些参数
     * @param string $key
     * @param mixed $val
     * @return $this
     */
    protected function setViewOption($key, $val) {
		MST_ActionView::instance()->setOption($key, $val);
		return $this;
	}

    /**
     * 获取扩展参数,除路径外的另外一些参数
     * @param $key
     * @return null
     */
    protected function getViewOption($key) {
		return MST_ActionView::instance()->getOption($key);
	}

    /**
     * 指定扩展参数[多项版本],除路径外的另外一些参数
     * @param array $options
     * @return $this
     */
    protected function setViewOptions(array $options) {
		MST_ActionView::instance()->setOptions($options);
		return $this;
	}

    /**
     * 获取扩展参数[多项版本],除路径外的另外一些参数
     * @return array
     */
    protected function getViewOptions() {
		return MST_ActionView::instance()->getOptions();
	}

    /**
     * comet模式
     * @deprecated
     * @param callable $fn
     */
    protected function doComet(Closure $fn) {
		$times = 0;
		set_time_limit(0);
		while(true) {
			ob_flush();
			flush();
			$times++;
			$result = call_user_func($fn, $times, $this);
			if ($result === false) {
				break;
			}
			usleep(10000);
			sleep($this->comet);
		}
	}
}
