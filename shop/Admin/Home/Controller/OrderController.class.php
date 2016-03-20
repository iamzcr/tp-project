<?php
/**
 * Created by PhpStorm.
 * User: mixmedia
 * Date: 15-8-28
 * Time: 下午6:04
 */
namespace Home\Controller;
class OrderController extends HomeController {
    public function indexAction()
    {
        $order = D('order');
        $order_list = $order->get_order_list();
        $this->assign('order_list',$order_list);
        $this->display();
    }
}