<?php
namespace Home\Controller;
use Think\Controller;
class HomeController extends Controller {

    public function _initialize(){
        vendor('Wechat.WechatJSON');
        vendor('Wechat.OAuth');
        vendor('Wechat.WechatXML');
        vendor('Wechat.WeReq');
    }
}