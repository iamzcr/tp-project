<?php
return array(
    //'配置项'=>'配置值'
    'LAYOUT_ON'=>true,
    'LAYOUT_NAME'=>'layout',


    'URL_CASE_INSENSITIVE' =>true, //不区分大小写
    'URL_HTML_SUFFIX'=>'',//设置伪静态

    'ACTION_SUFFIX'         =>  'Action', // 操作方法后缀

    'DEFAULT_FILTER'        => 'strip_tags,htmlspecialchars', //get/post获取变量进行过滤
    'DB_TYPE'      =>  'mysql',     // 数据库类型
    'DB_HOST'      =>  '127.0.0.1',     // 服务器地址
    'DB_NAME'      =>  'deep_shop',     // 数据库名
    'DB_USER'      =>  'root',     // 用户名
    'DB_PWD'       =>  '123456',     // 密码
    'DB_PORT'      =>  '3306',     // 端口
    'DB_PREFIX'    =>  'de_',     // 数据库表前缀
    'DB_DSN'       =>  '',     // 数据库连接DSN 用于PDO方式
    'DB_CHARSET'   =>  'utf8', // 数据库的编码 默认为utf8

//    'DEFAULT_FILTER'        => 'strip_tags,htmlspecialchars', //get/post获取变量进行过滤
//    'DB_TYPE'      =>  'mysql',     // 数据库类型
//    'DB_HOST'      =>  'localhost',     // 服务器地址
//    'DB_NAME'      =>  'yfcityne_wx',     // 数据库名
//    'DB_USER'      =>  'yfcityne_wx',     // 用户名
//    'DB_PWD'       =>  'ilovejing1314',     // 密码
//    'DB_PORT'      =>  '3306',     // 端口
//    'DB_PREFIX'    =>  'de_',     // 数据库表前缀
//    'DB_DSN'       =>  '',     // 数据库连接DSN 用于PDO方式
//    'DB_CHARSET'   =>  'utf8', // 数据库的编码 默认为utf8

    'alipay_config'=>array(
        'partner'              =>'你的支付宝接口信息',
        'key'                  =>'你的支付宝接口信息',
        'seller_email'           => 'ef.service@ef.com',
        'sign_type'            => strtoupper('MD5'),
        'input_charset'        => strtolower('utf-8'),
        'cacert'               => getcwd().'/cacert.pem',
        'transport'            => 'http',
    ),
    'alipay'              =>array(
        //这里是异步通知页面url，提交到项目的Pay控制器的notifyurl方法；
        'notify_url'=>'http://localhost/deep_shop/index.php/home/pay/notifyurl',
        //这里是页面跳转通知url，提交到项目的Pay控制器的returnurl方法；
        'return_url'=>'http://localhost/deep_shop/index.php/home/pay/returnurl',

        'product_url'=>'http://localhost/deep_shop/index.php/home/product/index',
    ),

    'TMPL_L_DELIM'    =>    '<{',
    'TMPL_R_DELIM'    =>    '}>',
);