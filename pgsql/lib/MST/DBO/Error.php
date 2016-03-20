<?php
/**
 * Class MST_DBO_Error
 * @package MST_DBO
 */
class MST_DBO_Error implements IMST_DBO_Error {

	const
		GENERAL = '#general#';

	protected
		$_errors = array();

	public function getError($key) {
		if (isset($this->_errors[$key]))
			return $this->_errors[$key];
	}

	public function setError($key, $msg = null) {
		$this->_errors[$key] = $msg;
		return $this;
	}

	public function getErrors() {
		return $this->_errors;
	}

	public function hasError($key = null) {
		if ($key == null)
			return !empty($this->_errors);
		else
			return isset($this->_errors[$key]);
	}

	public function countError() {
		return empty($this->_errors) ? 0 : count($this->_errors);
	}

	public function clearError($key = null) {
		if ($key == null)
			$this->_errors = array();
		else {
			if (isset($this->_errors[$key]))
				unset($this->_errors[$key]);
		}
	}
}