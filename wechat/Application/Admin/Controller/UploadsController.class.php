<?php
/**
 * ecshop用户资料
 */
namespace Admin\Controller;
use Think\Controller;
class UploadsController extends AdminController {
    public function indexAction(){
        $uploads = D('uploads');
        $list = $uploads->get_uploads_list();
        $this->assign('data',$list);
        $this->display();
    }
}