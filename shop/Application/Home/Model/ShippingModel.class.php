<?php
/**
 * Created by PhpStorm.
 * User: mixmedia
 * Date: 15-9-7
 * Time: 下午12:22
 */
namespace Home\Model;
use Think\Model;
class ShippingModel extends Model {
    /*
    * 获取用户地址列表
    */
    public function get_shipping_list($user_id)
    {
        $map['user_id'] = $user_id;
        return $this->where($map)->select();
    }
    /*
     * 获取用户默认地址
     */
    public function get_one_shipping($user_id)
    {
        $data = $this->where('user_id = %d AND if_default = %d',$user_id,1)->find();
        if($data){
            return $data;
        }else{
            return false;
        }
    }
}