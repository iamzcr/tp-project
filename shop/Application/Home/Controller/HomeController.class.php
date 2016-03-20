<?php
namespace Home\Controller;
use Think\Controller;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class HomeController extends Controller {
    public $session_id;
    /* 空操作，用于输出404页面 */
    public function _empty(){
        $this->redirect('Index/index');
    }
    //初始化操作
    function _initialize() {

        if(!isset($this->session_id)){
            $this->session_id = session_id();
        }

        $banner = D('banner');
        $banner_data = $banner->get_banner_by_type(2);
        $this->assign('banner_data',$banner_data);

        $banner_product= $banner->get_banner_by_type(1);
        $this->assign('banner_product',$banner_product);

        //左侧品牌
        $brand = D('brand');
        $brand_list = $brand->get_brand_list();

        //左侧分类
        $category = D('category');
        $category_list = $category->get_category_list();

        //左侧标签
        $tag = D('tag');
        $tag_list = $tag->get_tag_list();

        $this->assign('brand',$brand_list);
        $this->assign('tag',$tag_list);
        $this->assign('category',$category_list);
    }

}
