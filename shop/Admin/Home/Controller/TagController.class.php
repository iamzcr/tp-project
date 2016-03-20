<?php
namespace Home\Controller;
class TagController extends HomeController {
    public function indexAction()
    {
        $tag = D('tag');

        $data = $tag->get_tag_list();
        $this->assign('data',$data);
        $this->display();
    }
    public function addAction()
    {
        $this->display();

        if(IS_POST){
            $postData = I('post.tag');
            $postData['create_time'] = time();

            $tag = M('tag');
            $tag->create($postData);

            $res = $tag->add();
            if($res){
                $this->success('添加成功',U('Home/Tag/index'));
            }else{
                $this->error('添加失败',U('Home/Tag/index'));
            }
        }
    }
    public  function  deleteAction()
    {
        $tag_id = I('get.tag_id');

        $tag = M('tag');
        $res = $tag->delete($tag_id);
        if($res){
            $this->success('删除成功',U('Home/Tag/index'));
        }else{
            $this->error('删除失败',U('Home/Tag/index'));
        }
    }
}