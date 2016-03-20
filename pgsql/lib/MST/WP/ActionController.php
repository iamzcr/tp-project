<?php

/**
 * Class MST_WP_ActionController
 * @package MST_WP
 */
abstract class MST_WP_ActionController extends MST_ActionController {

    static public
        $_controller_instance = array(),
        $_wp_menu_options = null,
        $_wp_ajax_options = null,
        $_wp_init_options = null;

    public function __construct(MST_ActionController_Request $request, $autoAction = false) {
        $this->params = $request;
        $this->viewPath = trim(
            $this->params['module'] . '/' . $this->params['controller'], '/');
        if ($autoAction && ($this->application() !== self::NO_RENDER || !defined(self::IS_RENDER)))
            $this->action($this->params['action']);
    }

    /**
     * @param array($controller, $action) | $action
     * @return MST_ActionController|MST_WP_ActionController
     */
    public function action($action=null) {
        $args = func_get_args();
        $arg_count = func_num_args();
        if ($arg_count == 0) {
            throw new Exception('args type error, must be array($controller, $action) or $action');
        }
        if ($arg_count > 1) {
            $ctrl = self::getController($args[0], $this->params);
            return $ctrl->action($args[1]);
        }
        parent::action($args[0]);
        return $this;
    }

    static protected function createAdminMenu(MST_ActionController_Request $request) {
        if (!empty(self::$_wp_menu_options)) {
            $options = self::$_wp_menu_options;
            add_action('admin_menu', function() use ($options, $request) {
                $index = 0;
                $top_item = $top_func = null;
                foreach($options as $menu_name => $menu_options) {
                    $request['controller'] = $menu_options['controller'];
                    $ctrl = MST_WP_ActionController::getController($menu_options['controller'], $request);
                    $action = $menu_options['action'];
                    if ($index == 0) {
                        $top_item = $menu_name;
                        $top_func = array($ctrl, $action . MST_WP_ActionController::PF_ACTION);
                        add_menu_page($menu_options['title'], $menu_options['title'], 'manage_options', $menu_name, $top_func, HttpUri('images/icon.gif'), 100);
                    } elseif ($index == 1) {
                        add_submenu_page($top_item, $menu_options['title'], $menu_options['title'], 'manage_options', $top_item, $top_func);
                    } else {
                        add_submenu_page($top_item, $menu_options['title'], $menu_options['title'], 'manage_options', $menu_name, array($ctrl, $action.MST_WP_ActionController::PF_ACTION));
                    }

                    $index++;
                }
            });
        }
    }

    static protected function createInitHook(MST_ActionController_Request $request) {
        if (!empty(self::$_wp_init_options)) {
            if (isset(self::$_wp_init_options['controller']) && isset(self::$_wp_init_options['action'])) {
                add_action('init', function() use ($request) {
                    $request['action'] = MST_WP_ActionController::$_wp_init_options['action'];
                    $request['controller'] = MST_WP_ActionController::$_wp_init_options['controller'];
                    $ctrl = MST_WP_ActionController::getController(MST_WP_ActionController::$_wp_init_options['controller'], $request);
                    $ctrl->action($request['action']);
                });
            }

            if (isset(self::$_wp_init_options['admin']) && isset(self::$_wp_init_options['admin']['controller']) && isset(self::$_wp_init_options['admin']['action'])) {
                add_action('admin_init', function() use ($request) {
                    $request['action'] = MST_WP_ActionController::$_wp_init_options['admin']['action'];
                    $request['controller'] = MST_WP_ActionController::$_wp_init_options['admin']['controller'];
                    $ctrl = MST_WP_ActionController::getController(MST_WP_ActionController::$_wp_init_options['admin']['controller'], $request);
                    $ctrl->action($request['action']);
                });
            }
        }
    }

    static public function defaultAjaxHook() {
        if (isset($_REQUEST['url']) && !empty($_REQUEST['url'])) {
            MST_WP_Core::setRequestUri($_REQUEST['url']);
            $config = MST_WP_Core::getConfig();
            unset($_REQUEST['action'], $_GET['action'], $_POST['action']);
            $request = new MST_ActionController_Request($config['request']);
            $router = new MST_ActionController_Router($config['routes']);
            $router->routing($request);
            $controller = $request['controller'];
            $ctrl = MST_WP_ActionController::getController($controller, $request);
            $ctrl->action($request['action']);
        }
    }

    static protected function createAjaxHook(MST_ActionController_Request $request) {
        //default route action
        if (!empty(self::$_wp_ajax_options)) {
            if (isset(self::$_wp_ajax_options['controller'])) {
                $request['controller'] = self::$_wp_ajax_options['controller'];
                $ctrl = self::getController(self::$_wp_ajax_options['controller'], $request);
                $refClass = new ReflectionClass($ctrl);
                $actions = $refClass->getMethods(ReflectionMethod::IS_PUBLIC);
                add_action('init', function() use ($actions, $request, $ctrl) {
                    foreach($actions as $action) {
                        if (stripos($action->name, MST_WP_ActionController::PF_ACTION) !== false) {
                            $action_shortname = str_ireplace(MST_WP_ActionController::PF_ACTION, '', $action->name);
                            add_action('wp_ajax_nopriv_'.$action_shortname, array($ctrl, $action->name));
                        }
                    }
                    add_action('wp_ajax_nopriv_'.MST_WP_Core::getOption('name').'_route', array('MST_WP_ActionController', 'defaultAjaxHook'));
                });
            }

            if (isset(self::$_wp_ajax_options['admin']) && isset(self::$_wp_ajax_options['admin']['controller'])) {
                $request['controller'] = self::$_wp_ajax_options['admin']['controller'];
                $ctrl = self::getController(self::$_wp_ajax_options['controller'], $request);
                $refClass = new ReflectionClass($ctrl);
                $actions = $refClass->getMethods(ReflectionMethod::IS_PUBLIC);
                add_action('admin_init', function() use ($actions, $request, $ctrl) {
                    foreach($actions as $action) {
                        if (stripos($action->name, MST_WP_ActionController::PF_ACTION) !== false) {
                            $action_shortname = str_ireplace(MST_WP_ActionController::PF_ACTION, '', $action->name);
                            add_action('wp_ajax_'.$action_shortname, array($ctrl, $action->name));
                        }
                    }
                    add_action('wp_ajax_'.MST_WP_Core::getOption('name').'_route', array('MST_WP_ActionController', 'defaultAjaxHook'));
                });
            }
        }
    }

    /**
     * @static
     * @param $controller
     * @param MST_ActionController_Request $request
     * @return MST_WP_ActionController
     */
    static public function getController($controller, MST_ActionController_Request $request) {
        $ctrl = $controller;
        if (!isset(self::$_controller_instance[$controller])) {
            $controller = MST_String::camelize2($controller) . static::PF_CONTROLLER;
            if (!class_exists($controller))
                MST_Core::error(202, $controller);
            else {
                $refClass = new ReflectionClass($controller);
                if ($refClass->isSubclassOf('MST_WP_ActionController')) {
                    $request['controller'] = $ctrl;
                    self::$_controller_instance[$controller] = $refClass->newInstanceArgs(array($request));
                }
                else
                    MST_Core::error(202, $controller);
            }
        }

        return self::$_controller_instance[$controller];
    }

    static public function init(array $config = null) {
        global $data_cache;
        if (isset($config['routes']['menu'])) {
            self::$_wp_menu_options = $config['routes']['menu'];
        }
        if (isset($config['routes']['ajax'])) {
            self::$_wp_ajax_options = $config['routes']['ajax'];
        }
        if (isset($config['routes']['init'])) {
            self::$_wp_init_options = $config['routes']['init'];
        }

        $request = new MST_ActionController_Request($config['request']);
        $router = new MST_ActionController_Router($config['routes']);
        $router->routing($request);
        $data_cache['request'] = & $request;

        self::createInitHook($request);
        self::createAdminMenu($request);
        self::createAjaxHook($request);
    }

    final static public function fire(array $config = null, $beforeDispatch = null) {
        global $data_cache;
        if (self::$_instance == null) {
            $request = new MST_ActionController_Request($config['request']);
            $router = new MST_ActionController_Router($config['routes']);
            $router->routing($request);
            $controller = $request['controller'];
            $controller = MST_String::camelize2($controller) . static::PF_CONTROLLER;
            if ($request['module'] != null) {
                $module = $request['module'];
                if (strpos($module, '/') !== false)
                    $module = str_replace('/', '_', $module);
                $controller = $module . '_' . $controller;
            }
            if (is_callable($beforeDispatch)) {
                call_user_func_array($beforeDispatch, array($request, & $controller));
            }
            $data_cache['request'] = & $request;
            if (!class_exists($controller))
                MST_Core::error(202, $controller);
            else {
                $refClass = new ReflectionClass($controller);
                if ($refClass->isSubclassOf('MST_WP_ActionController')) {
                    self::$_instance = $refClass->newInstanceArgs(array($request));

                } else {
                    MST_Core::error(202, $controller);
                }
            }
        }
    }

    protected function render($mode = null, $content = null, array $options = null) {
        if (defined(self::IS_RENDER)) return self::NO_RENDER;
        define(self::IS_RENDER, true);
        if ($mode == null) $mode = $this->defaultRender;
        if ($mode == self::VIEW)
            $content = $this->viewPath . '/' . $content;
        $view = MST_WP_ActionView::getInstance();
        $view->assign($this);
        $view->setOptions($options);
        $view->render($mode, $content);
        if ($this->params->isAjax()) {
            die();
            return;
        }
        return $this;
    }

    protected function setViewOption($key, $val) {
        MST_WP_ActionView::getInstance()->setOption($key, $val);
        return $this;
    }

    protected function getViewOption($key) {
        return MST_WP_ActionView::getInstance()->getOption($key);
    }

    protected function setViewOptions(array $options) {
        MST_WP_ActionView::getInstance()->setOptions($options);
        return $this;
    }

    protected function getViewOptions()
    {
        return MST_WP_ActionView::getInstance()->getOptions();
    }
}