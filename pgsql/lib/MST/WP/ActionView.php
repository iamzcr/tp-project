<?php

/**
 * Class MST_WP_ActionView
 * @package MST_WP
 */
class MST_WP_ActionView extends MST_ActionView {

    protected static
        $_instance = null;

    static public function getInstance() {
        if (empty(self::$_instance)) {
            self::$_instance = new MST_WP_ActionView();
        }
        return self::$_instance;
    }

    public function render($mode, $content) {
        if (defined(self::IS_RENDER)) return false;
        define(self::IS_RENDER, true);
        if (!isset($this->_options['layout'])) {
            switch ($mode) {
                case MST_ActionController::TEXT:
                case MST_ActionController::FILE:
                    $this->layout = false;
            }
        }
        else
            $this->layout = $this->_options['layout'];
        if (!empty($this->_options['status']) && is_numeric($this->_options['status']) && $this->_options['status'] != $this->status)
            $this->status = $this->_options['status'];
        if (!empty($this->_options['format']))
            $this->format = $this->_options['format'];
        $this->_render['mode'] = $mode;
        $this->_render['content'] = $content;
        if ($this->layout) {
            $this->import((string)$this->layout, MST_Core::P_LAYOUT, self::EXT);
        }
        else {
            $this->content();
        }
    }

    public function getAjaxData($data = null, $controller = null, $action = null) {
        if (empty($data)) {
            $data = array();
        }
        if (empty($controller)) {
            $controller = $this->params['controller'];
        }
        if (empty($action)) {
            $action = $this->params['action'];
        }
        $data['c'] = $controller;
        $data['a'] = $action;
        $data['action'] = 'route';
        return json_encode($data);
    }
}