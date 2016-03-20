<?php
/**
 * 前台产品控制器
 */
namespace Home\Controller;

class ProductController extends HomeController {
    /*
     * 获取产品列表
     */
    public function indexAction(){

        //所有产品
        $product = D('product');
        $product_list = $product->get_product_list();

        $this->assign('product_list',$product_list);

        $this->display();
    }
    /*
     * 获取产品详情
     */
    public function detailAction()
    {
        $product_id = I('get.product_id');

        $product = D('product');
        $detail = $product->get_one_product_by_id($product_id);

        //推荐产品
        $recommend_list = $product->get_recommend_product_list();
        $this->assign('recommend_list',$recommend_list);


        $this->assign('detail',$detail);
        $this->display();
    }
    /*
     * 获取标签对应的产品
     */
    public function tagAction()
    {
        $tag_id  = I('get.tag_id');
        $product = D('product');
        $data = $product->get_tag_category_brand_product($tag_id,'tag');
        $this->assign('product_list',$data['product']);
        $this->assign('tag_name',$data['tag_name']);
        $this->display();
    }
    /*
     * 获取分类对应的产品
     */
    public function categoryAction()
    {

        $category_id  = I('get.cate_id');
        $product = D('product');
        $data = $product->get_tag_category_brand_product($category_id,'category');
        $this->assign('product_list',$data['product']);
        $this->assign('cate_name',$data['cate_name']);
        $this->display();
    }
    /*
     * 获取品牌对应的产品
     */
    public function brandAction()
    {
        $brand_id  = I('get.brand_id');
        $product = D('product');
        $data = $product->get_tag_category_brand_product($brand_id,'brand');
        $this->assign('product_list',$data['product']);
        $this->assign('brand_name',$data['brand_name']);
        $this->display();
    }
}