<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-12-5
 * Time: 上午6:07
 */
namespace Admin\Controller;
class CategoryController extends AdminController {
    public function indexAction(){

        $data =  D('category')->get_category_list();
        $this->assign('data',$data);
        $this->display();
    }
    public function addAction()
    {
        if (IS_AJAX){
            $category = D('category');
            $data = I('post.category');

            $res = $category->where("name = '%s'",$data['name'])->find();
            if($res){
                $record = array('status'=>0,'info'=>'分类已存在','url'=>U('Admin/Category/add'));
                $this->ajaxReturn($record);
            }
            $data['create_time'] = time();
            $data['num'] = 0;

            $category->create($data);
            if($category->add()){
                $record = array('status'=>1,'info'=>'添加成功','url'=>U('Admin/Category/index'));
                $this->ajaxReturn($record);
            }
        }
        $category_list = D('category')->get_category_list();
        $this->assign('category_list',$category_list);
        $this->display();
    }
    public function  delAction(){
        $category_id = I('get.category_id');
        if($category_id){
            $res = D('category')->delete($category_id);
            if($res){
                $this->redirect('Admin/Category/index');
            }
        }
    }
}