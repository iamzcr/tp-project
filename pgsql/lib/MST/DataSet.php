<?php
/**
 * 框架使用的基础数据集合类
 * @package MST
 */
class MST_DataSet extends ArrayObject implements IMST_DataSet {

	protected
		$_asRow = false;

	public function __construct(array $data = null, $asRow = false) {
		if ($data != null) {
			parent::__construct($data);
		}
		if ($asRow)
			$this->_asRow = true;
		if ($this->_asRow)
			$this->setFlags(ArrayObject::ARRAY_AS_PROPS);
	}

	public function isEmpty() {
		return (array)$this == null;
	}

	public function isRow() {
		return $this->_asRow;
	}
}

