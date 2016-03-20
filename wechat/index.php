<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件
// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');
//if (! empty ( $_GET ['echostr'] ) && ! empty ( $_GET ["signature"] ) && ! empty ( $_GET ["nonce"] )) {
//    $signature = $_GET ["signature"];
//    $timestamp = $_GET ["timestamp"];
//    $nonce = $_GET ["nonce"];
//
//    $tmpArr = array (
//        'feisudaifa',
//        $timestamp,
//        $nonce
//    );
//    sort ( $tmpArr, SORT_STRING );
//    $tmpStr = sha1 ( implode ( $tmpArr ) );
//
//    if ($tmpStr == $signature) {
//        echo $_GET ["echostr"];
//    }
//    exit ();
//}

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);

define('APP_PATH','./Application/');
//定义物理路径常量
define('DOC_ROOT', dirname(__FILE__));

// 引入ThinkPHP入口文件
require './ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单