<?php
$GLOBALS['MST_ERR'] = array(
	/* 预留错误定义空间 */
	0 => array(),
	/* 核心流程错误 MST_Core专用，中断流程 直接exit */
	1 => array(
		1 => '非法的引用环境！',
		2 => '项目运行配置为空！请检查项目Config的定义！',
		3 => 'MStation错误定义文件丢失，请检查%s！',
		4 => '未加载mb_string扩展，请检查PHP配置！',
		5 => '不存在文件%s！',
	),
	/* Controller、Zend流程错误，中断流程 404错误 */
	2 => array(
		0 => '无法加载Bootstrap文件%s！',
		1 => '不存在Controller，未找到文件%s！',
		2 => '不存在Controller，未定义%s类！',
		3 => '不存在Action，未定义%s方法！',
		4 => '不存在Layout文件%s',
		10 => 'Zend运行出错：%s',
		11 => '不存在擴展：%s',
	),
	/* Model、数据库错误，中断流程 500错误 */
	3 => array(
		1 => '缺少数据库配置，请检查#config#database#%s的配置！',
		2 => '不存在数据库连接器，请检查#config#database#%s的配置！',
		3 => '不存在数据库连接器，不存在%s文件，或未定义%s类！',
		4 => '无法连接数据库（%s），请检查连接配置！',
		5 => '无法找到指定的Model，不存在%s文件，或未定义%s类！',
		10 => 'SQL执行错误：%s<br /><br />SQL: %s<br /><br />Params: %s',
		11 => 'SQL查询为空，或缺少必要的构成参数。<br /><br />SQL: %s<br /><br />Conditions: %s<br /><br />Params: %s',
		12 => '不允许通过(object)%s->create()一条空数据，要插入空数据请使用MST_DBC::insert()。',
		13 => '数据没有任何变更，如要强行更新，请传入$isForce = true。',
		14 => '缺少要操作的对象，%s操作，数据库操作禁止操作空对象！<br /><br />Params: %s',
		15 => '要更新数据请执行(object)%s->update()。',
		16 => '(object)%s->update($args)缺少操作对象$args，要更新全表请使用MST_DBC::updateAll($data)。',
		17 => '(object)%s->delete($args)缺少操作对象$args，要删除全表请使用MST_DBC::deleteAll()或MST_DBC::turncate()。',
		20 => '表间关联，关联配置为空。<br/><br/>%s::$%s[\'%s\'] = %s',
		21 => '表间关联（%s），当前数据集不存在指定的字段%s。<br /><br />Object(%s) => %s',
	),
	/* 警告 */
	4 => array(
		1 => '页面已经输出（render或redirect），无法重复输出！',
		2 => '变量绑定的格式不正确，请重新绑定！',
		3 => '不允许render空content！',
		10 => '不存在View文件%s！',
		11 => '不存在Widget文件%s！',
	),
	5 => array(
		0 => '請填寫%s！',
		1 => '%s不是一個有效數字！',
		3 => '正確的時間格式應該是：' . date('Y-m-d H:i:s'),
		10 => '%s不是一個有效的浮點數！',
		11 => '%s不可大于%d!',
		12 => '%s不可小于%d!',
		20 => '%s不符合指定格式！',
		21 => '%s的长度不得多于%d个字符串！',
		22 => '%s的长度不得少于%d个字符串！',
		30 => '%s不是一个有效的时间格式！',
		31 => '%s有误差，输入：%s，实际：%s',
		90 => '为数据结构中的必填字段！',
		99 => '已经存在%s值为"%s"的记录！',
	),
	'unknow' => '未知错误！',
);
