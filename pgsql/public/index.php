<?php
// 3.1框架入口文件，再次简化
// Janpoem 2010/12/15

// Library路径
$libPath = dirname(dirname(__FILE__)).'/lib';
require_once "{$libPath}/MST/Core.php";

// 修改系统的Library路径
MST_Core::setPath(MST_Core::P_LIB, $libPath, true);

switch ($_SERVER['HTTP_HOST']) {
	case 'Production_Host' : $env = MST_Core::IN_PRO; break;
	case 'localhost' : $env = MST_Core::IN_DEV; break;
	case 'dev.mvctools' : $env = MST_Core::IN_DEV; break;
	default : $env = MST_Core::IN_TEST; break;
}

MST_Core::start($env, array(
	'name' => 'MVC Tools',
	'root' => realpath('..'),
	'lang' => 'zh-cn',
	'httpEntry' => dirname(dirname($_SERVER["SCRIPT_NAME"])),
));
