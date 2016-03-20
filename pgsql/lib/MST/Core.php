<?php
/**
 * MixMVC 核心文件
 *
 * 定义大部分公共功能及框架入口
 *
 *
 * @author Janpoem
 */

/**
 * 基础数据接口 [集合及单行类型数据均实现此接口]
 * @api
 * @package MST
 */
interface IMST_DataSet {
    /**
     * 如果数据是集合数据，判断数据是否为空[是否包含行数据]
     * @abstract
     * @return bool
     */
    public function isEmpty();
    /**
     * 判断数据是否单行数据
     * @abstract
     * @return bool
     */
    public function isRow();
}

/**
 * 基础错误接口[所有数据库接口类都实现此接口]
 * @api
 * @package MST
 */
interface IMST_DBO_Error {
    /**
     * 获取某key值的错误信息
     * @abstract
     * @param string $key
     * @return null|bool|string
     */
    public function getError($key);
    /**
     * 设定某key值下的错误信息
     * @abstract
     * @param string $key
     * @param null $msg
     * @return mixed
     */
    public function setError($key, $msg = null);
    /**
     * 获取所有的错误信息
     * @abstract
     * @return array eg: {error_key:error_value, ...}
     */
    public function getErrors();
    /**
     * 判断是否存在某key的错误，当不适用key参数时，返回是否包含任何错误
     * @abstract
     * @param string $key
     * @return bool
     */
    public function hasError($key = null);
    /**
     * 获取错误的数量，多用于判断是否有错误
     * @abstract
     * @return number
     */
    public function countError();
    /**
     * 清除某key下错误, 不适用key参数时清除所有的错误
     * @abstract
     * @param string|null $key
     * @return bool
     */
    public function clearError($key = null);
}

/**
 * 运行状态标识。判断时候已经进入框架的流程
 */
define('IN_MST_CORE', true);

/*
 * 框架公共缓存
 * @var array $data_cache
 * */
global $data_cache;
$data_cache = array();

/**
 * 处理框架公共功能集合，配置运行环境，目录结构访问，组织各部分功能调用，框架入口。
 *
 * @package MST
 *
 */
abstract class MST_Core {

    /**
     * 运行环境标识，开发环境，默认输出debug信息
     */
    const IN_DEV = 'development';
    /**
     * 运行环境标识，测试环境，默认屏蔽任何debug信息
     */
    const IN_TEST = 'test';
    /**
     * 运行环境标识，产品环境|正式环境，默认屏蔽任何debug信息
     */
    const IN_PRO = 'production';
    /**
     * 运行环境标识，是否已经进入项目模式
     */
    const RUN_IN_PROJECT = 'IN_MST_PROJECT';
    /**
     * 运行环境标识，是否已经进入创建器模式
     */
    const RUN_IN_GENERATOR = 'IN_MST_GENERATOR';
    /**
     * 运行环境标识，是否已经进入轻量化模式，不进行预加载
     */
    const LITE_MODE = 'MST_LITE_MODE';
    /**
     * 路径别名标识，MST_Core::getPathOf使用，代表root/library 路径
     */
    const P_LIB = 10;
    /**
     * 路径别名标识，MST_Core::getPathOf使用，代表root/config 路径
     */
    const P_CONFIG = 20;
    /**
     * 路径别名标识，MST_Core::getPathOf使用，代表root/application 路径
     */
    const P_APP = 30;
    /**
     * 路径别名标识，MST_Core::getPathOf使用，代表root/public 路径
     */
    const P_PUBLIC = 40;
    /**
     * 路径别名标识，MST_Core::getPathOf使用，代表root/log 路径
     */
    const P_LOG = 50;
    /**
     * 路径别名标识，MST_Core::getPathOf使用，代表root/cache 路径
     */
    const P_CACHE = 60;
    /**
     * 路径别名标识，MST_Core::getPathOf使用，代表root/backup 路径
     */
    const P_BACKUP = 70;
    /**
     * 路径别名标识，MST_Core::getPathOf使用，代表root/application/controllers 路径
     */
    const P_CONTROLLER = 3001;
    /**
     * 路径别名标识，MST_Core::getPathOf使用，代表root/application/models 路径
     */
    const P_MODEL = 3002;
    /**
     * 路径别名标识，MST_Core::getPathOf使用，代表root/application/views 路径
     */
    const P_VIEW = 3003;
    /**
     * 路径别名标识，MST_Core::getPathOf使用，代表root/application/helpers 路径
     */
    const P_HELPER = 3004;
    /**
     * 路径别名标识，MST_Core::getPathOf使用，代表root/application/views/__layouts 路径
     */
    const P_LAYOUT = 3005;
    /**
     * 路径别名标识，MST_Core::getPathOf使用，代表root/application/views/__widgets 路径
     */
    const P_WIDGET = 3006;
    /**
     * 路径别名标识，MST_Core::getPathOf使用，代表root/application/plugins 路径
     */
    const P_PLUGIN = 3007;
    /**
     * 路径别名标识，MST_Core::import使用，代表默认的加载文件后缀
     */
    const EXT_APP = '.php';
    /**
     * 路径别名标识，代表view文件的默认后缀
     */
    const EXT_VIEW = '.phtml';
    /**
     * 路径别名标识，代表Class的包的路径的分割符，PEAR风格
     */
    const SLASH_CLASS = '_';
    /**
     * 路径别名标识，代表默认的文件路径分割符
     */
    const SLASH_PATH = '/';
    /**
     * 系统支持的语言标识列表
     */
    const LANGUAGES = '|neutral|en|de|ja|ko|ru|zh-cn|zh-tw|';

    /**
     * @static
     * @var string $_env 项目运行环境
     */
    protected static $_env = self::IN_DEV;
    /**
     * @static
     * @var array $_options 项目默认配置，这个$_options一旦被设置，既不允许被调用，也不允许被更改
     *
     * @example example1.php Counting in action.
     */
    protected static $_options = array(
        'name' => 'MST Library ver 1.1',
        'root' => null,
        'lang' => 'zh-cn',
        'encode' => 'utf-8',
        'timezone' => 'Asia/Shanghai',
        'httpEntry' => '/',
        'inPublic' => true,
        'rewrite' => true,
        'httpIncludeHost' => false,
        'CDN' => false,
        'CDNdomain' => false,
    );
    /**
     * @static
     * @var string $_requestUri 请求的uri
     */
    protected static $_requestUri = null;
    /**
     * @static
     * @var array $_config 运行环境配置
     */
    protected static $_config = array(
        'request' => array(
            'key_case' => -1, # CASE_LOWER = 0 || CASE_UPPER = 1
        ),
        'request_valid' => array(
            'key' => '_MST_Request_',
            'format' => ':project_name|:ip|:session_id|:value'
        ),
        'routes' => null
    );
    /**
     * @static
     * @var array $_paths 系统常用路径uri
     */
    protected static $_paths = array(
        self::P_LIB => '/library',
        self::P_CONFIG => '/config',
        self::P_APP => '/application',
        self::P_PUBLIC => '/public',
        self::P_LOG => '/log',
        self::P_CACHE => '/cache',
        self::P_BACKUP => '/backup',
        self::P_CONTROLLER => '/application/controllers',
        self::P_MODEL => '/application/models',
        self::P_VIEW => '/application/views',
        self::P_PLUGIN => '/application/plugins',
        self::P_HELPER => '/application/helpers',
        self::P_LAYOUT => '/application/views/__layouts',
        self::P_WIDGET => '/application/views/__widgets',
    );
    /**
     * @static
     * @var array $_absPaths 绝对路径映射缓存
     */
    protected static $_absPaths = array();
    /**
     * @static
     * @var array $_fileLoader 已经加载的文件的缓冲
     */
    protected static $_fileLoader = array();
    /**
     * @static
     * @var array $_fileHashMap 已经加载的文件映射的缓冲
     */
    protected static $_fileHashMap = array();
    /**
     * @static
     * @var array $_requireLibs 框架运行必要加载的类库
     * @todo 需要增加一个addRequireLib的方法
     */
    protected static $_requireLibs = array(
        'MST/String',
        'MST/XML',
        'MST/DataSet',
        'MST/ActionController',
        'MST/ActionView',
        'MST/DBO',
        'MST/Mailer',
    );
    /**
     * @static
     * @var array $_coreLibNamespace 框架运行时额外的第三方namespace映射
     */
    protected static $_coreLibNamespace = array(
        'MST' => 1,
        'MM' => 1,
        'Zend' => 1,
        'PHPMailer' => 1,
    );
    protected static $_zendApp = null;
    protected static $_zendFront = null;
    protected static $_errorHandle = null;
    protected static $_warningHandle = null;


    /**
     * MST Lib 启动函数
     * @static
     * @param string $env 框架运行的环境参数，可能值:self::IN_DEV|self::IN_TEST|self::IN_PRO
     * @param array|null $options 参考 MST_Core::$_options
     *
     * @param callable|null $beforeDispatch 框架入口预处理callback
     * <code>
     *  function(MST_ActionController_Request $request, string & $controller) {}
     * </code>
     */
	static public function start(
		$env = self::IN_DEV,
		array $options = null,
		$beforeDispatch = null)
	{
		if ($env != self::IN_PRO && $env != self::IN_TEST)
			$env = self::IN_DEV;
		// 必须步骤#1
		// 设定当前运行环境
		self::$_env = $env;
		// 在Generator就无需执行本地化操作了
		if (!defined(self::RUN_IN_GENERATOR)) {
			// 必须步骤#3
			// 初始化$options
			if ($options != null)
				self::$_options = array_merge(self::$_options, $options);
			if (!defined(self::LITE_MODE))
				self::import(self::$_requireLibs, self::P_LIB);
				
			self::localization();
			spl_autoload_register(array('MST_Core', 'autoLoad'));
			self::loadConfig();
			
			MST_ActionController::dispatch(self::$_config, $beforeDispatch);
		}
	}

    /**
     * 项目本地化操作
     * 定义全局静态变量、加载框架错误定义、定制语言和编码、设置项目开发时区
     */
    final static protected function localization() {
		switch (self::$_env) {
			case self::IN_DEV :
				error_reporting(E_ALL | E_NOTICE);
				ini_set('display_errors', 1);
				ini_set('display_startup_errors', 1);
				break;
			case self::IN_TEST :
				error_reporting(E_ALL | E_NOTICE);
				ini_set('display_errors', 1);
				ini_set('display_startup_errors', 1);
				break;
			case self::IN_PRO :
				error_reporting(0);
				ini_set('display_errors', 0);
				ini_set('display_startup_errors', 0);
				break;
			default :
				static::error('运行环境设置有误！');
		}
		$currLang = '|' . self::$_options['lang'] . '|';
		if (stripos(self::LANGUAGES, $currLang) == false)
			self::$_options['lang'] = 'en';
		// 创建一系列全局静态变量
		if (self::$_options['root'] == null)
			self::$_options['root'] = realpath('..');
		define('PROJECT_ROOT', self::$_options['root']);
		define('PROJECT_NAME', self::$_options['name']);
		define('PROJECT_HASH', md5(PROJECT_NAME));
		define('PROJECT_LANG', self::$_options['lang']);
		define('PROJECT_ENCODE', self::$_options['encode']);
		define('PROJECT_TIMEZONE', self::$_options['timezone']);
		// 新添加
		define('HTTP_ENTRY', rtrim(self::$_options['httpEntry'], '/\/'));
		define('HTTP_IN_PUBLIC', self::$_options['inPublic']);
		define('HTTP_REWRITE', self::$_options['rewrite']);
		define('HTTP_INCLUDE_HOST', self::$_options['httpIncludeHost']);
		define('CDN_ENABLED', self::$_options['CDN']);
		define('CDN_DOMAIN', self::$_options['CDNdomain']);
        // 定制语言
        // 以下内容修正为使用ini_set设置
        // 语言
        ini_set('mbstring.language', PROJECT_LANG);
        // 编码
        if (version_compare(PHP_VERSION, '5.6.0') >= 0) {

        } else {
            ini_set('mbstring.internal_encoding', PROJECT_ENCODE);
            ini_set('mbstring.http_input', PROJECT_ENCODE);
            ini_set('mbstring.http_output', PROJECT_ENCODE);
        }

        // 设置时区
        ini_set('date.timezone', PROJECT_TIMEZONE);
		// 加载框架默认的错误定义
		self::import('MST/error/' . PROJECT_LANG . '/errors', MST_Core::P_LIB);

		// 如果没有设置$_requesrUri，则按照默认的REQUEST_URI
		if (self::$_requestUri == null) {
			static::setRequestUri(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : (isset($_SERVER['argv']) ? $_SERVER['PHP_SELF'].'?'.$_SERVER['argv'][0] : $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING']));
		}
	}

    /**
     * 加载项目P_CONFIG中的文件
     * 单独将loadConfig的加载作为一个独立的方法，用来作为config内存中读取的接口
     * @static
     * @param null $cacheHandle
     * @todo cacheHaldle的处理则以后添加
     */
	final static protected function loadConfig($cacheHandle = null) {
		if ($cacheHandle == null) {
			$config = self::import('config', self::P_CONFIG);
			if (empty($config))
				self::error('Can\'t load web application config!');
			if (!empty($config['common']))
				self::$_config = array_merge(self::$_config, $config['common']);
			if (!empty($config[self::$_env]) && is_array($config[self::$_env]))
				self::$_config = array_merge(self::$_config, $config[self::$_env]);
			self::$_config['routes'] = self::import('routes', self::P_CONFIG);
		}
	}

	final static public function getConfig($prefix = null, $key = null) {
		if ($prefix == null && $key == null)
			return self::$_config;
		if (isset(self::$_config[$prefix])) {
			if ($key == null) return self::$_config[$prefix];
			if (isset(self::$_config[$prefix][$key]))
				return self::$_config[$prefix][$key];
		}
		return null;
	}

	/**
	 * 获取当前框架运行环境
	 * @return string  可能值 self::IN_DEV|self::IN_TEST|self::IN_PRO
	 */
	final static public function getEnv() {
		return self::$_env;
	}

	/**
	 * 获取当前环境是否在Development环境下
	 * @return boolean
	 */
	final static public function inDev() {
		return self::$_env === self::IN_DEV;
	}

	/**
	 * 获取当前环境是否在Test环境下
	 * @return boolean
	 */
	final static public function inTest() {
		return self::$_env === self::IN_TEST;
	}

	/**
	 * 获取当前环境是否在Production环境下
	 * @return boolean
	 */
	final static public function inPro() {
		return self::$_env === self::IN_PRO;
	}

    /**
     * 获取框架运行时配置值
     * @static
     * @param $key 配置名称
     * @return null|mixed
     */
	static public function getOption($key) {
		return isset(self::$_options[$key]) ? self::$_options[$key] : null;
	}

	/**
	 * 设置当前访问的URI
	 * 这个是很重要的接口，用于兼容不同的服务器环境的。如在IIS 6环境内，使用IIRF的
	 * 话，必须在执行MST_Core::start之前调用该函数：
	 * <code>
	 * MST_Core::setRequestUri($_SERVER['HTTP_X_REWRITE_URL']);
	 * MST_Core::start(MST_Core::IN_DEV);
	 * </code>
	 * 假如没有事先指定URI，框架将按照默认的方式去获取当前请求的URI。
	 * @param string 当前URI
	 */
	final static public function setRequestUri($val = null) {
		self::$_requestUri = $val == null ? '/' : $val;
	}

    /**
     * 获取当前URI的统一调用接口
     * 这个接口只是为基础类中调用使用，常规的调用还是应该使用Controller环境中的
     * $this->params->uri
     * @static
     * @return null|string
     */
	final static public function getRequestUri() {
		return self::$_requestUri;
	}

    /**
     * 获取框架运行时的request对象
     * @static
     * @param null $key
     * @return null
     */
	final static public function getRequest($key = null) {
        global $data_cache;
		if (!isset($data_cache['request'])) return null;
		if ($key == null)
			return $data_cache['request'];
		else
			return isset($data_cache['request'][$key]) ? $data_cache['request'][$key] : null;
	}

    /**
     * 配置框架运行时，需要预加载的第三方类库的namespace
     * @static
     * @param $name
     */
	final static public function registerLibNamespace($name) {
		self::$_coreLibNamespace[$name] = 1;
	}

    /**
     * 实现PHP Auto Load 特性的函数，用于根据FQL加载Class文件
     *
     * @static
     * @param string $class
     * @return bool|mixed|string
     *
     * @throw 当找不到加载的Class文件是会触发一个中断
     */
	final static public function autoLoad($class) {
		$namespace = strtok($class, self::SLASH_CLASS);
		$fullPath = $class;
		// 如果在全局的CoreLibNamespace的空间
		if (isset(self::$_coreLibNamespace[$namespace])) {
			$fullPath = str_replace(self::SLASH_CLASS, self::SLASH_PATH, $fullPath);
			return self::import($fullPath, self::P_LIB);
		}
		// 不然则当做是load Model
		// 暂时不考虑加载非Model的部分
		else {
			if (strpos($fullPath, self::SLASH_CLASS)) {
				$fullPath = str_replace(self::SLASH_CLASS, self::SLASH_PATH, $fullPath);
				$fullPath = strtolower(dirname($fullPath)) . '/' . basename($fullPath);
			}
			switch (true) {
				case strripos($fullPath, 'controller') !== false :
					$errorCode = 201;
					$alias = static::P_CONTROLLER;
					break;
				case strripos($fullPath, 'application') !== false :
					$errorCode = 200;
					$alias = static::P_CONTROLLER;
					break;
				case strripos($fullPath, 'plugin') != false :
					$errorCode = 211;
					$alias = static::P_PLUGIN;
					break;
				default:
					$errorCode = 105;
					$alias = static::P_MODEL;
			}
			if (!self::import($fullPath, $alias)) {
				if (!static::inPro())
					$path = MST_Core::getPathOf($fullPath, $alias, '.php', true);
				else
					$path = $fullPath;
				MST_Core::error($errorCode, $path);
			}
			if (is_subclass_of($class, 'MST_DBO'))
				call_user_func(array($class, '__init'));
		}
	}

    /**
     * 添加自定义loader，用于在流程内增加额外的auto loader
     * @param callable $func
     */
    static public function add_auto_loader(Closure $func) {
        spl_autoload_unregister(array(self, 'autoLoad'));
        call_user_func($func);
        spl_autoload_register(array(self, 'autoLoad'));
    }

    /**
     * 配置 框架文件路径别名映射
     * @static
     * @param number $alias
     * @param string $path
     * @param bool $isAbs
     */
	final static public function setPath($alias, $path, $isAbs = false) {
		self::$_paths[$alias] = $path;
		if ($isAbs || isset(self::$_absPaths[$alias]))
			self::$_absPaths[$alias] = $isAbs;
	}

    /**
     * 通过框架的路径别名映射，获取真实的物理路径
     * @static
     * @param string $path 待转换的路径
     * @param number $alias 系统目录的物理影射见 可能值为本类P_* 系列常量
     * @param string $ext 路径的后缀，如果是dir时不需要提供，可能值为本类EXT_* 系列常量
     * @param bool $isConvertSystemSlash 是否转换为系统本地的路径分割符
     * 这个框架默认使用linux风格的路径分割符
     * @return string
     */
	final static public function getPathOf(
		$path, 
		$alias = null, 
		$ext = null, 
		$isConvertSystemSlash = false)
	{
		// 去掉多余的/
		if ($path != null) $path = trim($path, '/');
		if ($path != null) {
			// 当$path不为空，则给其添加一个默认的左/
			$path = '/' . $path;
			if ($ext != null && $ext{0} != '.') $ext = '.' . $ext;
			if ($ext != null && strrchr($path, '.') !== $ext)
				$path .= $ext;
		}
		if ($alias != null && isset(self::$_paths[$alias])) {
			$path = self::$_paths[$alias] . $path;
			if (!isset(self::$_absPaths[$alias]))
				$path = self::$_options['root'] . $path;
		}
		else
			$path = self::$_options['root'] . $path;
		if ($isConvertSystemSlash && DIRECTORY_SEPARATOR == "\\")
			$path = str_replace('/', DIRECTORY_SEPARATOR, $path);
		return $path;
	}

    /**
     * 载入类库
     *
     * @static
     * @param array|string $file
     * @param number $alias
     * @param string $ext
     * @param array|null $localVars
     * @return bool|string
     */
	final static public function import(
		$file,
		$alias = null,
		$ext = self::EXT_APP,
		array $localVars = null)
	{
		if (is_array($file)) {
			foreach ($file as &$val) {
				if (is_array($val)) {
					list($item, $alias) = $val;
					if (!empty($val[2])) $ext = $val[2];
				}
				else
					$item = (string)$val;
				$val = self::import($item, $alias, $ext);
			}
			return $file;
		}
		// 只处理$file为string类型的import
		elseif (is_string($file) && $file != null) {
			$file = self::getPathOf($file, $alias, $ext);
			$hash = md5($file);
			if (!isset(self::$_fileLoader[$hash])) {
				if (is_file($file)) {
					if ($localVars != null) {
						extract($localVars, EXTR_PREFIX_SAME, 'local');
					}
					self::$_fileHashMap[$hash] = $file;
					self::$_fileLoader[$hash]  = include_once $file;
				}
				else
					return false;
			}
			return self::$_fileLoader[$hash];
		}
		return false;
	}

    /**
     * 获取框架的出错信息
     *
     * @static
     * @param array|number|string $args
     *
     * @return array
     */
	final static public function getErrorMsg($args) {
		global $MST_ERR;
		$errorType = 2; # 默认错误是警告
		$errorText = $args[0];
		if (is_numeric($errorText)) {
			$errorType = (int)($errorText / 100);
			$key = (int)($errorText % 100);
			if (!empty($MST_ERR[$errorType]) && !empty($MST_ERR[$errorType][$key])) {
				$args[0] = $MST_ERR[$errorType][$key];
				$errorText = call_user_func_array('sprintf', $args);
			}
		}
		elseif (is_string($errorText)) {
			if (!empty($args[1])) $errorText = call_user_func_array('sprintf', $args);
		}
		if (empty($errorText)) $errorText = $MST_ERR['unknow'];
		if (self::$_env !== self::IN_DEV) {
			$errorText = str_ireplace(PROJECT_ROOT, '/' . PROJECT_NAME, $errorText);
		}
		return array($errorType, $errorText);
	}

    /**
     * 配置框架错误处理的callback
     * @static
     * @param callable $method
     */
	final static public function setErrorHandle($method) {
		if (is_callable($method)) {
			self::$_errorHandle = $method;
		}
	}

    /**
     * 输出错误，当需要输出出错信息并中断流程的时候调用。
     * 当没有设定setErrorHandle的时候，会调用框架末默认的报错信息页面并中断流程
     */
    final static public function error() {
		$args = func_get_args();
		$error = self::getErrorMsg($args);
		if (self::$_errorHandle != null) {
			call_user_func_array(self::$_errorHandle, $error);
		}
		else {
			$debug = false;
            if (static::inDev()) {
                $debug = debug_backtrace();
            }
			$status = $error[0] == 2 ? 404 : 500;
			header("{$_SERVER['SERVER_PROTOCOL']} {$status}");
			if (class_exists('MST_ActionView')) {
				MST_ActionView::instance()->assign(array(
						'error' => $error[1],
						'debug' => $debug,
						'status' => $status
					))->import('MST/error/' . PROJECT_LANG . '/render.phtml', self::P_LIB);
			}
			else {
				// 临时
				echo <<<ERR_TXT
<h2>{$status}错误</h2>
内部错误定义：{$args[0]}<br />
错误提示：{$error[1]}
ERR_TXT;
			}
			exit();
		}
	}

    /**
     * 配置框架警告处理的callback
     * @param $method
     */
    final static public function setWarningHandle($method) {
		if (is_callable($method)) {
			self::$_warningHandle = $method;
		}
	}

    /**
     * 输出警告，当需要输出警告信息的时候调用。
     * 当没有设定setWarningHandle的时候，会调用框架末默认的警告信息页面
     *
     * @return bool
     */
    final static public function warning() {
		$args = func_get_args();
		$error = self::getErrorMsg($args);
        if (self::$_warningHandle != null) {
            return call_user_func_array(self::$_warningHandle, $error);
        }
		echo '<div style="font-size:12px;color:red;font-family:serif;padding:10px;">'.$error[1].'</div>';
		return false;
	}

    /**
     * 写日志，默认会保存到MST_Core::P_LOG对应的影射目录下，一般是root/log
     * 日志内容的格式为：
     * [Y-m-d H:i:s] [日志内容]
     * @param mixed|string|array|object|res|bool $content 可以传入任何数据，当发现是array|object数据时会序列化成字符串格式
     * @param string $logFile 定义日志保存的文件名，默认是 default.log
     */
    final static public function log($content, $logFile = 'default.log') {
		global $data_cache;
        $file = static::getPathOf($logFile, static::P_LOG);
		$dir = dirname($file);
		if (!is_dir($dir)) mkdir($dir, 7777, 1);
		if ($content != null) {
			$dt = Date('Y-m-d H:i:s', time());
			if (is_array($content) || is_object($content))
				$content = var_export($content, 1);
			$content = $data_cache['request']->ip . ' [' . $dt . ']' . ' ' . $content . "\r\n";
			file_put_contents($file, $content, FILE_APPEND);
		}
	}

    /**
     * 根据预定义的项目参数生成表单的随机验证hash，防止远程恶意提交
     * 可用于生成表单验证的隐藏域
     * 接受表单提交的时候可以在controller中使用 MST_ActionController_Request::isPost方法验证表单的提交来源的合法性
     * <code>
     * if ($this->params->isPost('prefix')) {
     *      //code...
     * }
     * </code>
     * @param $value 表单数据的前缀
     * @return array {key:value}
     */
    final static public function generateValidCode($value) {
		# :project_name|:ip|:session_id|:value
		$code = str_ireplace(array(
			':project_name', ':ip', ':session_id', ':value'
		), array(
			PROJECT_NAME, '', session_id(), $value
		), self::$_config['requestValid']['format']);
		return array(self::$_config['requestValid']['key'], md5($code));
	}

    /**
     * 备份文件，备份的文件会存放到MST_Core::P_BACKUP影射对应的文件路径下，默认为 root/backup
     * @param $file 需要备份的文件完整物理路径
     * @param string|null $prefix 保存时需要存在的文件路径前缀，用于新建文件夹存放
     * @param bool $isDelete 是否剪切源文件
     * @return bool 操作状态
     */
    final static public function backup($file, $prefix = null, $isDelete = false) {
		if (!is_file($file))
			return false;
		$info = pathinfo($file);
		$dt = date('YmdHis');
		$backFileName = "{$info['filename']}.{$dt}.{$info['extension']}";
		if ($prefix != null)
			$backFileName = $prefix . '/' . $backFileName;
		$backFile = MST_Core::getPathOf($backFileName, MST_Core::P_BACKUP);
		$dir = dirname($backFile);
		if (!is_dir($dir)) mkdir($dir, 0777, 1);
		if ($isDelete) {
			$result = rename($file, $backFile);
			MST_Core::log('转移备份 - ' . $file . ($result ? '成功' : '失败'));
		}
		else {
			$result = copy($file, $backFile);
			MST_Core::log('复制备份 - ' . $file . ($result ? '成功' : '失败'));
		}
		return $result;
	}

    /**
     * 配置全局httpUri 必须携带的query参数
     * @param array $qurey
     */
    static public function set_global_query(array $qurey) {
        global $data_cache;
        $data_cache['global_query'] = $qurey;
    }
}

/**
 * 构建访问URL
 * @param string $path 路径URL
 * @param null|string $include_host 是否包含完整域名路径，如不传根据框架默认的配置选择是否添加完整域名路径，如传入则覆盖站点默认的域名配置，生成完成的包含域名的url
 * @param array|bool $query url携带的query参数，为false|null时，根据 $path的分析结果携带query参数，当传入array时覆盖$path参数携带的query
 * @param bool $ignore_query 是否忽略所有的query参数
 * @return array|string 返回生成的URL路径
 */
function httpUri($path = '/', $include_host = null, $query = false, $ignore_query = false) {
	if (is_string($path)) {

        $uri = new MST_URI($path);
        $http_entry = '';
        if (strlen(HTTP_ENTRY) > 0 && strpos($uri->path, HTTP_ENTRY) !== 0)
            $http_entry = HTTP_ENTRY;
        if (!$uri->scheme)
            $uri->path = $http_entry. (HTTP_IN_PUBLIC ? '/' : '/public/') . ltrim($uri->path, '/\/');

        $relative_path = !$include_host && !HTTP_INCLUDE_HOST;

        if ($include_host) {
            $uri->host = $include_host;
        }

        global $data_cache;
        if (isset($data_cache['global_query']) &&
            !empty($data_cache['global_query']) &&
            is_array($data_cache['global_query'])) {

            $ori_query_params = $uri->get_query_params();
            $_query = array_merge($ori_query_params, $data_cache['global_query']);
            $uri->query = http_build_query($_query);
        }

        if ($query && is_array($query)) {
            if ($uri->query) {
                $ori_query_params = $uri->get_query_params();
                $query = array_merge($ori_query_params, $query);
            }
            $uri->query = http_build_query($query);
        }

        if ($ignore_query) {
            $uri->query = null;
        }

        if (CDN_ENABLED && CDN_DOMAIN) {
            $info = $uri->get_path_info();

            global $cdn_exts;
            if (!$cdn_exts) {
                $cdn_exts = MST_Core::getConfig('cdn', 'exts');
                if (!$cdn_exts) {
                    $cdn_exts = array();
                }
            }

            if (in_array($info->extension, $cdn_exts) && !$include_host) {
                $uri->host = CDN_DOMAIN;
            }
        }

        return $uri->build_url($relative_path);
    }
	else if (is_array($path))
		return array_map('httpUri', $path);
}

/**
 * 生成资源的URI
 * @deprecated
 * @param string $path
 * @return array|string
 */
function linkUri($path = '/') {
    $info = parse_url($path);
    if (!empty($info['host'])) {
        return $path;
    }
	if (is_string($path))
		return HTTP_ENTRY . (!HTTP_REWRITE ? '/index.php/' : '/') . ltrim($path, '/\/');
	else if (is_array($path))
		return array_map('linkUri', $path);
}

