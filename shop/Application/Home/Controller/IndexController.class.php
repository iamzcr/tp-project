<?php
namespace Home\Controller;
class IndexController extends HomeController {
    public function indexAction(){



        $product = D('product');
        //推荐产品
        $recommend_list = $product->get_recommend_product_list();
        //新产品
        $features_list = $product->get_features_product_list();
        //所有产品
        $product_list = $product->get_product_list();

        $this->assign('recommend_list',$recommend_list);
        $this->assign('features_list',$features_list);
        $this->assign('product_list',$product_list);


        $this->display();
    }
}