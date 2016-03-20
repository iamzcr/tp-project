<?php
namespace Home\Controller;
class LoginController extends HomeController {
    //初始化操作
    function _initialize() {
        parent::_initialize();
    }

    public  function  loginAction()
    {
        if($this->user_id){
            $this->redirect('User/index');
        }else{
            $this->display();
        }
    }
    public  function do_loginAction()
    {
        if(IS_POST){
            $loginData = I('post.login');
            $user = D('user');

            $row = $user->where("email='%s'" ,array($loginData['email']))->find();
            if($row){
                if($row['password'] != md5($loginData['password'])){
                    $this->error('邮箱或密码错误',U('Login/login'));
                }else{
                    $this->save_cart($row['user_id']);
                    session('user_id',$row['user_id']);
                    $this->success('登录成功，正在跳转...',U('User/index'));
                }
            }
        }
    }
    public  function  registerAction()
    {
        if(IS_POST){
            $registerData = I('post.register');
            $user = D('user');
            foreach($registerData as $v){
                if(empty($v)){
                    $this->error('请完整填写注册信息',U('Login/login'));
                }
            }
            if($registerData['password'] != $registerData['repassword']){
                $this->error('两次密码不一致',U('Login/login'));
            }
            $row = $user->where("email='%s' or phone='%s' or username='%s'",array($registerData['email'],$registerData['phone'],$registerData['username']))->find();
            if($row){
                $this->error('邮箱、电话、或者账号已存在，请重新注册',U('Login/login'));
            }

            $registerData['password'] = md5($registerData['password']);
            $user->create($registerData);
            $res = $user->add();
            if($res){
                $this->save_cart($res);
                $this->success('注册成功，正在跳转...',U('User/index'));
            }else{
                $this->error('注册失败，请重新注册',U('Login/login'));
            }
        }
    }
    //登录后保存session中购物车信息
    function save_cart($user_id)
    {
        $cart = M('cart');
        $data = $cart->where("session_id = '%s'",$this->session_id)->select();

        foreach($data as $k => $v){
            $record['user_id'] = $user_id;
            $cart->where("cart_id = %d",$v['cart_id'])->save($record);
        }
    }
}
