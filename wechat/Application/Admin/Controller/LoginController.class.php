<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
    public  function  indexAction(){

        if(IS_AJAX){
            $manager = D('manager');
            $data = I('post.login');
            $res = $manager->where("email = '%s'",$data['email'])->find();
            if($res){
                if($res['password'] != md5($data['password'])){
                    $data = array('status'=>0,'info'=>'登录失败','url'=>U('admin/login/login'));
                    $this->ajaxReturn($data);
                }else{
                    session('manager_id',$res['manager_id']);
                    session('name',$res['name']);
                    session('email',$res['email']);
                    $data = array('status'=>0,'info'=>'登录成功','url'=>U('admin/index/index'));
                    $this->ajaxReturn($data);
                }
            }
        }
        layout(false); // 临时关闭当前模板的布局功能
        $this->display();
    }
    public  function  logoutAction()
    {
        session(null);
        $this->redirect('Admin/Login/index');
    }
}