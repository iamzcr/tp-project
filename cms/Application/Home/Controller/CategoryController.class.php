<?php
namespace Home\Controller;
use Think\Controller;
class CategoryController extends HomeController {
    public function _initialize(){
        parent::_initialize();
    }
    public function indexAction(){
        $article = D('article');
        $category_id = I("get.category_id");
        $article_list = $article->get_article_by_category($category_id);
        $this->assign('article_list',$article_list);
        $this->display();
    }
}