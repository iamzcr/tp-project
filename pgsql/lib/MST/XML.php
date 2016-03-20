<?php

/**
 * Class MST_XML
 * XML 处理类
 * @author janpoem
 * @package MST
 */
class MST_XML {

    /**
     * 创建XML节点
     * @static
     * @param $tag 节点类型[TYPE]
     * @param null $context 节点的内容
     * @param null $atts 节点的属性
     * @param bool $isStr 是否返回字符串或者直接输出
     * @return string|void 返回字符串或者直接输出
     */
	static public function tag(
		$tag,
		$context = null,
		$atts = null,
		$isStr = false)
	{
		$atts = self::attributes($atts);
		if ($context == null)
			$sXml = '<'.$tag.$atts.'/>';
		else
			$sXml = '<'.$tag.$atts.'><![CDATA[' . (string)$context .  ']]></'.$tag.'>';
		if (!$isStr)
			echo $sXml;
		else
			return $sXml;
	}

    /**
     * 创建XML节点属性
     * @static
     * @param null|string|array $atts 节点内容
     * 当string类型时直接输出，array类型序列化为键值对
     * @return null|string
     */
	static public function attributes($atts = null) {
		if ($atts == null) return null;
		if (is_string($atts)) return ' ' . $atts;
		if (!is_array($atts)) return null; // 再次过滤剩余的可能
		$result = '';
		foreach ($atts as $key => $val) {
			if ($key === 0) $result .= ' ' . $val;
			else $result .= ' ' . $key . '="' . $val . '"';
		}
		return $result;
	}

}

/**
 * HTML处理类
 * @package MST
 */
class MST_HTML extends MST_XML {

    /**
     * 行内tag列表
     */
    const INLINE_TAGS = '|input|img|link|meta|';

	protected static
		$_pagerOptions = array(
			'limit'      => 0, // 限制分页只输出多少页
			'count'      => 8, // 定义分页的link的数量
			'isForward'  => true, // 是否有导向按钮 定义是否出现上一页、下一页
			'isPolar'    => true, // 是否有两极按钮 定义是否出现第一页、最后一页
			'isJump'     => false, //时候显示跳转按钮
			'link'       => ':index', //分页索引替换模板 其中:index会被替换成索引值
			'prev'       => '&laquo;', //上一页链接文字
			'next'       => '&raquo;', //下一页链接文字
			'toFirst'    => '已经是第一页', //分页时第一页显示字符串
			'toLast'     => '已经是最后一页', //分页时最后一页显示字符串
			'space'      => '...', //中间省略文字
			'linkClass'  => 'p-link', //分页基础样式class
			'currClass'  => 'p-link p-curr', //当前页样式class
			'prevClass'  => 'p-link p-prev p-forward', //上一页样式class
			'nextClass'  => 'p-link p-next p-forward', //下一页样式class
			'firstClass' => 'p-link p-first p-polar', //第一页样式class
			'lastClass'  => 'p-link p-last p-polar', //最后一页样式class
			'disClass'   => 'p-text p-dis', //中间省略点样式class
			'spaceClass' => 'p-text p-space', //占位样式class
			'jumpBtn'    => 0, //跳转页面按钮
			'jumpText'   => 'Go', //跳转页面按钮文字
			'jumpClass'  => 'p-jump', //跳转页面按钮样式class
		);

	static public function tag(
		$tag,
		$context = null,
		$atts = null,
		$isStr = false)
	{
		$atts = self::attributes($atts);
		$tag = strtolower($tag);
		if (strpos(self::INLINE_TAGS, '|'.$tag.'|') !== false) {
			switch ($tag) {
				case 'input' : $atts .= ' value="' . $context . '"'; break;
				case 'img'   : $atts .= $context ? ' src="' . $context . '"'   : null; break;
				case 'link'  : $atts .= $context ? ' href="' . $context . '"'  : null; break;
			}
			$sHtml = '<' . $tag . $atts . ' />';
		}
		else
			$sHtml = '<' . $tag . $atts . '>' . (string)$context . '</' . $tag . '>';
		if (!$isStr)
			echo $sHtml;
		else
			return $sHtml;
	}

    /**
     * 创建链接[link]节点
     * @param $context link内容文字
     * @param null $href 跳转链接
     * @param null $atts 节点属性
     * @param bool $isStr 是否返回字符串[或直接输出]
     * @return string|null
     */
    static public function linkTo($context, $href = null, $atts = null, $isStr = false)
	{
		$atts = self::attributes($atts);
		if ($href == null) {
            $href = '#';
        } else {
            $href = httpUri($href);
        }
		$sHtml = '<a href="' . $href . '"' . $atts . '>' . (string)$context . '</a>';
		if (!$isStr)
			echo $sHtml;
		else
			return $sHtml;
	}

    /**
     * 创建[input]节点
     * @param string $type input类型
     * @param null $value input valeu值
     * @param null $atts 节点属性
     * @param bool $isStr 是否返回字符串[或直接输出]
     * @return string|null
     */
    static public function input($type = 'text', $value = null, $atts = null, $isStr = false) {
		$atts = self::attributes($atts);
		if ($value === 0) $value = '0';
		$sHtml = '<input type="' . $type . '" value="' . $value . '"'.$atts.'/>';
		if (!$isStr)
			echo $sHtml;
		else
			return $sHtml;
	}

    /**
     * 创建图片元素节点
     * @param $src 图片url地址
     * @param null $alt 图片加载失效时显示文字
     * @param null $atts 节点属性
     * @param bool $isStr 是否返回字符串[或直接输出]
     * @return string|null
     */
    static public function img($src, $alt = null, $atts = null, $isStr = false)
	{
		$atts = self::attributes($atts);
		$sHtml = '<img src="'.$src.'"';
		if ($alt != null)
			$sHtml .= ' alt="'.$alt.'"';
		if ($atts != null)
			$sHtml .= $atts;
		if (!$isStr)
			echo $sHtml;
		else
			return $sHtml;
	}

    /**
     * 创建图片链接[链接包裹图片]节点
     * @param $src 图片url地址
     * @param null $href 跳转链接
     * @param null $atts link节点属性
     * @param bool $isStr 是否返回字符串[或直接输出]
     * @return null|string
     */
    static public function imgLinkTo($src, $href = null, $atts = null, $isStr = false)
	{
		$img = self::img($src, null, 'border="0"', true);
		return self::linkTo($img, $href, $atts, $isStr);
	}

    /**
     * 生成分页节点
     * @param MST_DBO_DataSet $data 分页信息数据
     * @param array $options 分页参数见 MST_HTML::$_pagerOptions
     * @param bool $isStr 是否返回字符串[或直接输出]
     * @return bool|null|string
     */
    static public function pagerLink(
		MST_DBO_DataSet $data,
		array $options = null,
		$isStr = false)
	{
        global $data_cache;
		if ($data->isEmpty() || !$pager = $data->getPager())
			return false;
		if ($options == null)
			$options = self::$_pagerOptions;
		else
			$options = array_merge(self::$_pagerOptions, $options);

		$pageCount = $pager['pageCount'];
		$pageCurr = $pager['pageCurrent'];
		$paramStr = $pager['pageParam'];
		$linkCount = $options['count'];
		$isPolar = $options['isPolar'];

		if (isset($options['baseUri'])) {
			$parse = parse_url($options['baseUri']);
			$baseUri = $parse['path'] == null ? '/' : $parse['path'];
			$baseUri = linkUri($baseUri);
			$get = null;
			if (isset($parse['query']))
				parse_str($parse['query'], $get);
		}
		else {
			$get = $_GET;
			$baseUri = $data_cache['request']->pure_uri;
		}
//		$baseUri = linkUri($baseUri);

		unset($get[$paramStr]);
		if (!empty($get)) {
			$baseUri .= '?' . http_build_query($get) . '&amp;' . $paramStr . '=';
			unset($get);
		}
		else {
			$baseUri .= '?' . $paramStr . '=';
		}

		if ($pageCount > $linkCount) {
			$half = (int)($linkCount / 2) - 1;
			$start = $pageCurr - $half;
			if ($start < 1) $start = 1;
			$over = $start + $linkCount - ($isPolar ? ($start == 1 ? 2 : 3) : 1);
			if ($over > $pageCount) {
				$over = $pageCount;
				$start = $over - ($linkCount - ($isPolar ? ($over == $pageCount ? 2 : 3) : 1));
				if ($start <= 1) $start = 1;
			}
		}
		else {
			$start = 1;
			$over = $pageCount;
		}
		if ($options['isForward']) {
			$prev = $pageCurr - 1;
			$next = $pageCurr + 1;
			if ($next > $pageCount) $next = $pageCount;
			if ($prev < 1) $prev = 1;
		}

		$sHtml = null;
		if ($options['isForward']) {
			if ($pageCurr == $prev)
				$sHtml .= self::tag('span', $options['prev'], 'title="'.$options['toFirst'].'" class="'.$options['prevClass'].'"', 1);
			else
				$sHtml .= self::linkTo($options['prev'], $baseUri . $prev, 'class="'.$options['prevClass'].'"', 1);
		}

		if ($start != 1) {
			$sHtml .= self::linkTo(1, $baseUri . 1, 'class="'.$options['linkClass'].'"', 1);
			if ($start - 1 != 1)
				$sHtml .= self::tag('span', $options['space'], 'class="'.$options['spaceClass'].'"', 1);
		}

		$index = $start;
		while ($index <= $over) {
			$linkStr = str_replace($options['link'], ':index', $index);
			$linkClass = $index == $pageCurr ? $options['currClass'] : $options['linkClass'];
			if ($index == $pageCurr) {
				$sHtml .= self::tag('strong',  $linkStr, 'class="'.$linkClass.'"', 1);
			}
			else {
				$sHtml .= self::linkTo($linkStr, $baseUri . $index, 'class="'.$linkClass.'"', 1);
			}
			$index++;
		}

		if ($over != $pageCount) {
			if ($over + 1 != $pageCount)
				$sHtml .= self::tag('span', $options['space'], 'class="'.$options['spaceClass'].'"', 1);
			$sHtml .= self::linkTo($pageCount, $baseUri . $pageCount, 'class="'.$options['linkClass'].' p-last"', 1);
		}

		if ($options['isForward']) {
			if ($pageCurr == $next)
				$sHtml .= self::tag('span', $options['next'], 'title="'.$options['toLast'].'" class="'.$options['nextClass'].'"', 1);
			else
				$sHtml .= self::linkTo($options['next'], $baseUri . $next, 'class="'.$options['nextClass'].'"', 1);
		}
		
		if ($options['isJump']) {
			$baseId = 'p_jump_' . time();
			$pageOp = array('max' => $pageCount, 'min' => 1, 'current' => $pageCurr, 'uri' => linkUri($baseUri));
			$sHtml .= '<span class="'.$options['jumpClass'].'">';
			$sHtml .= self::tag('input', $pageCurr, ' id="'.$baseId.'_val" class="'.$options['jumpClass'].'-tb"', 1);
			$sHtml .= self::linkTo($options['jumpText'], $baseUri . $pageCurr, 'type="button" id="'.$baseId.'_btn" class="'.$options['jumpClass'].'-btn"', 1);
			//$sHtml .= self::tag('input', $options['jumpText'], 'type="button" id="'.$baseId.'_btn" class="'.$options['jumpClass'].'-btn"', 1);
			$sHtml .= '</span><script type="text/javascript">Agi.PagerJump("'.$baseId.'", '.json_encode($pageOp).');</script>';
		}
		
		if ($isStr)
			return $sHtml;
		else
			echo $sHtml;
	}

    /**
     * 生成分页节点
     * @param array $data
     * @param array $pager 分页信息数据
     * @param array $options 分页参数见 MST_HTML::$_pagerOptions
     * @param bool $isStr 是否返回字符串[或直接输出]
     * @return bool|null|string
     */
    static public function pagerLinkByData(
		array $data,
		array $pager,
		array $options = null,
		$isStr = false)
	{
        global $data_cache;
		if (empty($data) || empty($pager))
			return false;
		if ($options == null)
			$options = self::$_pagerOptions;
		else
			$options = array_merge(self::$_pagerOptions, $options);

		$pageCount = $pager['pageCount'];
		$pageCurr = $pager['pageCurrent'];
		$paramStr = isset($pager['pageParam']) ? $pager['pageParam'] : 'page';
		$linkCount = $options['count'];
		$isPolar = $options['isPolar'];

		if (isset($options['baseUri'])) {
			$parse = parse_url($options['baseUri']);
			$baseUri = $parse['path'] == null ? '/' : $parse['path'];
			$baseUri = linkUri($baseUri);
			$get = null;
			if (isset($parse['query']))
				parse_str($parse['query'], $get);
		}
		else {
			$get = $_GET;
			$baseUri = $data_cache['request']->pure_uri;
		}
		unset($get[$paramStr]);
		if (!empty($get)) {
			$baseUri .= '?' . http_build_query($get) . '&amp;' . $paramStr . '=';
			unset($get);
		}
		else {
			$baseUri .= '?' . $paramStr . '=';
		}

		if ($pageCount > $linkCount) {
			$half = (int)($linkCount / 2) - 1;
			$start = $pageCurr - $half;
			if ($start < 1) $start = 1;
			$over = $start + $linkCount - ($isPolar ? ($start == 1 ? 2 : 3) : 1);
			if ($over > $pageCount) {
				$over = $pageCount;
				$start = $over - ($linkCount - ($isPolar ? ($over == $pageCount ? 2 : 3) : 1));
				if ($start <= 1) $start = 1;
			}
		}
		else {
			$start = 1;
			$over = $pageCount;
		}
		if ($options['isForward']) {
			$prev = $pageCurr - 1;
			$next = $pageCurr + 1;
			if ($next > $pageCount) $next = $pageCount;
			if ($prev < 1) $prev = 1;
		}

		$sHtml = null;
		if ($options['isForward']) {
			if ($pageCurr == $prev)
				$sHtml .= self::tag('span', $options['prev'], 'title="'.$options['toFirst'].'" class="'.$options['prevClass'].'"', 1);
			else
				$sHtml .= self::linkTo($options['prev'], $baseUri . $prev, 'class="'.$options['prevClass'].'"', 1);
		}

		if ($start != 1) {
			$sHtml .= self::linkTo(1, $baseUri . 1, 'class="'.$options['linkClass'].'"', 1);
			if ($start - 1 != 1)
				$sHtml .= self::tag('span', $options['space'], 'class="'.$options['spaceClass'].'"', 1);
		}

		$index = $start;
		while ($index <= $over) {
			$linkStr = str_replace($options['link'], ':index', $index);
			$linkClass = $index == $pageCurr ? $options['currClass'] : $options['linkClass'];
			if ($index == $pageCurr) {
				$sHtml .= self::tag('strong',  $linkStr, 'class="'.$linkClass.'"', 1);
			}
			else {
				$sHtml .= self::linkTo($linkStr, $baseUri . $index, 'class="'.$linkClass.'"', 1);
			}
			$index++;
		}

		if ($over != $pageCount) {
			if ($over + 1 != $pageCount)
				$sHtml .= self::tag('span', $options['space'], 'class="'.$options['spaceClass'].'"', 1);
			$sHtml .= self::linkTo($pageCount, $baseUri . $pageCount, 'class="'.$options['linkClass'].' p-last"', 1);
		}

		if ($options['isForward']) {
			if ($pageCurr == $next)
				$sHtml .= self::tag('span', $options['next'], 'title="'.$options['toLast'].'" class="'.$options['nextClass'].'"', 1);
			else
				$sHtml .= self::linkTo($options['next'], $baseUri . $next, 'class="'.$options['nextClass'].'"', 1);
		}

		if ($options['isJump']) {
			$baseId = 'p_jump_' . time();
			$pageOp = array('max' => $pageCount, 'min' => 1, 'current' => $pageCurr, 'uri' => linkUri($baseUri));
			$sHtml .= '<span class="'.$options['jumpClass'].'">';
			$sHtml .= self::tag('input', $pageCurr, ' id="'.$baseId.'_val" class="'.$options['jumpClass'].'-tb"', 1);
			$sHtml .= self::linkTo($options['jumpText'], $baseUri . $pageCurr, 'type="button" id="'.$baseId.'_btn" class="'.$options['jumpClass'].'-btn"', 1);
			//$sHtml .= self::tag('input', $options['jumpText'], 'type="button" id="'.$baseId.'_btn" class="'.$options['jumpClass'].'-btn"', 1);
			$sHtml .= '</span><script type="text/javascript">Agi.PagerJump("'.$baseId.'", '.json_encode($pageOp).');</script>';
		}

		if ($isStr)
			return $sHtml;
		else
			echo $sHtml;
	}
}
