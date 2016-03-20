<?php
namespace Admin\Controller;
use Think\Controller;
class AdminController extends Controller {
    public $access_token,$manager_id,$wechat;
    public function _initialize(){

        vendor('Wechat.WechatJSON');
        vendor('Wechat.OAuth');
        vendor('Wechat.WechatXML');
//        import('Vendor.Wechat.Wechat');
        // 自动运行方法
        $this->manager_id = session("manager_id");
        if(empty($this->manager_id)){
            $this->redirect('Admin/Login/index');
        }
    }
}