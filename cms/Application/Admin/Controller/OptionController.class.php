<?php
namespace Admin\Controller;
use Think\Controller;
class OptionController extends AdminController {
    const
        DEEP_EMAIL = 'deep_email',
        DEEP_PHONE = 'deep_phone',
        DEEP_FAX = 'deep_fax',
        DEEP_NAME = 'deep_name',
        DEEP_ADDRESS = 'deep_address',

        DEEP_TITLE = 'deep_title',
        DEEP_KEYWORD = 'deep_keyword',
        DEEP_SEO = 'deep_seo',

        DEEP_MARK = 'deep_mark',
        DEEP_POWERED= 'deep_powered',

        DEEP_LOGO = 'deep_logo';




    public function settingAction() {

        if(IS_AJAX){
            $option = D('option');
            $deep = I('post.deep');
            $option->set_option(self::DEEP_NAME,$deep['name']);
            $option->set_option(self::DEEP_PHONE,$deep['phone']);
            $option->set_option(self::DEEP_FAX,$deep['fax']);
            $option->set_option(self::DEEP_EMAIL,$deep['email']);
            $option->set_option(self::DEEP_ADDRESS,$deep['address']);

            $data = array('status'=>1,'info'=>'设置成功','url'=>U('Admin/Option/setting'));
            $this->ajaxReturn($data);
        }

        $option = D('option');
        $deep_name = $option->get_option(self::DEEP_NAME);
        $deep_phone = $option->get_option(self::DEEP_PHONE);
        $deep_fax = $option->get_option(self::DEEP_FAX);
        $deep_email = $option->get_option(self::DEEP_EMAIL);
        $deep_address = $option->get_option(self::DEEP_ADDRESS);

        $this->assign('deep_name',$deep_name);
        $this->assign('deep_phone',$deep_phone);
        $this->assign('deep_fax',$deep_fax);
        $this->assign('deep_email',$deep_email);
        $this->assign('deep_address',$deep_address);

        $this->display();
    }
    public  function  logoAction()
    {
        if(IS_POST){
            $upload = new \Think\Upload();// 实例化上传类
            $upload->autoSub = false;// 关闭子目录
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      './Public/Upload/base/'; // 设置附件上传根目录
            $upload->saveName = time().'_'.mt_rand();
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['my_image']);
            if($info){
                $logo_image =  $info['savename'];
            }else{
                $logo_image = 'deep_shop.jpg';
            }

            $option = D('option');
            $option->set_option(self::DEEP_LOGO,$logo_image);
            $this->redirect('Admin/Option/logo');
        }

        $option = D('option');
        $deep_logo = $option->get_option(self::DEEP_LOGO);
        $this->assign('deep_logo',$deep_logo);
        $this->display();
    }
    public function footeringAction() {


        if(IS_AJAX){
            $option = D('option');
            $deep = I('post.deep');
            $option->set_option(self::DEEP_MARK,$deep['mark']);
            $option->set_option(self::DEEP_POWERED,$deep['powered']);
            $data = array('status'=>1,'info'=>'设置成功','url'=>U('Admin/Option/footering'));
            $this->ajaxReturn($data);
        }
        $option = D('option');
        $deep_mark = $option->get_option(self::DEEP_MARK);
        $deep_powered = $option->get_option(self::DEEP_POWERED);

        $this->assign('deep_mark',$deep_mark);
        $this->assign('deep_powered',$deep_powered);

        $this->display();
    }
    public  function  seoAction() {
        if(IS_AJAX){
            $option = D('option');
            $deep = I('post.deep');
            $option->set_option(self::DEEP_TITLE,$deep['title']);
            $option->set_option(self::DEEP_KEYWORD,$deep['keyword']);
            $option->set_option(self::DEEP_SEO,$deep['seo']);
            $data = array('status'=>1,'info'=>'设置成功','url'=>U('Admin/Option/seo'));
            $this->ajaxReturn($data);
        }
        $option = D('option');
        $deep_title = $option->get_option(self::DEEP_TITLE);
        $deep_keyword = $option->get_option(self::DEEP_KEYWORD);
        $deep_seo = $option->get_option(self::DEEP_SEO);

        $this->assign('deep_title',$deep_title);
        $this->assign('deep_keyword',$deep_keyword);
        $this->assign('deep_seo',$deep_seo);

        $this->display();
    }
}