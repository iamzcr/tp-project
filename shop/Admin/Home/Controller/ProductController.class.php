<?php
namespace Home\Controller;
class ProductController extends HomeController {
    public function indexAction()
    {
        $product = D('product');

        $data = $product->get_product_list();
        $this->assign('data',$data);
        $this->display();
    }
    public function addAction()
    {
        $category = D('category');
        $data = $category->get_category_list();

        $brand = D('brand');
        $brandData = $brand->get_brand_list();

        $tag = D('tag');
        $tagData = $tag->get_tag_list();
        $this->assign('tagData',$tagData);

        $this->assign('brandData',$brandData);
        $this->assign('data',$data);

        $this->display();

        if(IS_POST){
            $postData = I('post.product','',false);
            $postData['create_time'] = time();
            $tagData = $postData['tag_ids'];
            unset( $postData['tag_ids'] );

            $upload = new \Think\Upload();// 实例化上传类
            $upload->autoSub = false;// 关闭子目录
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      './Public/Uploads/product/'; // 设置附件上传根目录
            $upload->saveName = time().'_'.mt_rand();
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['my_image']);
            if($info){
                $postData['default_image'] =  $info['savename'];
            }else{
                $postData['default_image'] = 'deep_shop.jpg';
            }

            $product = M('product');
            $product->create($postData);

            $res = $product->add();
            if($res){
                $product_tag  = D('product_tag_relation');
                foreach($tagData as $v){
                    $tag_data = (array('product_id'=>$res,'tag_id'=>$v));
                    $product_tag->create($tag_data);
                    $product_tag->add();
                }
                $this->success('添加成功',U('Home/Product/index'));
            }else{
                $this->error('添加失败',U('Home/Product/index'));
            }
        }
    }
    public  function  deleteAction()
    {
        $product_id = I('get.product_id');

        $product = M('product');
        $res = $product->delete($product_id);
        if($res){
            $this->success('删除成功',U('Home/Product/index'));
        }else{
            $this->error('删除失败',U('Home/Product/index'));
        }
    }
}