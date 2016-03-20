<?php
$GLOBALS['MST_ERR'] = array(
	/* 预留错误定义空间 */
	0 => array(),
	/* 核心流程错误 MST_Core专用，中断流程 直接exit */
	1 => array(
		1 => 'Illegal applications!',
		2 => 'Project run configuration is empty! Please check the definition of the project Config!',
		3 => 'Error definition file is missing, check %s！',
		4 => 'Mb_string extension is not loaded, please check PHP configuration!',
		5 => '"%s" file does not exist!',
	),
	/* Controller、Zend流程错误，中断流程 404错误 */
	2 => array(
		0 => 'Bootstrap file %s could not be loaded!',
		1 => 'The controller does not exist, file not found "%s"!',
		2 => 'The controller does not exist, undefined "%s" class!',
		3 => 'The action does not exist, undefined method %s!',
		4 => 'Layout file "%s" does not exist!',
		10 => 'Zend运行出错：%s',
	),
	/* Model、数据库错误，中断流程 500错误 */
	3 => array(
		1 => 'Lack of database configuration, please check the configuration database #config#database#%s!',
		2 => 'Database Adapter does not exist, please check #config#database#%s!',
		3 => 'Database Adapter does not exist, file not found "%s", or undefined "%s" class!',
		4 => 'Can not connect to the database (%s), please check the connection configuration!',
		5 => 'Can not find the specified Model, file "%s" does not exist or is not defined "%s" class!',
		10 => 'SQL execution error: %s<br /><br />SQL: %s<br /><br />Params: %s',
		11 => 'SQL query is empty, or the lack of necessary component parameters. <br /><br />SQL: %s<br /><br />Conditions: %s<br /><br />Params: %s',
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
		1 => 'Page has the output (render or redirect), the output can not be repeated!',
		2 => 'Variable binding format is incorrect, please re-bound!',
		3 => 'Not allowed to render an empty content!',
		10 => 'View file "%s" does not exist!',
		11 => 'Widget file "%s" does not exist!',
	),
	5 => array(
		0 => '%s must not be empty!',
		1 => '%s is not a valid number!',
		3 => 'Standard time format: ' . date('Y-m-d H:i:s'),
		10 => '%s is not a valid floating point number!',
		11 => '%s can\'t more than %d!',
		12 => '%s can\'t less than %d!',
		20 => '%s do not meet the specified format!',
		21 => '%s\'s length can\'t more than %d!',
		22 => '%s\'s length can\'t less than %d!',
		30 => '%s is not a valid time format!',
		31 => '%s有误差，输入：%s，实际：%s',
		90 => 'The data structure required fields!',
		99 => 'Value already exists %s "%s" record!',
	),
	'unknow' => 'Unknown Error!',
);
