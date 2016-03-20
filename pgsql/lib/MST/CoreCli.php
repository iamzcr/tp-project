<?php
/**
 * MixMVC Cli运行模式
 *
 * @author Sam
 */
if (!class_exists('MST_Core')) {
    include_once(dirname(__FILE__).DIRECTORY_SEPARATOR.'Core.php');
}
/**
 * Class MST_CoreCli 框架Cli运行 MST_Core 封装
 * 自保留Autoload 功能，不进入request流程
 * @package MST
 */
class MST_CoreCli extends MST_Core {

    /**
     * 判定时候在Cli模式下运行
     * @return bool
     */
    static public function in_cli() {
        return (php_sapi_name() == self::CLI);
    }

    /**
     * 判定是否为入口主文件
     * @param string $path 当前文件的完整物理路径
     * @return bool
     */
    static public function is_main($path) {
        if (!self::in_cli()) {
            return false;
        }
        if (!empty($_SERVER['argv']) && is_array($_SERVER['argv']) && isset($_SERVER['argv'][0]) && $_SERVER['argv'][0] == $path) {
            return true;
        }
        return false;
    }

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
            if (!defined(parent::LITE_MODE))
                parent::import(parent::$_requireLibs, self::P_LIB);

            parent::localization();
            spl_autoload_register(array('MST_Core', 'autoLoad'));
            parent::loadConfig();
        }
    }
}
