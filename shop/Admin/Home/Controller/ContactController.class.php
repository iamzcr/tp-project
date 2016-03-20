<?php
namespace Home\Controller;
class ContactController extends HomeController {

    public function indexAction()
    {
        $contact = D('contact');

        $data = $contact->get_contact_list();
        $this->assign('data',$data);
        $this->display();
    }
    public  function  deleteAction()
    {
        $id = I('get.id');

        $contact = M('contact');
        $res = $contact->delete($id);
        if($res){
            $this->success('删除成功',U('Home/Contact/index'));
        }else{
            $this->error('删除失败',U('Home/Contact/index'));
        }
    }
}