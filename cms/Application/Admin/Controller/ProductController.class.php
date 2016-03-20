<?php
namespace Admin\Controller;
use Think\Controller;
class ProductController extends AdminController {
    public function indexAction(){
        $data =  D('product')->get_product_list();
        $this->assign('data',$data);
        $this->display();
    }
    public function addAction()
    {
        if (IS_POST){
            $product = D('product');
            $data = I('post.product');

            $upload = new \Think\Upload();// 实例化上传类
            $upload->autoSub = false;// 关闭子目录
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      './Public/Upload/product/'; // 设置附件上传根目录
            $upload->saveName = time().'_'.mt_rand();
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['my-file']);

            $data['create_time'] = time();
            $data['default_image'] = $info['savename'];

            $product->create($data);
            if($product->add()){
                $this->redirect('Admin/Product/index');
            }
        }

        $terms_list = D('terms')->get_terms_list();
        $this->assign('terms_list',$terms_list);
        $this->display();
    }
    public function  delAction(){
        $product_id = I('get.product_id');
        if($product_id){
            $res = D('product')->delete($product_id);
            if($res){
                $this->redirect('Admin/Product/index');
            }

        }
    }
}