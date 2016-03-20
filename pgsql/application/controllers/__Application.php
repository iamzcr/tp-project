<?php
class __Application extends MST_ActionController {

	public
        $pageTitle,
        $sub_title,
        $systemName,
		$layout = 'default',
		$format = 'html';
	
	public function application() {
        $this->systemName = 'PostgreSQL Sample';
		//$this->setViewOption('debug', true);
	}

    public function render_json($res) {
        return $this->render(self::TEXT, json_encode($res));
    }
}
