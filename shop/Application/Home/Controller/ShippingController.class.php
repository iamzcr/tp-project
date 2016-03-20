<?php
namespace Home\Controller;
class ShippingController extends HomeController {
    public $user_id;
    //初始化操作
    function _initialize() {
        parent::_initialize();
        $this->user_id = session('user_id');
        if(!isset($this->user_id)){
            $this->redirect('User/login');
        }
    }
    public function indexAction(){
            $shipping = D('shipping');
            $data = $shipping->get_shipping_list($this->user_id);
            if(!isset($data)){
                $data = '';
            }
            $this->assign('shipping_list',$data);
            $this->display();
    }

    public function addAction()
    {
        $this->display();
        if(IS_POST){
            if($this->user_id){
                $shipping = D('shipping');

                $data = $shipping->where($this->user_id)->select();
                if($data){
                    foreach($data as $k=>$v){
                        $v['if_default'] = 0;
                        $shipping->save($v);
                    }
                }
                $address = I('post.address');
                $address['create_time'] = time();
                $address['user_id'] = $this->user_id;
                $address['if_default'] = 1;

                $shipping->create($address);
                $res = $shipping->add();
                if($res){
                    $this->success('添加成功',U('Shipping/index'));
                }else{
                    $this->error('添加失败');
                }
            }
        }
    }
    public function deleteAction()
    {
        $shipping = M('shipping');

        $shipping_id = I('get.shipping_id');

        $data = $shipping->where('shipping_id = %d',$shipping_id)->find();
        if($data['if_default'] == 1){
            $this->error('不能删除默认地址',U('Shipping/index'));
        }

        $res = $shipping->delete($shipping_id);
        if($res){
            $this->success('删除成功',U('Shipping/index'));
        }else{
            $this->error('删除失败',U('Shipping/index'));
        }

    }
    public function changeAction()
    {
        $shipping_id = I('get.shipping_id');

        if($this->user_id){
            $shipping = D('shipping');

            $data = $shipping->where($this->user_id)->select();
            if($data){
                foreach($data as $k=>$v){
                    $v['if_default'] = 0;
                    $shipping->save($v);
                }
            }

            $res = $shipping-> where('shipping_id='.$shipping_id)->setField('if_default',1);
            if($res){
                $this->success('设置成功',U('Shipping/index'));
            }else{
                $this->error('设置失败',U('Shipping/index'));
            }
        }
    }
}