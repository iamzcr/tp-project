<?php
// 项目配置文件
// Janpoem 2010/12/15
return array(
	'common' => array(
		'requestValid' => array(
			'key'		=> '_MM_REQUEST_',
			'format'	=> ':project_name|:ip|:session_id|:value'
		),
	),
	MST_Core::IN_DEV => array(
		'database' => array(
            'pgsql' => array(
                'adapter'	=> MST_DBC::PDO_PGSQL,
                'host'		=> '192.168.33.2',	// 数据库连接ip
                'user'		=> 'postgres',		// 数据库账号
                'password'	=> 'postgres',	// 数据库密码
                'dbname'	=> 'test',
                'prefix'	=> '',
            ),
		),
		MST_Mailer::FLAG => array(
			MST_Mailer::LOCAL => array(
				'host'		=> 'localhost',
				'port'		=> 25,
				'debug'		=> 0,
				'charset'	=> PROJECT_ENCODE,
				'language'	=> PROJECT_LANG,
				MST_Mailer::IS_SMTP 	=> true,
				MST_Mailer::SMTP_AUTH	=> false,
				MST_Mailer::FROM_MAIL	=> 'noreply@mixmedia.com',
				MST_Mailer::FROM_NAME	=> '测试邮箱',
			)
		),
	),
	MST_Core::IN_TEST => array(
        'database' => array(
            'pgsql' => array(
                'adapter'	=> MST_DBC::PDO_PGSQL,
                'host'		=> '192.168.33.2',	// 数据库连接ip
                'user'		=> 'postgres',		// 数据库账号
                'password'	=> 'postgres',	// 数据库密码
                'dbname'	=> 'test',
                'prefix'	=> '',
            ),
        ),
		MST_Mailer::FLAG => array(
			MST_Mailer::LOCAL => array(
				'host'		=> 'localhost',
				'port'		=> 25,
				'debug'		=> 0,
				'charset'	=> PROJECT_ENCODE,
				'language'	=> PROJECT_LANG,
				MST_Mailer::IS_SMTP 	=> true,
				MST_Mailer::SMTP_AUTH	=> false,
				MST_Mailer::FROM_MAIL	=> 'noreply@mixmedia.com',
				MST_Mailer::FROM_NAME	=> '测试邮箱',
			)
		),
	),
	MST_Core::IN_PRO => array(
        'database' => array(
            'pgsql' => array(
                'adapter'	=> MST_DBC::PDO_PGSQL,
                'host'		=> '192.168.33.2',	// 数据库连接ip
                'user'		=> 'postgres',		// 数据库账号
                'password'	=> 'postgres',	// 数据库密码
                'dbname'	=> 'test',
                'prefix'	=> '',
            ),
        ),
		MST_Mailer::FLAG => array(
			MST_Mailer::LOCAL => array(
				'host'		=> 'localhost',
				'port'		=> 25,
				'debug'		=> 0,
				'charset'	=> PROJECT_ENCODE,
				'language'	=> PROJECT_LANG,
				MST_Mailer::IS_SMTP 	=> true,
				MST_Mailer::SMTP_AUTH	=> false,
				MST_Mailer::FROM_MAIL	=> 'noreply@mixmedia.com',
				MST_Mailer::FROM_NAME	=> '测试邮箱',
			)
		),
	),
);
