<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-12-5
 * Time: 上午6:07
 */
namespace Admin\Controller;
class TermsController extends AdminController {
    public function indexAction(){

        $data =  D('terms')->get_terms_list();
        $this->assign('data',$data);
        $this->display();
    }
    public function addAction()
    {
        if (IS_AJAX){
            $terms = D('terms');
            $data = I('post.terms');

            $res = $terms->where("name = '%s' or slug = '%s'",$data['name'],$data['slug'])->find();
            if($res){
                $record = array('status'=>0,'info'=>'分类已存在','url'=>U('Admin/Terms/add'));
                $this->ajaxReturn($record);
            }
            $data['create_time'] = time();
            $data['num'] = 0;

            $terms->create($data);
            if($terms->add()){
                $record = array('status'=>1,'info'=>'添加成功','url'=>U('Admin/Terms/index'));
                $this->ajaxReturn($record);
            }
        }
        $terms_list = D('terms')->get_terms_list();
        $this->assign('terms_list',$terms_list);
        $this->display();
    }
    public function  delAction(){
        $terms_id = I('get.terms_id');
        if($terms_id){
            $res = D('terms')->delete($terms_id);
            if($res){
                $this->redirect('Admin/Terms/index');
            }
        }
    }
    public  function  subAction()
    {

        if (IS_AJAX){
            $terms = D('terms');
            $data = I('post.terms');

            $res = $terms->where("name = '%s' or slug = '%s'",$data['name'],$data['slug'])->find();
            if($res){
                $record = array('status'=>0,'info'=>'分类已存在','url'=>U('Admin/Terms/add'));
                $this->ajaxReturn($record);
            }
            $data['create_time'] = time();
            $data['num'] = 0;

            $terms->create($data);
            if($terms->add()){
                $record = array('status'=>1,'info'=>'添加成功','url'=>U('Admin/Terms/index'));
                $this->ajaxReturn($record);
            }
        }
        $terms_id = I('get.terms_id');
        $this->assign('terms_id',$terms_id);

        $terms_list = D('terms')->get_terms_list();
        $this->assign('terms_list',$terms_list);
        $this->display();


    }
}