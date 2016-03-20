<?php
/**
 * Class MST_DBO_DataSet
 * @package MST_DBO
 */
class MST_DBO_DataSet extends MST_DataSet implements IMST_DataSet {

	protected
		$_asRow = false;

	private
		$_pager = null;

	final public function setPager($pager) {
		$this->_pager = $pager;
	}

	final public function getPager($key = null) {
		if ($this->_pager == null) return null;
		if ($key == null) return $this->_pager;
		if (isset($this->_pager[$key])) return $this->_pager[$key];
		return null;
	}

	public function toXML() {
	}

	// JSON_HEX_QUOT, JSON_HEX_TAG, JSON_HEX_AMP, JSON_HEX_APOS, JSON_FORCE_OBJECT
	public function toJSON($options = JSON_HEX_QUOT) {
		return json_encode((array)$this);
	}

	public function toHTML() {

	}

	public function toArray() {
		return (array)$this;
	}
}
