<?php
/**
 * Created by PhpStorm.
 * User: mixmedia
 * Date: 15-9-9
 * Time: 下午2:27
 */
namespace Home\Controller;
class CouponController extends HomeController {
    public function indexAction()
    {
        $coupon = D('coupon');
        $coupon_list = $coupon->get_coupon_list();
        $this->assign('coupon_list',$coupon_list);
        $this->display();
    }
    public function addAction()
    {
        $this->display();

        if(IS_POST){
            $postData = I('post.coupon');
            $postData['create_time'] = time();
            $postData['type'] = 0;

            $coupon = M('coupon');
            $coupon->create($postData);

            $res = $coupon->add();
            if($res){
                $this->success('添加成功',U('Home/Coupon/index'));
            }else{
                $this->error('添加失败',U('Home/Coupon/index'));
            }
        }
    }
    public  function  deleteAction()
    {
        $coupon_id = I('get.coupon_id');

        $coupon = M('coupon');
        $res = $coupon->delete($coupon_id);
        if($res){
            $this->success('删除成功',U('Home/Coupon/index'));
        }else{
            $this->error('删除失败',U('Home/Coupon/index'));
        }
    }
}