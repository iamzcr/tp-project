<?php
namespace Admin\Controller;
use Think\Controller;
class ArticleController extends AdminController {
    public function indexAction(){
        $data =  D('article')->get_article_list();
        $this->assign('data',$data);
        $this->display();
    }
    public function addAction()
    {
        if (IS_POST){
            $article = D('article');
            $data = I('post.article');

            $upload = new \Think\Upload();// 实例化上传类
            $upload->autoSub = false;// 关闭子目录
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      './Public/Upload/article/'; // 设置附件上传根目录
            $upload->saveName = time().'_'.mt_rand();
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['my-file']);

            $data['create_time'] = time();
            $data['default_image'] = $info['savename'];

            $article->create($data);
            if($article->add()){
                $this->redirect('Admin/Article/index');
            }
        }

        $category_list = D('category')->get_category_list();
        $this->assign('category_list',$category_list);
        $this->display();
    }
    public function  delAction(){
        $article_id = I('get.article_id');
        if($article_id){
            $res = D('article')->delete($article_id);
            if($res){
                $this->redirect('Admin/Article/index');
            }

        }
    }
}