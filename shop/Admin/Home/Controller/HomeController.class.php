<?php
namespace Home\Controller;
use Think\Controller;
class HomeController extends Controller {


    public $manager_id,$name,$email;

    //初始化操作
    function _initialize() {
        $this->manager_id = session('manager_id');
        if(!isset($this->manager_id)){
            $this->error('你还没登录',U('Home/Login/index'));
        }else{
            $this->name = session('name');
            $this->email = session('email');
            $this->assign('name',$this->name);
            $this->assign('email',$this->email);
        }
    }
}