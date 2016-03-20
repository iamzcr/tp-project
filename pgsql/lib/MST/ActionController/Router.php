<?php
/**
 * URI路由匹配器
 * @package MST_ActionController
 */
class MST_ActionController_Router {
	
	const
		IS_MAP			= 'IS_MAP_ROUTER',
		NS_MAP			= 'map',
		NS_BASE			= 'base',
		NS_PREFIX		= 'prefix',
		NS_MODULE		= 'module',
		NS_ENTITY		= 'entity';
	
	protected
		$_routes = array(
			self::NS_BASE		=> array(
				array(
					':controller/:action',
					array(':controller' => '([\w\-\.]+)', ':action' => '([\w\-\.]+)'),
				),
			),
			self::NS_ENTITY		=> '/index.php',
			self::NS_MAP		=> null,
			self::NS_PREFIX		=> null,
			self::NS_MODULE		=> null,
		),
		$_default = array(
			'module'			=> null,
			'controller'		=> 'index',
			'action'			=> 'index',
			'target'			=> null,
			'format'			=> 'html',
			'uriParams'			=> array(),
		);

	public function __construct($routes = null) {
		if ($routes != null && is_array($routes))
			$this->_routes = array_merge($this->_routes, $routes);
	}

    /**
     * 匹配路由 生成及填充 request对象
     * @param MST_ActionController_Request $request
     */
    public function routing(MST_ActionController_Request $request) {
		$uri = $this->getPureUri($request);
		if ($uri == null || $uri == '/')
			return $this->assignRequest($request);
		$path = null;
		$maps = $this->_routes[self::NS_MAP] == null ? null : $this->_routes[self::NS_MAP];
		$modules = $this->_routes[self::NS_MODULE] == null ? null : $this->_routes[self::NS_MODULE];
		$info = pathinfo($uri);
		if (!empty($info['extension'])) {
			$uri = str_ireplace('.'.$info['extension'], null, $uri);
			$this->_default['format'] = $info['extension'];
		}
		if ($modules != null) {
			$ns = trim($uri, '/'); # 去除uri里面的第一个的/
			while ($ns) {
				if (isset($modules[$ns])) {
					$uri = str_replace('/' . $ns, '', $uri);
					$this->_default['module'] = $ns;
					// 命名的Module
					if (empty($uri) || $uri == '/') {
						return $this->assignRequest($request);
					}
					if (!empty($modules[$ns]['map'])
					 && is_array($modules[$ns]['map']))
						$maps = $modules[$ns]['map'];
					else
						$maps = array();
					break;
				}
				$path = null;
				$pos = strrpos($ns, '/');
				$ns = substr($ns, 0, $pos);
			}
		}
		$maps = $maps != null
			? array_merge($maps, $this->_routes[self::NS_BASE])
			: $this->_routes[self::NS_BASE];
		foreach ($maps as $mKey => $mVal) {
			# 假如$uri不包含/，且maps的key已经进入到$routes['base']的循环
			# 提前中断，并断言
			if (!is_string($mKey) && !strrpos($uri, '/')) {
				$this->_default['controller'] = trim($uri, '/');
				return $this->assignRequest($request);
			}
			if (empty($mVal[2])) $mVal[2] = null; 
			list($rule, $tok, $val) = $mVal;
			/*
			if ($val && is_array($val))
				$this->_default = array_merge($this->_default, $val);
			*/
			$this->mkRule($rule, $tok);
			$match = null;
			if (preg_match($rule, $uri, $match)) {
				$idx = 1;
				if ($val && is_array($val))
					$this->_default = array_merge($this->_default, $val);
				if ($tok && is_array($tok)) {
					foreach ($tok as $tKey => $tVal) {
						$tKey = substr($tKey, 1);
						# TODO:p2_1 暂时强制通过url获取回来的变量都为小写
						$this->_default[$tKey] = strtolower($match[$idx]);
						++$idx;
					}
				}
				//if ($path)
				//	$this->_default['controller'] = $this->_default['controller'];
				if ($match[$idx])
					$this->_default['target'] = addslashes(trim($match[$idx], '/'));
					# TODO:p2_2 others变量为了安全起见，强行进行转编码
				if (!empty($this->_default['target'])) {
					$others = explode('/', $this->_default['target']);
					foreach ($others as $oIdx => $oVal) {
						if ($oIdx % 2 > 0) continue;
						$this->_default[$oVal] = isset($others[$oIdx + 1]) ? $others[$oIdx + 1] : true;
						if ($this->_default[$oVal] !== true)
							$this->_default['uriParams'][$oVal] = $this->_default[$oVal];
					}
				}
				if (is_string($mKey)) define(self::IS_MAP, true);
				return $this->assignRequest($request);
			}
		}
		return $this->assignRequest($request);
	}

    /**
     * 填充request对象
     * @param MST_ActionController_Request $request
     */
    public function assignRequest(MST_ActionController_Request $request) {
		defined(self::IS_MAP) || define(self::IS_MAP, false);
		foreach ($this->_default as $key => $val) {
			if ($val != null 
			 && $key == 'module' || $key == 'controller' || $key == 'action') {
				$val = str_ireplace(array('.', '-'), '_', $val);
			}
			if (!isset($request[$key]) && !isset($request->post[$key]))
				$request[$key] = $val;
		}
	}
	
    /**
     * 获取原始请求uri
     * @param MST_ActionController_Request $request
     * @return mixed|string
     */
    public function getPureUri(MST_ActionController_Request $request) {
		$entity = $this->_routes[self::NS_ENTITY];
		$uri = $request['uri'];
		if (HTTP_ENTRY != '/' && stripos($uri, HTTP_ENTRY) === 0)
			$uri = substr($uri, strlen(HTTP_ENTRY));
		if (stripos($uri, $entity) === 0)
			$uri = substr($uri, strlen($entity));
		$request['entity'] = $entity;
		$uri = preg_replace(array('/\/+/', '/\?.*$/', '/\/+$/'), array('/', '', ''), $uri);
		$request['pure_uri'] = ($uri == null || $uri == '/') ? '/' : $uri;
		return $uri;
	}

    /**
     * 根据规则，匹配并获取路由参数
     * @param $rule
     * @param null $tok
     */
    public function mkRule(& $rule, & $tok = null) {
		$rule = str_replace('/', '\/', $rule);
		if (is_array($tok) && $tok) $rule = str_replace(array_keys($tok), array_values($tok), $rule);
		$rule = '/^\/'.$rule.'($|\/(.*)$)/i';
	}
}