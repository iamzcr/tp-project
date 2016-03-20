<?php
namespace Home\Controller;
class ManagerController extends HomeController {
    public function indexAction()
    {
        $manager = D('manager');

        $data = $manager->get_manager_list();
        $this->assign('data',$data);
        $this->display();
    }

    public function addAction()
    {
        $this->display();

        if(IS_POST){
            $postData = I('post.manager');
            $postData['password'] = md5($postData['password']);
            $manager = M('manager');
            $manager->create($postData);

            $res = $manager->add();
            if($res){
                $this->success('添加成功',U('Home/Manager/index'));
            }else{
                $this->error('添加失败',U('Home/Manager/index'));
            }
        }
    }
    public  function  deleteAction()
    {
        $manager_id = I('get.manager_id');

        $manager = M('manager');
        $res = $manager->delete($manager_id);
        if($res){
            $this->success('删除成功',U('Home/Manager/index'));
        }else{
            $this->error('删除失败',U('Home/Manager/index'));
        }
    }
    public function logoutAction()
    {
        session('manager_id',null); // 删除name
        $this->success('登出成功',U('Home/Login/index'));
    }
    public function pwdAction()
    {

        $this->display();
        if(IS_POST){
            $old_password = I('post.old_password');
            $new_password = I('post.new_password');
            $manager = D('manager');
            $res = $manager->where($this->manager_id)->find();
            if($res){
                if($res['password'] == md5($old_password)){
                    $record = $manager->where($this->manager_id)->save(array('password'=>md5($new_password)));
                    if($record){
                        $this->success('修改成功',U('Home/index/index'));
                    }else{
                        $this->error('修改失败',U('Home/Manager/pwd'));
                    }
                }else{
                    $this->error('原密码不正确',U('Home/Manager/pwd'));
                }
            }else{
                $this->error('你还没登录',U('Home/Login/index'));
            }
        }
    }


}