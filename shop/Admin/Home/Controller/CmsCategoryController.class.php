<?php
namespace Home\Controller;
class CmsCategoryController extends HomeController {
    public function indexAction()
    {
        $cms_category = D('cms_category');

        $data = $cms_category->get_cms_category_list();
        $this->assign('data',$data);
        $this->display();
    }
    public function addAction()
    {
        $this->display();

        if(IS_POST){
            $postData = I('post.cms');
            $postData['create_time'] = time();

            $cms_category = M('cms_category');
            $cms_category->create($postData);

            $res = $cms_category->add();
            if($res){
                $this->success('添加成功',U('Home/CmsCategory/index'));
            }else{
                $this->error('添加失败',U('Home/CmsCategory/index'));
            }
        }
    }
    public  function  deleteAction()
    {
        $id = I('get.id');

        $cms_category = M('cms_category');
        $res = $cms_category->delete($id);
        if($res){
            $this->success('删除成功',U('Home/CmsCategory/index'));
        }else{
            $this->error('删除失败',U('Home/CmsCategory/index'));
        }
    }
}