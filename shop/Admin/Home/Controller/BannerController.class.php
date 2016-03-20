<?php
namespace Home\Controller;
class BannerController extends HomeController {
    public function indexAction()
    {
        $banner = D('banner');

        $data = $banner->get_banner_list();
        $this->assign('data',$data);
        $this->display();
    }
    public function addAction()
    {
        $this->display();

        if(IS_POST){
            $bannerData = I('post.banner');
            $bannerData['create_time'] = time();

            $upload = new \Think\Upload();// 实例化上传类
            $upload->autoSub = false;// 关闭子目录
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      './Public/Uploads/banner/'; // 设置附件上传根目录
            $upload->saveName = time().'_'.mt_rand();
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['my_image']);
            if($info){
                $bannerData['images'] =  $info['savename'];
            }else{
                $bannerData['images'] = 'deep_shop.jpg';
            }

            $banner = M('banner');
            $banner->create($bannerData);

            $res = $banner->add();
            if($res){
                $this->success('添加成功',U('Home/Banner/index'));
            }else{
                $this->error('添加失败',U('Home/Banner/index'));
            }
        }
    }
    public  function  deleteAction()
    {
        $banner_id = I('get.banner_id');

        $banner = M('banner');
        $res = $banner->delete($banner_id);
        if($res){
            $this->success('删除成功',U('Home/Banner/index'));
        }else{
            $this->error('删除失败',U('Home/Banner/index'));
        }
    }
}