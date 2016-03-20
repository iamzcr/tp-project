<?php
namespace Admin\Controller;
use Think\Controller;
class MessageController extends AdminController {
    public function indexAction(){
        $data =  D('message')->get_message_list();
        $this->assign('data',$data);
        $this->display();
    }
}