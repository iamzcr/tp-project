<?php
/**
 *  MixMVC 最小化启动
 *
 *  @author Sam
 */
if (!class_exists('MST_Core')) {
    include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'Core.php');
}

//开启lite模式
if (!defined(MST_Core::LITE_MODE)) {
    define(MST_Core::LITE_MODE, true);
}

/**
 *
 * Class MST_CoreLite 最小化启动MST_Core封装
 * 可以在cli环境下使用，自保留Autoload 功能，不进入request流程
 * @package MST
 */
class MST_CoreLite extends MST_Core {

    static public function start($env = self::IN_DEV, array $options = null, $beforeDispatch = null) {
        if ($env != parent::IN_PRO && $env != parent::IN_TEST)
            $env = parent::IN_DEV;
        // 必须步骤#1
        // 设定当前运行环境
        parent::$_env = $env;
        // 在Generator就无需执行本地化操作了
        if (!defined(parent::RUN_IN_GENERATOR)) {
            // 必须步骤#3
            // 初始化$options
            if ($options != null)
                parent::$_options = array_merge(parent::$_options, $options);

            //重新配置 lib 加载目录
            $local_path = dirname(dirname(__FILE__));
            self::setPath(self::P_LIB, $local_path, true);

            //重载简易版错误输出
            self::setErrorHandle(array('MST_CoreLite', 'error_handler_lite'));

            //如果需要mysql支持的话，必须传入config参数指定加载config文件的路径
            if (isset(self::$_options['config'])) {
                self::setPath(self::P_CONFIG, dirname(self::$_options['config']), true);
            }

            parent::localization();
            //重新配置 model 加载目录
            self::setPath(self::P_MODEL, self::$_options['root'].self::$_options['model_path'], true);

            spl_autoload_register(array('MST_Core', 'autoLoad'));
            parent::loadConfig();
        }
    }

    static public function error_handler_lite() {
        $error = func_get_args();
        $status = $error[0] == 2 ? 404 : 500;
        header("{$_SERVER['SERVER_PROTOCOL']} {$status}");
        echo <<<ERR_TXT
<div style="color:red;">
<h2>{$status} Error</h2>
Error Code:{$error[0]}<br />
Message:{$error[1]}
</div>
ERR_TXT;
        exit();
    }

}

/*
 * smaple start code
MST_CoreLite::start(MST_CoreLite::IN_DEV, array(
    'root' => realpath('.'),
    'config' => dirname(__FILE__).'/config/config.php',
    'lang' => 'en',
    'name' => 'MVC Lite Mode',
    'model_path' => '/models',
));
*/
