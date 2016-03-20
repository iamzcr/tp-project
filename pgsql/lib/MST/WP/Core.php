<?php
if (!class_exists('MST_Core')) {
    require_once dirname(dirname(__FILE__)).'/Core.php';
}

/**
 * Class MST_WP_Core
 * @package MST_WP
 */
class MST_WP_Core extends MST_Core {

    static public function WP_AutoLoad($class) {
        $namespace = strtok($class, self::SLASH_CLASS);
        $fullPath = $class;
        // 如果在全局的CoreLibNamespace的空间
        if (isset(self::$_coreLibNamespace[$namespace])) {
            $fullPath = str_replace(self::SLASH_CLASS, self::SLASH_PATH, $fullPath);
            return self::import($fullPath, self::P_LIB);
        }
        // 不然则当做是load Model
        // 暂时不考虑加载非Model的部分
        else {
            if (strpos($fullPath, self::SLASH_CLASS)) {
                $fullPath = str_replace(self::SLASH_CLASS, self::SLASH_PATH, $fullPath);
                $fullPath = strtolower(dirname($fullPath)) . '/' . basename($fullPath);
            }
            switch (true) {
                case strripos($fullPath, 'controller') !== false :
                    $errorCode = 201;
                    $alias = static::P_CONTROLLER;
                    break;
                case strripos($fullPath, 'application') !== false :
                    $errorCode = 200;
                    $alias = static::P_CONTROLLER;
                    break;
                case strripos($fullPath, 'plugin') != false :
                    $errorCode = 211;
                    $alias = static::P_PLUGIN;
                    break;
                default:
                    $errorCode = 105;
                    $alias = static::P_MODEL;
            }
            if (!self::import($fullPath, $alias)) {
                if (!static::inPro())
                    $path = MST_Core::getPathOf($fullPath, $alias, '.php', true);
                else
                    $path = $fullPath;
//                MST_Core::error($errorCode, $path);
            }
            if (get_parent_class($class) == 'MST_DBO')
                call_user_func(array($class, '__init'));
        }
    }


    static public function start($env = self::IN_DEV, array $options = null, Closure $beforeDispatch = null) {
        if ($env != self::IN_PRO && $env != self::IN_TEST)
            $env = self::IN_DEV;
        // 必须步骤#1
        // 设定当前运行环境
        self::$_env = $env;

        self::setErrorHandle(array('MST_WP_Core', 'wp_error'));
        // 在Generator就无需执行本地化操作了
        if (!defined(self::RUN_IN_GENERATOR)) {
            // 必须步骤#3
            // 初始化$options
            if ($options != null)
                self::$_options = array_merge(self::$_options, $options);
            if (!defined(self::LITE_MODE))
                self::import(self::$_requireLibs, self::P_LIB);

            self::localization();
            spl_autoload_register(array('MST_WP_Core', 'WP_AutoLoad'));
            self::loadConfig();
        }

        MST_WP_ActionController::init(MST_WP_Core::getConfig());
    }

    static public function get_WP_httpEntry($file) {
        $plugin_url = plugin_dir_url($file);
        $site_url = get_site_url();
        return str_replace($site_url, '', $plugin_url);
    }

    static public function wp_error($errorType, $errorText) {
        $debug = null;
        if (self::inDev()) {
            $debug = debug_backtrace();
        }
        $status = $errorType == 2 ? 404 : 500;
        header("{$_SERVER['SERVER_PROTOCOL']} {$status}");
        if (class_exists('MST_ActionView')) {
            MST_ActionView::instance()->assign(array(
                'error' => $errorText,
                'debug' => $debug,
                'status' => $status
            ))->import('MST/error/' . PROJECT_LANG . '/render.phtml', self::P_LIB);
        }
        else {
            // 临时
            echo <<<ERR_TXT
<h2>{$status}错误</h2>
错误提示：{$errorText}
ERR_TXT;
        }
        if ($status == 500) {
            exit();
        }
    }
}