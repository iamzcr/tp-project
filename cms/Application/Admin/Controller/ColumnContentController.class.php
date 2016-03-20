<?php
namespace Admin\Controller;
use Think\Controller;
class ColumnContentController extends AdminController {
    public function editAction(){

        if (IS_AJAX){
            $column_content = M('column_content');
            $data = I('post.content');
            $res = $column_content->where("column_id = '%d'",$data['column_id'])->find();
            if($res){
                $column_content->where("column_id = '%d'",$res['column_id'])->save($data);
                $record = array('status'=>1,'info'=>'添加成功','url'=>U('Admin/Column/index'));
                $this->ajaxReturn($record);
            }else{
                $column_content->create($data);
                if($column_content->add()){
                    $record = array('status'=>1,'info'=>'添加成功','url'=>U('Admin/Column/index'));
                    $this->ajaxReturn($record);
                }
            }
        }


        $record = array();
        $column_content = D('column_content');
        $column_id = I('get.column_id');
        $record = $column_content->where("column_id = '%d'",$column_id)->find();
        if(!$record){
            $record['column_id'] = $column_id;
            $record['title'] = '';
            $record['summary'] = '';
            $record['content'] = '';
        }
        $this->assign('record',$record);
        $this->display();
    }
}