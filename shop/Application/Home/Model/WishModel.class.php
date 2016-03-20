<?php
/**
 * Created by PhpStorm.
 * User: mixmedia
 * Date: 15-9-7
 * Time: 下午12:22
 */
namespace Home\Model;
use Think\Model;
class WishModel extends Model {
    public function get_my_wish($user_id)
    {

        $arr = array();
        $data = $this->where('user_id = %d',$user_id)->select();
        foreach($data as $k=>$v){
           $product_id = (int)$v['product_id'];
           $arr[$k]=  $this->get_wish_one_product($product_id);

        }
        return $arr;
    }
    public function get_wish_one_product($id)
    {
        $product =  D('product');
        $data = $product->where("product_id = %d and if_show = %d",$id,1)->find();
        return $data;
    }
}