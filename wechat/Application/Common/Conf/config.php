<?php
return array(
    'DEFAULT_MODULE'     => 'Home',           //默认模块
    'MODULE_DENY_LIST'   => array('Common'), //禁止访问模块
    'URL_CASE_INSENSITIVE' =>true, //不区分大小写
    'URL_HTML_SUFFIX'=>'',//设置伪静态

    'ACTION_SUFFIX'         =>  'Action', // 操作方法后缀
    //微信测试号
//    'appid' => 'wx750451f72688e733',
//    'appsecret' => '22c34f45c1a2e70426179280c719aece',
    //野球俱乐部
//    'appid' => 'wxbef06a349545f939',
//    'appsecret' => '3690e245c2b5f4e9c654e61f4eb1bf4b',
    //分销商
      'appid' => 'wx2336d0e99d8929e7',
      'appsecret' => '3bfe98ca05561cc673cc8375bb9ae3c5',
      'token' => 'feisudaifa',
      'aeskey' => 'AthLK2KOk8FFIqTidK215tTEYsMmNmd98p2TPJq36nd',

    'TMPL_L_DELIM'    =>    '<{',
    'TMPL_R_DELIM'    =>    '}>',

    // 'DEFAULT_FILTER'        => 'strip_tags,htmlspecialchars', //get/post获取变量进行过滤
    // 'DB_TYPE'      =>  'mysql',     // 数据库类型
    // 'DB_HOST'      =>  '100.98.112.171',     // 服务器地址
    // 'DB_NAME'      =>  'test',     // 数据库名
    // 'DB_USER'      =>  'admin',     // 用户名
    // 'DB_PWD'       =>  'admin',     // 密码
    // 'DB_PORT'      =>  '3306',     // 端口
    // 'DB_PREFIX'    =>  'we_',     // 数据库表前缀
    // 'DB_DSN'       =>  '',     // 数据库连接DSN 用于PDO方式
    // 'DB_CHARSET'   =>  'utf8', // 数据库的编码 默认为utf8

   'DEFAULT_FILTER'        => 'strip_tags,htmlspecialchars', //get/post获取变量进行过滤
   'DB_TYPE'      =>  'mysql',     // 数据库类型
   'DB_HOST'      =>  'localhost',     // 服务器地址
   'DB_NAME'      =>  'wx',     // 数据库名
   'DB_USER'      =>  'root',     // 用户名
   'DB_PWD'       =>  '123456',     // 密码
   'DB_PORT'      =>  '3306',     // 端口
   'DB_PREFIX'    =>  'we_',     // 数据库表前缀
   'DB_DSN'       =>  '',     // 数据库连接DSN 用于PDO方式
   'DB_CHARSET'   =>  'utf8', // 数据库的编码 默认为utf8
);