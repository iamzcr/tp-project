<?php
/**
 * Class MST_DBO_Validator
 * 数据验证类
 * @package MST_DBO
 */
class MST_DBO_Validator extends MST_DBO_Error {

	const
		DATE_FORMAT			= '/^[1-2][90][\d]{2}\-([0]{0,1}[\d]|1[02])-([012]{0,1}[\d]|3[01])[\s]([01]{0,1}[\d]|2[0-4])\:[0-6]{0,1}[\d]\:[0-6]{0,1}[\d]$/i',
		HTML_TO_STRIP_TAGS	= -1,
		HTML_TO_ENTITIES	= 0,
		IS_HTML				= 1;

	private
		$_rules = array();

	public function __construct(& $rules = null) {
		if ($rules != null && is_array($rules))
			$this->_rules = $rules;
	}

	public function & getRules() {
		return $this->_rules;
	}

	public function addRule($key, $rule) {
		$this->_rules[$key] = $rule;
	}

	public function getRule($key) {
		if (isset($this->_rules[$key]))
			return $this->_rules[$key];
	}

	public function filter(& $val, $rule) {
		$val = trim($val);
		if (!empty($rule['trim']))
			$val = trim($val, $rule['trim']);
		if (!empty($rule['ltrim']))
			$val = ltrim($val, $rule['ltrim']);
		if (!empty($rule['rtrim']))
			$val = rtrim($val, $rule['rtrim']);

		$isHtml = empty($rule['isHtml']) ? self::HTML_TO_STRIP_TAGS : intval($rule['isHtml']);
		switch ($isHtml) {
			case self::HTML_TO_STRIP_TAGS :
				$val = strip_tags($val);
				break;
			case self::HTML_TO_ENTITIES   :
				$val = htmlentities($val, ENT_COMPAT, PROJECT_ENCODE);
				break;
		}
		// 特殊格式，以下特殊格式只允许存在任意中的一种
		switch (true) {
			case !empty($rule['nl2br']) :
				$val = bl2br($val, true);
				break;
			case !empty($rule['wordwrap']) :
				if (!is_array($rule['wordwrap']))
					$rule['wordwrap'] = array($rule['wordwrap']);
				if (is_array($rule['wordwrap'])) {
					array_unshift($rule['wordwrap'], $val);
					$val = call_user_func_array('wordwrap', $rule['wordwrap']);
				}
				break;
			case !empty($rule['urlencode']) :
				$val = urlencode($val);
				break;
			case !empty($rule['urldecode']) :
				$val = urldecode($val);
				break;
		}
		return $val;
	}

	public function validate(& $val, $key = null, $target = null) {
		if (is_array($val) || (is_object($val) && $val instanceof MST_DBO)) {
			array_walk($val, array($this, 'validate'), $target);
			return;
		}
		$rule = empty($this->_rules[$key]) ? array() : $this->_rules[$key];
		$this->filter($val, $rule);
		$valLen = MST_String::length($val);
		$title = empty($rule['title']) ? ucfirst($key) : $rule['title'];
		$isNumber = $isRequire = false;
		$isDate = false;
		if (!empty($rule['presence']) || !empty($rule['require']))
			$isRequire = true;
		if (!empty($rule['isNumber']) || !empty($rule['isNum'])
		 || !empty($rule['isFloat'])
		 || (!empty($rule['numMax']) && $rule['numMax'] > 0)
		 || (!empty($rule['numMin']) && $rule['numMin'] > 0))
			$isNumber = true;
		if ($isRequire && $valLen <= 0)
			return $this->setError($key, sprintf($GLOBALS['MST_ERR'][5][0], $title));
		if ($isNumber && !is_numeric($val))
			return $this->setError($key, sprintf($GLOBALS['MST_ERR'][5][1], $title));
		if (!empty($rule['date'])) {
			$isDate = true;
			if (empty($rule['format'])) $rule['format'] = self::DATE_FORMAT;
			if (empty($rule['sample'])) $rule['sample'] = $GLOBALS['MST_ERR'][5][3];
		}
		# 浮点检测
		if (!empty($rule['isFloat'])) {
			if (is_numeric($rule['isFloat']) && $rule['isFloat'] > 0)
				$val = round($val, $rule['isFloat']);
		}
		# 字符串最大检测
		if (!empty($rule['max']) && is_numeric($rule['max']) && $valLen > $rule['max'])
			return $this->setError($key, sprintf($GLOBALS['MST_ERR'][5][21], $title, $rule['max']));
		# 字符串最小检测
		if (!empty($rule['min']) && is_numeric($rule['min']) && $valLen < $rule['min'])
			return $this->setError($key, sprintf($GLOBALS['MST_ERR'][5][22], $title, $rule['min']));
		# 数字最大检测
		if (!empty($rule['numMax']) && is_numeric($rule['numMax']) && $val > $rule['numMax'])
			return $this->setError($key, sprintf($GLOBALS['MST_ERR'][5][11], $title, $rule['numMax']));
		# 数字最小检测
		if (!empty($rule['numMin']) && is_numeric($rule['numMin']) && $val < $rule['numMin'])
			return $this->setError($key, sprintf($GLOBALS['MST_ERR'][5][12], $title, $rule['numMin']));
		# 字符串正则表达式检测
		if (($valLen > 0 || $isRequire) && !empty($rule['format']) && preg_match($rule['format'], $val) == 0) {
			return $this->setError($key, sprintf($GLOBALS['MST_ERR'][5][($isDate ? 30 : 20)], $title) . (empty($rule['sample']) ? null : $rule['sample']));
		}
		# 最后一次校验时间
		if ($isRequire && $isDate) {
			$parse = date_parse($val);
			$dt = date('Y-m-d H:i:s', mktime($parse['hour'], $parse['minute'], $parse['second'], $parse['month'], $parse['day'], $parse['year']));
			if ($dt != $val)
				return $this->setError($key, sprintf($GLOBALS['MST_ERR'][5][31], $title, $val, $dt));
		}
		# 唯一检测
		if (($valLen > 0 || $isRequire)
		 && (!empty($rule['only']) || !empty($rule['unique']))) {
			$only = empty($rule['only']) ? $rule['unique'] : $rule['only'];
			if (is_string($only)) $only = array($only, $only::getStatic($only, 'primaryKey'));
			else if (is_array($only)) {
				$m = $only[0];
				if (empty($only[1]))  $only[1] = $m::getStatic($m, 'primaryKey');
			}
			list($model, $primaryKey) = $only;
			if (empty($target) || empty($primaryKey))
				$count = $model::rsCount(array('where' => array($key . ' = ?', $val)));
			else {
				$where = array(
					'where' => array($key . ' = ? AND ' . $primaryKey . ' != ?', $val, $target)
				);
				$count = $model::rsCount($where);
			}
			if ($count > 0)
				return $this->setError($key, sprintf($GLOBALS['MST_ERR'][5][99], $title, stripslashes($val)));
		}
	}
}
