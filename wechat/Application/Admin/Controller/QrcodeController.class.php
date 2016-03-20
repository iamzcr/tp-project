<?php
/**
 * 微信二维码
 */
namespace Admin\Controller;
use Think\Controller;
class QrcodeController extends AdminController {
    public function indexAction(){
        $this->display();
    }
}