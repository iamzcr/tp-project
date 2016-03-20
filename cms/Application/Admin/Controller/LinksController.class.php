<?php
namespace Admin\Controller;
use Think\Controller;
class LinksController extends AdminController {
    public function indexAction(){
        $data =  D('links')->get_links_list();
        $this->assign('data',$data);
        $this->display();
    }
    public function addAction()
    {
        if (IS_AJAX){
            $links = D('links');
            $data = I('post.links');

            $res = $links->where("name = '%s'",$data['name'])->find();
            if($res){
                $record = array('status'=>0,'info'=>'友链已存在','url'=>U('Admin/Links/add'));
                $this->ajaxReturn($record);
            }
            $data['create_time'] = time();

            $links->create($data);
            if($links->add()){
                $record = array('status'=>1,'info'=>'添加成功','url'=>U('Admin/Links/index'));
                $this->ajaxReturn($record);
            }
        }
        $this->display();
    }
}