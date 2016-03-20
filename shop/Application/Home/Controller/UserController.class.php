<?php
namespace Home\Controller;
class UserController extends HomeController {
    public $user_id;
    //初始化操作
    function _initialize() {

        $this->user_id = session('user_id');
        if(empty($this->user_id)){
            $this->error('你还没登录',U('Login/login'));
        }
    }
    public function indexAction()
    {
        $user =D('user');
        $user_data = $user->where($this->user_id)->find();
        $this->assign('user_data',$user_data);
        $this->display();
    }
    public function pwdAction()
    {
        $this->display();

    }
    public function  do_pwdAction()
    {
        if(IS_POST){
            $data = array();
            $pwd = I('post.pwd');
            if($pwd['new_password'] != $pwd['re_new_password'])
            {
                $this->error('两次密码不一致',U('User/pwd'));
            }
            $user = M('user');
            $res = $user->where('user_id = %d',$this->user_id)->find();
            if($res['password'] != md5($pwd['old_password'])){
                $this->error('原密码不正确',U('User/pwd'));
            }
            $data['password'] = md5($pwd['new_password']);
            $record = $user->where('user_id = %d',$this->user_id)->save($data);
            if($record){
                $this->success('密码修改成功',U('User/index'));
            }else{
                $record->error('密码修改失败',U('User/index'));
            }
        }
    }
    public function infoAction()
    {
        $this->display();

    }
    public function  do_infoAction()
    {
        if(IS_POST){
            $info = I('post.info');
            $user = M('user');
            $res = $user->where('user_id = %d',$this->user_id)->save($info);
            if($res){
                $this->success('修改成功',U('User/index'));
            }else{
                $this->error('修改失败',U('User/index'));
            }
        }
    }
    public function logoutAction()
    {
        session('user_id',null); //
        $this->success('登出成功',U('Product/index'));
    }
}
