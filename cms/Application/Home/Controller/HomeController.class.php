<?php
namespace Home\Controller;
use Think\Controller;
class HomeController extends Controller {
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

    public function _initialize(){

        $option = D('option');

        $deep_name = $option->get_option(self::DEEP_NAME);
        $deep_phone = $option->get_option(self::DEEP_PHONE);
        $deep_fax = $option->get_option(self::DEEP_FAX);
        $deep_email = $option->get_option(self::DEEP_EMAIL);
        $deep_address = $option->get_option(self::DEEP_ADDRESS);

        $deep_title = $option->get_option(self::DEEP_TITLE);
        $deep_keyword = $option->get_option(self::DEEP_KEYWORD);
        $deep_seo = $option->get_option(self::DEEP_SEO);

        $deep_logo = $option->get_option(self::DEEP_LOGO);


        $this->assign('deep_name',$deep_name);
        $this->assign('deep_phone',$deep_phone);
        $this->assign('deep_fax',$deep_fax);
        $this->assign('deep_email',$deep_email);
        $this->assign('deep_address',$deep_address);

        $this->assign('deep_logo',$deep_logo);


        $this->assign('deep_title',$deep_title);
        $this->assign('deep_keyword',$deep_keyword);
        $this->assign('deep_seo',$deep_seo);

        $category = D('category');
        $category_show_list = $category->get_show_category_list();

        $column = D('column');
        $column_show_list = $column->get_show_column_list();

        $this->assign('category_show_list',$category_show_list);
        $this->assign('column_show_list',$column_show_list);
    }
}