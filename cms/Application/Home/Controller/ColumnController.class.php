<?php
namespace Home\Controller;
use Think\Controller;
class ColumnController extends HomeController {
    public function _initialize(){
        parent::_initialize();
    }
    public function indexAction(){
        $column_content = D('column_content');
        $column_id = I("get.column_id");
        $detail = $column_content->get_content_list($column_id);
        $this->assign('detail',$detail);
        $this->display();
    }
}