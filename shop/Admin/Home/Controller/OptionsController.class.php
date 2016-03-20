<?php
namespace Home\Controller;
class OptionsController extends HomeController {
    const
        DEEP_EMAIL = 'deep_email',
        DEEP_PHONE = 'deep_phone',
        DEEP_FAX = 'deep_fax',
        DEEP_NAME = 'deep_name',
        DEEP_ADDRESS = 'deep_address',

        DEEP_TITLE = 'deep_title',
        DEEP_KEYWORD = 'deep_keyword',
        DEEP_SEO = 'deep_seo',

        DEEP_LOGO = 'deep_logo',

        DEEP_SERVICE = 'deep_service',

        DEEP_SUMMARY = 'deep_summary',
        DEEP_CONTENT = 'deep_content';


    public function indexAction()
    {
        $options = D('options');

        $data = $options->get_option();
        $this->assign('data',$data);
        $this->display();
    }
    /*
     * 商城seo
     */
    public function  seoAction()
    {
        $options = D('options');
        $deep_title = $options->get_option(self::DEEP_TITLE);
        $deep_keyword = $options->get_option(self::DEEP_KEYWORD);
        $deep_seo = $options->get_option(self::DEEP_SEO);

        $this->assign('deep_title',$deep_title);
        $this->assign('deep_keyword',$deep_keyword);
        $this->assign('deep_seo',$deep_seo);


        $this->display();

        if(IS_POST){
            $options = D('options');
            $deep = I('post.deep');
            $options->set_option(self::DEEP_TITLE,$deep['title']);
            $options->set_option(self::DEEP_KEYWORD,$deep['keyword']);
            $options->set_option(self::DEEP_SEO,$deep['seo']);
        }
    }
    /*
     * 商城联系方式
     */
    public  function  settingAction()
    {
        $options = D('options');
        $deep_name = $options->get_option(self::DEEP_NAME);
        $deep_phone = $options->get_option(self::DEEP_PHONE);
        $deep_fax = $options->get_option(self::DEEP_FAX);
        $deep_email = $options->get_option(self::DEEP_EMAIL);
        $deep_address = $options->get_option(self::DEEP_ADDRESS);

        $this->assign('deep_name',$deep_name);
        $this->assign('deep_phone',$deep_phone);
        $this->assign('deep_fax',$deep_fax);
        $this->assign('deep_email',$deep_email);
        $this->assign('deep_address',$deep_address);

        $this->display();

        if(IS_POST){
            $options = D('options');
            $deep = I('post.deep');
            $options->set_option(self::DEEP_NAME,$deep['name']);
            $options->set_option(self::DEEP_PHONE,$deep['phone']);
            $options->set_option(self::DEEP_FAX,$deep['fax']);
            $options->set_option(self::DEEP_EMAIL,$deep['email']);
            $options->set_option(self::DEEP_ADDRESS,$deep['address']);
        }
    }
    /*
     * 商城介绍
     */
    public function  introduceAction()
    {

        $options = D('options');
        $deep_summary = $options->get_option(self::DEEP_SUMMARY);
        $deep_content = $options->get_option(self::DEEP_CONTENT);
        $this->assign('deep_summary',$deep_summary);
        $this->assign('deep_content',$deep_content);

        $this->display();

        if(IS_POST){

            $options = D('options');
            $deep = I('post.deep','',false);

            $options->set_option(self::DEEP_SUMMARY,$deep['summary']);
            $options->set_option(self::DEEP_CONTENT,$deep['content']);
        }
    }
    /*
     * logo
     */
    public  function  logoAction()
    {
        $options = D('options');
        $deep_logo = $options->get_option(self::DEEP_LOGO);
        $this->assign('deep_logo',$deep_logo);
        $this->display();

        if(IS_POST){

            $upload = new \Think\Upload();// 实例化上传类
            $upload->autoSub = false;// 关闭子目录
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      './Public/Uploads/base/'; // 设置附件上传根目录
            $upload->saveName = time().'_'.mt_rand();
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['my_image']);
            if($info){
                $logo_image =  $info['savename'];
            }else{
                $logo_image = 'deep_shop.jpg';
            }

            $options = D('options');
            $options->set_option(self::DEEP_LOGO,$logo_image);
        }
    }
    /*
     * 服务条款
     */
    public function serviceAction()
    {
        $options = D('options');
        $deep_service = $options->get_option(self::DEEP_SERVICE);
        $this->assign('deep_service',$deep_service);
        $this->display();

        if(IS_POST){
            $options = D('options');
            $deep = I('post.deep','',false);
            $options->set_option(self::DEEP_SERVICE,$deep['service']);
        }
    }
}