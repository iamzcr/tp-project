<?php
namespace Home\Controller;
use Think\Controller;
class LoginController extends Controller {
    public function indexAction(){
        layout(false); // 临时关闭当前模板的布局功能
        $this->display();
        if(IS_POST){
            $email = I('post.email');
            $password = I('post.password');
            $manager = D('manager');
            $row = $manager->where("email='%s'" ,array($email))->find();
            if($row){
                if($row['password'] != md5($password)){
                    $this->error('邮箱或密码错误',U('Login/index'));
                }else{
                    session('manager_id',$row['manager_id']);
                    session('name',$row['name']);
                    session('email',$row['email']);
                    $this->success('登录成功，正在跳转...',U('Index/index'));
                }
            }else{
                $this->error('邮箱或密码错误',U('Login/index'));
            }
        }
    }
}