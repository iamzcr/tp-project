<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-10-11
 * Time: 上午7:04
 */
namespace Admin\Controller;
use Think\Controller;
class ResponseController extends AdminController {
    public function indexAction()
    {
        $response = D('response');
        $data = $response->get_response_list();
        $this->assign('data',$data);
        $this->display();
    }
    public function  textAction()
    {
        if(IS_AJAX){
            $data = I('post.response');
            $data['create_time'] = time();
            $temp = M('response');
            $temp->create($data);
            if($temp->add()){
                $data = array('status'=>1,'info'=>'新增成功','url'=>U('Admin/Response/index'));
                $this->ajaxReturn($data);
            }else{
                $data = array('status'=>0,'info'=>'新增失败','url'=>U('Admin/Response/index'));
                $this->ajaxReturn($data);
            }
        }
        $this->display();
    }
}