<?php
namespace Home\Controller;
class UserController extends HomeController {
    public function indexAction()
    {
        $user = D('user');

        $data = $user->get_user_list();
        $this->assign('data',$data);
        $this->display();
    }
    public  function  deleteAction()
    {
        $user_id = I('get.user_id');

        $user = M('user');
        $res = $user->delete($user_id);
        if($res){
            $this->success('删除成功',U('Home/User/index'));
        }else{
            $this->error('删除失败',U('Home/User/index'));
        }
    }



}