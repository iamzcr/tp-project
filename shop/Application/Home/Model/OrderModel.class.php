<?php
/**
 * Created by PhpStorm.
 * User: mixmedia
 * Date: 15-9-7
 * Time: 下午12:22
 */
namespace Home\Model;
use Think\Model;
class OrderModel extends Model {
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
    public function get_one_order($id)
    {
       return  $this->where('order_id = %d',$id)->find();
    }
}