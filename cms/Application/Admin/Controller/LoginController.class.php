<?php
namespace Admin\Controller;
use Think\Controller;
class LoginController extends Controller {
    public  function  indexAction(){
        layout(false); // 临时关闭当前模板的布局功能
        $this->display();
    }
    public function  loginAction(){

        if (IS_AJAX){
            $manager = D('manager');
            $data = I('post.manager');

            $res = $manager->where("email = '%s'",$data['email'])->find();
            if($res){
                if($res['password'] != md5($data['password'])){
                    $data = array('status'=>0,'info'=>'登录邮箱或密码错误','url'=>U('Admin/Login/index'));
                    $this->ajaxReturn($data);
                }else{
                    session('manager_id',$res['id']);
                    session('email',$res['email']);
                    $data = array('status'=>1,'info'=>'登录成功,点击进入后台','url'=>U('Admin/Index/index'));
                    $this->ajaxReturn($data);
                }
            }
        }
    }
    public  function  logoutAction()
    {
        session(null);
        $this->success('登出成功，正在跳转...',U('Admin/User/login'));
    }
}