<?php
/**
 * 微信菜单
 */
namespace Admin\Controller;
use Think\Controller;
class MenuController extends AdminController {
    public function indexAction(){
        $menu = D('menu');
        $data = $menu->get_menu_list();


        $this->assign('data',$data);
        $this->display();
    }

    /**
     * 添加菜单
     */
    public function  addAction()
    {
        if(IS_AJAX){
            $data = I('post.menu');
            $data['create_time'] = time();
            $menu = D('menu');
            $menu->create($data);
            $res = $menu->add();
            if($res){
                $data = array('status'=>1,'info'=>'新增成功','url'=>U('Admin/Menu/index'));
                $this->ajaxReturn($data);
            }else{
                $data = array('status'=>0,'info'=>'新增失败','url'=>U('Admin/Menu/index'));
                $this->ajaxReturn($data);
            }
        }
        $this->display();
    }
    /**
     * 添加子菜单
     */
    public function subAction()
    {
        if(IS_AJAX){
            $data = I('post.menu');
            $data['create_time'] = time();
            $menu = D('menu');
            $menu->create($data);
            $res = $menu->add();
            if($res){
                $data = array('status'=>1,'info'=>'新增成功','url'=>U('Admin/Menu/index'));
                $this->ajaxReturn($data);
            }else{
                $data = array('status'=>0,'info'=>'新增失败','url'=>U('Admin/Menu/index'));
                $this->ajaxReturn($data);
            }
        }
        $menu_id = I('get.menu_id');
        $menu = D('menu');
        $res = $menu->find($menu_id);
        $this->assign('data',$res);
        $this->display();
    }
    /**
     * 菜单同步到微信
     */
    public function  syscAction()
    {
        if(IS_AJAX){
            $menu = D('menu');
            $menu_list = $menu->get_wechat_menu_list();
            $wechat = new \Wx_WechatJSON;
            $res = $wechat->call('/menu/create', $menu_list, \Wx_WechatJSON::JSON);
            if($res['errcode'] == 0){
                $data = array('status'=>1,'info'=>'同步成功','url'=>U('Admin/Menu/index'));
                $this->ajaxReturn($data);
            }else{
                $data = array('status'=>1,'info'=>'同步失败','url'=>U('Admin/Menu/index'));
                $this->ajaxReturn($data);
            }
        }
    }

    /**
     * 删除微信菜单
     */
    public  function  deleteAction()
    {
        $menu_id = I('get.menu_id');

        $menu = M('menu');
        $res = $menu->delete($menu_id);
        if($res){
            $this->redirect('Admin/Menu/index');
        }else{
            $this->redirect('Admin/Menu/index');
        }
    }
}