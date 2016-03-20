<?php
/**
 * MM Cache Client文件
 */

/**
 * Class MM_MClient MM cache client
 * @package MM
 */
class MM_MClient {

    /**
     * 通信标识
     */
    const FLAG = 'mclient';
    const LOCAL = 'localhost';
    // config flag
    const HOST = 'host';
    const PORT = 'port';
    const TIMEOUT = 'timeout';
    const PASSWORD = 'password';
    const SPEARATOR = 'separator';
    
    
    private static
		$_instance = array(),
		$_error_num = 0,
		$_error_str = null;

	private
		$_host = null,
		$_port = null,
		$_socket = null,
		$_timeout = 8,
		$_password = '123456',
		$_separator = '<.>',
		$_err_num = 0,
		$_err_str = null;

	/**
	 *
	 * @param string $host
	 * @param string|int $port
	 * @return MM_MClient 
	 */
	public static function instance($remote = self::LOCAL) {
		if (!isset(self::$_instance[$remote])) {
            $config = MST_Core::getConfig(self::FLAG, $remote);
            if ($config != null)
                self::$_instance[$remote] = new self($config[self::HOST], $config[self::PORT]);
            else
                return false;
		}
		return self::$_instance[$remote];
	}
	
	protected function __construct($host, $port, $timeout = 10) {
		$this->_host = $host;
		$this->_port = $port;
		$this->_timeout = $timeout;
	}

	public function __destruct() {
		$this->disconnect();
	}

	private function connect() {
		$cmd = $this->build_command(func_get_args());
		if (!$cmd)
			trigger_error('错误的指令类型');
		if ($cmd) {
			$this->_socket = @fsockopen($this->_host, $this->_port, $this->_err_num, $this->_err_str, $this->_timeout);
			if ($this->_socket && fputs($this->_socket, $cmd))
				return true;
		}
		return false;
	}

	public function disconnect() {
		if (!empty($this->_socket) && is_resource($this->_socket))
			fclose($this->_socket);
	}

	public function has($key) {
		if ($this->connect('haskey', $key)) {
			$value = trim(fgets($this->_socket, 2));
			$this->disconnect();
			return intval($value) > 0;
		}
		return false;
	}

	public function add($key) {
		if ($this->connect('saveadd', $key)) {
			$value = trim(fgets($this->_socket));
			$this->disconnect();
			return is_numeric($value) ? intval($value) : 0;
		}
		return false;
	}

	public function minus($key) {
		if ($this->connect('saveminus', $key)) {
			$value = trim(fgets($this->_socket));
			$this->disconnect();
			return is_numeric($value) ? intval($value) : 0;
		}
		return false;
	}

	public function save($key, $value = null) {
		if ($this->connect('save', $key, $value)) {
			$value = trim(fgets($this->_socket, 2));
			$this->disconnect();
			return $value;
		}
		return false;
	}

	/*
	public function more(array $params = null) {
		if ($this->connect('savemore', $params)) {
			$value = trim(fgets($this->_socket, 2));
			$this->disconnect();
			return $value;
		}
		return false;
	}
	*/

	public function clean() {
		if ($this->connect('clean')) {
			$value = trim(fgets($this->_socket, 2));
			$this->disconnect();
			return $value;
		}
		return false;
	}

	public function load($key) {
		if ($this->connect('load', $key)) {
			$value = trim(fgets($this->_socket));
			$this->disconnect();
			if ($value === '')
				return null;
			return $value;
		}
		return false;
	}

	public function remove($key) {
		if ($this->connect('del', $key)) {
			$value = trim(fgets($this->_socket, 2));
			$this->disconnect();
			return $value;
		}
		return false;
	}

	public function getAllKeys() {
		if ($this->connect('all')) {
			$value = explode($this->_separator, fgets($this->_socket));
			array_pop($value);
			$this->disconnect();
			return $value;
		}
		return false;
	}

	public function count() {
	if ($this->connect('count')) {
			$value = trim(fgets($this->_socket));
			$this->disconnect();
			return is_numeric($value) ? intval($value) : 0;
		}
		return false;
	}

	private function filter_key($key) {
		return $key;
	}

	private function build_command(array $args = null) {
		// 0:为类型
		if (!isset($args[0])) return false;
		// 指令過濾
		if ($args[0] == 'savemore') {
			if (empty($args[1])) return false;
			$params = $args[1];
			$args = array($args[0]);
			foreach ($params as $key => $val) {
				$val = (string)$val;
				if ($val == '+' || $val == '-') {
					if ($val == '+')
						$args[] = $this->filter_key($key) . '<..>funny<..>j';
					elseif ($val == '-')
						$args[] = $this->filter_key($key) . '<..>funny<..>jj';
					else
						continue;
				}
				else {
					$args[] = $this->filter_key($key) . '<..>' . $val;
				}
			}
		}
		elseif (isset($args[1])) {
			$args[1] = $this->filter_key($args[1]);
		}
		// 生成最終指令
		if (!empty($args)) {
			array_unshift($args, $this->_password);
			// 最后一位补<.>
//			$args[] = 'sess_id_'.$_SESSION['index'];
			$args[] = '';
			return implode($this->_separator, $args);
		}
		return false;
	}
}

