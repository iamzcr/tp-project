<?php
namespace Home\Controller;
class CmsPostController extends HomeController {
    public function indexAction()
    {
        $cms_postt = D('cms_post');

        $data = $cms_postt->get_cms_post_list();
        $this->assign('data',$data);
        $this->display();
    }
    public function addAction()
    {
        $cms_category = D('cms_category');
        $data = $cms_category->get_cms_category_list();

        $this->assign('data',$data);

        $this->display();

        if(IS_POST){
            $postData = I('post.article','',false);
            $postData['create_time'] = time();


            $upload = new \Think\Upload();// 实例化上传类
            $upload->autoSub = false;// 关闭子目录
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      './Public/Uploads/cms/'; // 设置附件上传根目录
            $upload->saveName = time().'_'.mt_rand();
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['my_image']);
            if($info){
                $postData['image'] =  $info['savename'];
            }else{
                $postData['image'] = 'deep_shop.jpg';
            }

            $cms_post = M('cms_post');
            $cms_post->create($postData);

            $res = $cms_post->add();
            if($res){
                $this->success('添加成功',U('Home/CmsPost/index'));
            }else{
                $this->error('添加失败',U('Home/CmsPost/index'));
            }
        }
    }
    public  function  deleteAction()
    {
        $post_id= I('get.post_id');

        $cms_post = M('cms_post');
        $res = $cms_post->delete($post_id);
        if($res){
            $this->success('删除成功',U('Home/CmsPost/index'));
        }else{
            $this->error('删除失败',U('Home/CmsPost/index'));
        }
    }
}