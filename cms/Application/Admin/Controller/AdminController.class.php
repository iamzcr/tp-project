<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-12-5
 * Time: 上午3:45
 */
namespace Admin\Controller;
use Think\Controller;
class AdminController extends Controller {
    public $manager_id;
    public function _initialize(){
        // 自动运行方法
        $this->manager_id = session("manager_id");
        if(empty($this->manager_id)){
            $this->error("没有登录",U('Admin/Login/index'));
        }
    }
}