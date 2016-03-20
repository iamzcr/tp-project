<?php
namespace Home\Controller;
class WishController extends HomeController {
    public $user_id;
    //初始化操作
    function _initialize() {
        parent::_initialize();
        $this->user_id = session('user_id');
        if(!isset($this->user_id)){
            $this->redirect('User/login');
        }
    }
    public function addAction()
    {
        $data = array();
        $product_id = I('get.product_id');
        $type = I('get.type');
        if($this->user_id){
            $wish = D('wish');
            $record = $wish->where('product_id = %d AND user_id = %d',$product_id,$this->user_id)->find();
            if($record){
                $this->error('此商品已被收藏');
            }
            $data['product_id'] = $product_id;
            $data['user_id'] = $this->user_id;
            $data['type'] = $type;
            $data['create_time'] = time();

            $wish->create($data);
            $res = $wish->add();
            if($res){
                $this->success('收藏成功');
            }else{
                $this->error('收藏失败');
            }
        }else{
            $this->error('请先登录',U('User/login/'));
        }
    }
    public function indexAction()
    {
        if($this->user_id){
            $wish = D('wish');
            $list = $wish->get_my_wish($this->user_id);
            $this->assign('list',$list);
            $this->display();
        }
    }
    public function deleteAction()
    {
        $product_id = I('get.product_id');
        $wish = D('wish');
        if($this->user_id){
            $res = $wish->where('product_id = %d AND user_id = %d ',$product_id,$this->user_id)->delete();
            if($res){
                $this->success('删除成功');
            }else{
                $this->error('删除失败');
            }
        }
    }
}