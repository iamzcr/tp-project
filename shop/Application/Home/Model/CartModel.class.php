<?php
/**
 * Created by PhpStorm.
 * User: mixmedia
 * Date: 15-9-7
 * Time: 下午12:22
 */
namespace Home\Model;
use Think\Model;
class CartModel extends Model {
      public function  get_cart_list($session_id)
      {
          $map['session_id'] = $session_id;
          return $this->where( $map )->select();

      }
}