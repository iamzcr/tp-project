<?php
namespace Home\Controller;
use Think\Controller;
class ArticleController extends HomeController {
    public function _initialize(){
        parent::_initialize();
    }
    public function indexAction(){
        $article = D('Article');
        $article_id = I("get.article_id");
        $detail = $article->get_article_by_id($article_id);
        $this->assign('detail',$detail);
        $this->display();
    }
}