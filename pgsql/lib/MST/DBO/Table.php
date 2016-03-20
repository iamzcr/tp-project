<?php
/**
 * 数据表结构
 * @package MST_DBO
 */
class MST_DBO_Table {
	
	private
		$_model = null,
		$_table = null,
		$_prefix = null;
	
	public function __construct($class, $table = null) {
		$this->_model = (string)$class;
		if ($table == null)
			$this->_table = strtolower($this->_model);
		else
			$this->_table = (string)$table;
	}

    /**
     * 设置表名前缀
     * @param $prefix
     */
    public function setPrefix($prefix) {
		$this->_prefix = (string)$prefix;
	}
	
	public function __get($key) {
		if (isset($this->$key))
			return $this->$key;
		return null;
	}

    /**
     * 魔术方法 返回带前缀的表名
     * @return string
     */
    public function __toString() {
		return ($this->_prefix == null ? null : $this->_prefix . '_') . $this->_table;
	}
}

?>