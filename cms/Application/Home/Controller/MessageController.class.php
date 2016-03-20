<?php
namespace Home\Controller;
use Think\Controller;
class MessageController extends HomeController {
    public function _initialize(){
        parent::_initialize();
    }
    public function indexAction(){
        if(IS_AJAX){
            $data = I('post.message');
            $message = D('message');

            $res = $message->where("email = '%s'",$data['email'])->find();
            if($res){
                $record = array('status'=>0,'info'=>'请不要重复留言','url'=>U('Home/Message/index'));
                $this->ajaxReturn($record);
            }
            $data['create_time'] = time();

            $message->create($data);
            if($message->add()){
                $record = array('status'=>1,'info'=>'留言成功','url'=>U('Home/Message/index'));
                $this->ajaxReturn($record);
            }
        }
        $this->display();
    }
}