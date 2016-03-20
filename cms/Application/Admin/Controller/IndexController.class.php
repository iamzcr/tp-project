<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends AdminController {
    public function indexAction(){
        $this->display();
    }
}