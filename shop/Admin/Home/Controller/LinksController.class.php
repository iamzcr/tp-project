<?php
/**
 * Created by PhpStorm.
 * User: mixmedia
 * Date: 15-8-28
 * Time: 下午6:04
 */
namespace Home\Controller;
class LinksController extends HomeController {
    public function indexAction()
    {
        $links = D('links');
        $links_list = $links->get_links_list();
        $this->assign('links_list',$links_list);
        $this->display();
    }
    public function addAction()
    {
        $this->display();

        if(IS_POST){
            $postData = I('post.links');
            $postData['create_time'] = time();
            $links = M('links');
            $links->create($postData);

            $res = $links->add();
            if($res){
                $this->success('添加成功',U('Home/Links/index'));
            }else{
                $this->error('添加失败',U('Home/Links/index'));
            }
        }
    }
    public  function  deleteAction()
    {
        $id = I('get.id');

        $links = M('links');
        $res = $links->delete($id);
        if($res){
            $this->success('删除成功',U('Home/Links/index'));
        }else{
            $this->error('删除失败',U('Home/Links/index'));
        }
    }
}