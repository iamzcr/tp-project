<?php
namespace Home\Controller;
class CmsPostController extends HomeController {
    public function indexAction()
    {
        $cms_post = D('cms_post');
        $post_data = $cms_post->get_cms_post_list();
        $this->assign('post_data',$post_data);
        $this->display();
    }
    public function  categoryAction()
    {

    }
    public function singleAction()
    {
        $post_id = I('get.post_id');

        $cms_post = D('cms_post');
        $single = $cms_post->get_single_post_by_id($post_id);

        $this->assign('single',$single);
        $this->display();
    }

}