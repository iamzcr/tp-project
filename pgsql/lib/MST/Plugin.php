<?php
/**
 * Class MST_Plugin
 * @package MST
 */
abstract class MST_Plugin {
	
	private
		$view = null;
	
	final public function __construct(MST_ActionView $view, $args = null) {
		$this->view = $view;
		if (method_exists($this, '__onInit')) {
			call_user_func_array(array($this, '__onInit'), (array)$args);
		}
	}
	
	public function getView() {
		return $this->view;
	}

	// 輸出
	public function render($name = null, array $params = null) {
		$file = $name == null ? 'index' : (string)$name;
		$path = MST_Core::getPathOf(get_called_class() . '/' . $file, MST_Core::P_PLUGIN, '.phtml');
		if (is_file($path)) {
			include $path;
		}
		return $this;
	}
}