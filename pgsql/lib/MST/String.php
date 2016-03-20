<?php
/**
 * Class MST_String
 * 字符串处理类
 * @package MST
 */
class MST_String {

	const
		DET_HIGHLIGHT = '<span class="mst-str-highlight">%s</span>';

	protected static
		$_loadStr = array();

    /**
     * 返回字符串长度[根据项目encoding]
     * @param $str
     * @return int
     */
    static public function length($str) {
		return mb_strlen($str, PROJECT_ENCODE);
	}

    /**
     * 返回字符串字数宽度[根据项目encoding]
     * @param $str
     * @return int
     */
    static public function width($str) {
		return mb_strwidth($str, PROJECT_ENCODE);
	}

    /**
     * 根据固定长度裁剪字符
     * @param $str 待裁字符串
     * @param $length 需要显示的长度
     * @param string $suffix 裁掉部分的后缀
     * @return string
     */
    static public function cut($str, $length, $suffix = '...') {
		$strLen = mb_strlen($str, PROJECT_ENCODE);
		$suffixLen = mb_strlen($suffix, PROJECT_ENCODE);
		if ($strLen <= $length || $strLen <= $suffixLen)
			return $str;
		return (mb_substr($str, 0, $length - $suffixLen, PROJECT_ENCODE)) . $suffix;
	}

    /**
     * 根据固定字数裁剪字符
     * @param $str 待裁字符串
     * @param $width 需要显示的长度
     * @param string $suffix 裁掉部分的后缀
     * @return string
     */
    static public function widthCut($str, $width, $suffix = '...') {
		$strWidth = mb_strwidth($str, PROJECT_ENCODE);
		$suffixWidth = mb_strwidth($suffix, PROJECT_ENCODE);
		if ($strWidth <= $width || $strWidth <= $suffixWidth)
			return $str;
		$newStr = mb_strimwidth($str, 0, $width, $suffix, PROJECT_ENCODE);
		return $newStr;
	}

    /**
     * 根据首字母大写风格,格式化字符串 [方案1]
     * <code>
     * echo MST_String::camelize('mst_string');
     * //output：Mst_String
     * echo MST_String::camelize('mst/string', '/');
     * //output：Mst/String
     * echo MST_String::camelize('mst_string', '_', true);
     * //output：mst_String
     * </code>
     * @param $val 源字符串
     * @param string $split 分割符
     * @param bool $firstLower 是否首字母小写
     * @return mixed|string
     */
    static public function camelize($val, $split = '_', $firstLower = false) {
		if (!$val) return $val;
		if (strpos($val, $split) === false) return (!$firstLower ? ucwords($val) : $val);
		$val = strtolower($val);
		$val = preg_replace('/'.$split.'([a-z]{1})?/e', 'strtoupper(\'\\1\')', $val);
		if ($firstLower) $val{0} = strtoupper($val{0});
		return $val;
	}

    /**
     * 根据首字母大写风格,格式化字符串 [方案2]
     * <code>
     * echo MST_String::camelize('mst_string');
     * //output：Mst_String
     * echo MST_String::camelize('mst/string', '/');
     * //output：Mst/String
     * echo MST_String::camelize('mst_string', '_', true);
     * //output：mst_String
     * </code>
     * @param $val 源字符串
     * @param string $split 分割符
     * @param bool $firstUpper 是否首字母小写
     * @return mixed|string
     */
    static public function camelize2($val, $split = '_', $firstUpper = true) {
		if ($val == null) return $val;
		if (strpos($val, $split) === false) return ($firstUpper ? ucfirst($val) : $val);
		$val = str_replace('_', ' ', $val);
		$val = ucwords($val);
		$val = str_replace(' ', null, $val);
		if (!$firstUpper)
			$val = lcfirst($val);
		return $val;
	}

    /**
     * 将任何特殊字符替换成下滑线，并去掉最后出现的下划线 [方案1]
     * @param $val
     * @param string $split
     * @return mixed|string
     * @deprecated
     */
    static public function tableize($val, $split = '_') {
		$val = preg_replace('/([A-Z]{1})/e', '\'_\' . strtolower(\'\\1\')', $val);
		if ($val{0} == '_') $val = substr($val, 1);
		return $val;
	}

    /**
     * 将任何特殊字符替换成下滑线，并去掉最后出现的下划线 [方案2]
     * @param $val 源字符串
     * @param string $split
     * @return mixed|string
     */
    static public function tableize2($val, $split = '_'){
		if ($val == null) return $val;
		$val = strtolower($val);
		$val = str_replace('/',' ',$val);
		$val = str_replace('(',' ',$val);
		$val = str_replace('-',' ',$val);
		$val = str_replace(')',' ',$val);
		$val = str_replace(' ','_',$val);
		if(strrchr($val,'_') == '_')
			$val = substr_replace($val,' ',-1);

		trim($val);
		return $val;
	}

    /**
     * strtotime 一样功能
     * @param $date
     * @return int
     */
    static public function date2num($date) {
		$parse = date_parse($date);
		return mktime($parse['hour'], $parse['minute'], $parse['second'], $parse['month'], $parse['day'], $parse['year']);
	}

    /**
     * 生成字符转码表缓存[简繁转码]
     * @param $type
     * @return bool|int
     */
    static public function buildStrCache($type) {
		$name = "String/{$type}";
		$file = MST_Core::getPathOf($name, MST_Core::P_LIB, '.txt');
		if (is_file($file)) {
			$str = file_get_contents($file);
			$strAry = explode("\r\n", $str);
			$result = array();
			foreach ($strAry as $line) {
				$items = explode(',',$line);
				for ($i = 1; $i < count($items);$i++) {
					$result[$items[$i]] = $items[0];
				}
			}
			return file_put_contents(MST_Core::getPathOf($name, MST_Core::P_LIB, '.php'), "<?php\r\nreturn ".var_export($result, 1).";");
		}
		return false;
	}

    /**
     * 获取字符转码表[简繁转码]
     * @param $type
     * @return mixed
     */
    static public function getStrCache($type) {
		if (!isset(self::$_loadStr[$type])) {
			$file = 'String/' . $type;
			self::$_loadStr[$type] = MST_Core::import($file, MST_Core::P_LIB, '.php');
		}
		return self::$_loadStr[$type];
	}

    /**
     * 转码[简繁转码]字符
     * @param $str
     * @param $type
     * @return mixed
     */
    static public function charReplace($str, $type) {
		if ($typeWords = self::getStrCache($type)) {
			return str_replace(array_keys($typeWords), array_values($typeWords), $str);
		}
		return $str;
	}

    /**
     * 在任意文本中提取摘要
     * @param $content
     * @param int $len 需要提取的长度
     * @return mixed|string
     */
    static public function summary($content, $len = 256) {
		$content = nl2br($content);
		$content = strip_tags($content);
		$content = str_replace('&nbsp;', ' ', $content);
		$content = trim($content);
		$content = preg_replace('/([\r\n]+|[\s]{2,})/i', ' ', $content);
		$content = MST_String::cut($content, $len);
		return $content;
	}

	const MINUTE = 60;
	const HOUR   = 3600;
	const DAY    = 86400;

    /**
     * 生成多少时间之前的时间格式字符串
     * @param $date
     * @return string
     */
    public static function dateBeforeNow($date) {
		$diff = time() - $date;
		$unit = null;
		$suffix = '前';
		if ($diff < self::MINUTE) {
			$unit = '秒';
		}
		else if ($diff < self::HOUR) {
			$unit = '分钟';
			$last = round($diff / self::MINUTE, 0);
			$diff = intval($last);
		}
		else if ($diff < self::DAY) {
			$unit = '小时';
			$diff = round($diff / self::HOUR, 0);
		}
		else if ($diff < self::DAY * 10) {
			$unit = '天';
			$diff = round($diff / self::DAY, 0);
		}
		else {
			$diff = date('Y/m/d', $date) . ' ';
			$unit = $suffix = null;
		}
		return $diff . $unit . $suffix;
	}
}
