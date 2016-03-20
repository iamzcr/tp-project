<?php
namespace Home\Controller;
class SearchController extends HomeController {
    public function indexAction()
    {
       if(IS_POST){
           $params = I('post.keyword');
           $product = D('product');

           $product_list = $product->get_search_product($params);
           $this->assign('product_list',$product_list);
           $this->display();
       }else{
           $this->redirect('Index/index');
       }
    }
}