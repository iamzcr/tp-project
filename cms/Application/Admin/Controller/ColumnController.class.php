<?php
namespace Admin\Controller;
use Think\Controller;
class ColumnController extends AdminController {
    public function indexAction(){
        $data =  D('column')->get_column_list();
        $this->assign('data',$data);
        $this->display();
    }
    public function addAction()
    {
        if (IS_AJAX){
            $column = D('column');

            $data = I('post.column');

            $res = $column->where("name = '%s'",$data['name'])->find();
            if($res){
                $record = array('status'=>0,'info'=>'栏目已存在','url'=>U('Admin/Column/add'));
                $this->ajaxReturn($record);
            }
            $data['create_time'] = time();

            $column->create($data);
            if($column->add()){
                $record = array('status'=>1,'info'=>'添加成功','url'=>U('Admin/Column/index'));
                $this->ajaxReturn($record);
            }
        }
        $this->display();
    }
    public function  delAction(){
        $column_id = I('get.column_id');
        if($column_id){
            $res = D('column')->delete($column_id);
            if($res){
                $this->redirect('Admin/Column/index');
            }

        }
    }
}