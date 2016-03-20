<?php
namespace Home\Controller;
class CartController extends HomeController {

    public function indexAction()
    {

        if($this->session_id){
            $cart = D('cart');
            $data = $cart->get_cart_list($this->session_id);
        }else{
            $data = '';
        }
        $this->assign('cart_list',$data);
        $this->display();
    }
    public  function  addAction()
    {

        $data = array();
        $product_id = I('get.product_id');


        $product = D('product');
        $res = $product->get_one_product_by_id($product_id);
        if(!$res){
            $this->error('产品已下架或被删除',U('Product/index'));
        }
        if($res['stock'] <= 0){
            $this->error('缺货中',U('Product/index'));
        }
        if(session('user_id')){
            $data['user_id'] = session('user_id');
        }else{
            $data['user_id'] = 0;
        }
        $cart = M('cart');
        $update_res = $cart->where("session_id = '%s' AND product_id = %d",$this->session_id,$product_id)->find();
        if($update_res){
            $data['quantity'] = $update_res['quantity']+1;
            $up_res = $cart->where("cart_id = %d",$update_res['cart_id'])->save($data);
            if($up_res){
                $this->success('添加成功',U('Cart/index'));
            }else{
                $this->error('添加失败',U('Product/index'));
            }
        }else{
            $data['session_id'] =  $this->session_id;
            $data['product_id'] = $product_id;

            $data['product_name'] = $res['name'];
            $data['price'] = $res['price'];
            $data['quantity'] = 1;
            $data['image'] = $res['default_image'];


            $cart->create($data);
            $record = $cart->add();
            if($record){
                $this->success('添加成功',U('Cart/index'));
            }else{
                $this->error('添加失败',U('Product/index'));
            }
        }


    }
    public function  deleteAction()
    {
        $cart_id = I('get.cart_id');
        if($cart_id){
            $cart = D('cart');
            $res = $cart->delete($cart_id);
            if($res){
                $this->success('删除成功',U('Cart/index'));
            }else{
                $this->error('删除失败',U('Cart/index'));
            }

        }
    }
    public function putAction()
    {
        if(IS_AJAX){
            $product_id = I('post.product_id');
            $num = I('post.cart_count');

            $product = D('product');
            $res = $product->get_one_product_by_id($product_id);
            if(!$res){
                $data = array('status'=>0,'info'=>'产品不存在或下架');
                $this->ajaxReturn($data);
            }
            if($res['stock'] <= 0 || $res['stock'] < $num){
                $data = array('status'=>0,'info'=>'缺货中或库存不足');
                $this->ajaxReturn($data);
            }
            if(session('user_id')){
                $data['user_id'] = session('user_id');
            }else{
                $data['user_id'] = 0;
            }
            $cart = M('cart');
            $update_res = $cart->where("session_id = '%s' AND product_id = %d",$this->session_id,$product_id)->find();
            if($update_res){
                $data['quantity'] = $update_res['quantity']+$num;
                $up_res = $cart->where("cart_id = %d",$update_res['cart_id'])->save($data);
                if($up_res){
                    $data = array('status'=>1,'info'=>'添加成功','url'=>U('cart/index'));
                    $this->ajaxReturn($data);
                }else{
                    $data = array('status'=>0,'info'=>'添加失败');
                    $this->ajaxReturn($data);
                }
            }else{
                $data['session_id'] =  $this->session_id;
                $data['product_id'] = $product_id;

                $data['product_name'] = $res['name'];
                $data['price'] = $res['price'];
                $data['quantity'] = $num;
                $data['image'] = $res['default_image'];


                $cart->create($data);
                $record = $cart->add();
                if($record){
                    $data = array('status'=>1,'info'=>'添加成功','url'=>U('cart/index'));
                    $this->ajaxReturn($data);
                }else{
                    $data = array('status'=>0,'info'=>'添加失败');
                    $this->ajaxReturn($data);
                }
            }
        }
    }
    public function cutAction()
    {
        if(IS_AJAX){
            $cart_id = I('post.cart_id');
            $tag_type = I('post.tag_type');

            $cart = M('cart');
            $update_res = $cart->where("cart_id = %d",$cart_id)->find();

            $product = D('product');
            $res = $product->get_one_product_by_id($update_res['product_id']);
            if($tag_type == 'add'){
                $data['quantity'] = $update_res['quantity']+1;
                if($res['stock'] < 0 || $res['stock'] < $data['quantity']){
                    $data = array('status'=>0,'info'=>'缺货中或库存不足','url'=>U('cart/index'));
                    $this->ajaxReturn($data);
                }
                $up_res = $cart->where("cart_id = %d",$update_res['cart_id'])->save($data);
                if($up_res){
                    $data = array('status'=>1,'info'=>'操作成功','url'=>U('cart/index'));
                    $this->ajaxReturn($data);
                }else{
                    $data = array('status'=>0,'info'=>'操作失败','url'=>U('cart/index'));
                    $this->ajaxReturn($data);
                }
            }else{
                $data['quantity'] = $update_res['quantity']-1;
                $up_res = $cart->where("cart_id = %d",$update_res['cart_id'])->save($data);
                if($up_res){
                    $data = array('status'=>1,'info'=>'操作成功','url'=>U('cart/index'));
                    $this->ajaxReturn($data);
                }else{
                    $data = array('status'=>0,'info'=>'操作失败','url'=>U('cart/index'));
                    $this->ajaxReturn($data);
                }
            }
        }
    }
}
