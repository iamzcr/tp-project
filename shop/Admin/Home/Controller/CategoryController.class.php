<?php
namespace Home\Controller;
class CategoryController extends HomeController {
    public function indexAction()
    {
        $category = D('category');

        $data = $category->get_category_list();
        $this->assign('data',$data);
        $this->display();
    }
    public function addAction()
    {
        $category = D('category');

        $data = $category->get_category_list();

        $this->assign('data',$data);
        $this->display();

        if(IS_POST){
            $postData = I('post.cate');
            $postData['create_time'] = time();

            $cate = M('category');
            $cate->create($postData);

            $res = $cate->add();
            if($res){
                $this->success('添加成功',U('Home/Category/index'));
            }else{
                $this->error('添加失败',U('Home/Category/index'));
            }
        }
    }
    public  function  deleteAction()
    {
        $cate_id = I('get.cate_id');

        $category = M('category');
        $res = $category->delete($cate_id);
        if($res){
            $this->success('删除成功',U('Home/Category/index'));
        }else{
            $this->error('删除失败',U('Home/Category/index'));
        }
    }
}