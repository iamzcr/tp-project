<?php
namespace Home\Controller;
class OrderController extends HomeController {
    const
        PAYMENT_TYPE_NEW = 0,
        PAYMENT_TYPE_PAYPAL = 1,
        PAYMENT_TYPE_ASIAPAY = 2,
        PAYMENT_TYPE_JETCO = 3,
        PAYMENT_TYPE_ALIPAY = 4,
        PAYMENT_TYPE_WECHATPAY = 5,


        PAYMENT_STATUS_NEW = 0,
        PAYMENT_STATUS_PAY = 1,


        STATUS_NEW = 0,
        STATUS_WAIT_FOR_PAY = 1,
        STATUS_PAY = 2,
        STATUS_DELIVER = 3,
        STATUS_FINISH = 4,
        STATUS_CANCEL = 5;

    public $user_id;
    function _initialize() {
        parent::_initialize();
        $this->user_id = session('user_id');
        if(!isset($this->user_id)){
            $this->redirect('User/login');
        }
    }
    public function indexAction(){
        $cart_amount = 0.00;
        $amount = 0.00;
        $shipping_free = 0.00;
        $coupon = 0.00;

        //购物车信息
        if($this->session_id){
            $cart = D('cart');
            $data = $cart->get_cart_list($this->session_id);
            if(empty($data)){
                $this->redirect('Product/index');
            }
        }
        $this->assign('cart_list',$data);

        //用户地址信息
        $shipping = D('shipping');
        $shipping_data = $shipping->get_shipping_list($this->user_id);
        if(!isset($shipping_data)){
            $shipping_data = '';
        }
        $this->assign('shipping_list',$shipping_data);

        //计算总额
        foreach($data as $k => $v){
            $cart_amount += $v['price'];
        }
        $amount = $cart_amount - $shipping_free - $coupon;

        $this->assign('amount',$amount);
        $this->assign('cart_amount',$cart_amount);
        $this->assign('shipping_free',$shipping_free);
        $this->assign('coupon',$coupon);

        $this->display();
    }
    public function buildAction()
    {

        $order_data = array(
            'amount' => '',
            'user_id' => '',
            'payment_status' => self::PAYMENT_STATUS_NEW,
            'payment_type' => self::PAYMENT_TYPE_ASIAPAY,
            'status' => self::STATUS_NEW,
            'invoice_number' => '',
        );

        //用户地址信息
        $shipping = D('shipping');
        $shipping_data = $shipping->get_one_shipping($this->user_id);
        if(!$shipping_data){
            $this->error('没有默认地址');
        }

        $order_data['shipping_id'] = $shipping_data['shipping_id'];

        $cart = D('cart');
        $cart_data = $cart->get_cart_list($this->session_id);
        if(empty($cart_data)){
            $this->error('你的购物车为空',U('Product/index'));
        }

        foreach ($cart_data as $k => $v) {
            $order_data['amount'] += $v['price'];
        }
        $order_data['user_id'] = $this->user_id;
        //流水号
        $order_data['invoice_number'] = $this->get_invoice_number();
        //运费
        $order_data['shipping_fee'] = 0.00;
        $order_data['create_time'] = time();
        $order_data['update_time'] = time();

        $order = D('order');

        $order->create($order_data);
        $res = $order->add();

        if($res){
            $recode = $order->get_one_order($res);

            //更新订单详情表
            $detail = array();
            $cart_list = $cart->get_cart_list($this->session_id);
            foreach($cart_list as $k => $v){
                $order_detail = D('order_detail');

                $detail[$k]['order_id'] = $recode['order_id'];
                $detail[$k]['product_id'] = $v['product_id'];
                $detail[$k]['product_name'] = $v['product_name'];
                $detail[$k]['quantity'] = $v['quantity'];
                $detail[$k]['price'] = $v['price'];
                $detail[$k]['image'] = $v['image'];
                $order_detail->create($detail);
                $row = $order_detail->add();
                if($row){
                    //提交订单后，清空购物车
                    $cart->where("session_id = '%s'",$this->session_id)->delete();

                    //session记录订单流水号和总金额，用作付款
                    session('amount', $recode['amount']);
                    session('invoice_number', $recode['invoice_number']);
                    $this->redirect('Pay/alipay');
                }
            }
        }else{
            $this->error('订单提交错误',U('Product/index'));
        }
    }

    //获取流水号
    public function  get_invoice_number()
    {
        return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }
}