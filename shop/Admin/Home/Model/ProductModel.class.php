<?php
/**
 * Created by PhpStorm.
 * User: mixmedia
 * Date: 15-9-7
 * Time: 下午12:22
 */
namespace Home\Model;
use Think\Model;
class ProductModel extends Model {

    public function get_product_list()
    {
        $map['if_show'] = 1;

        $data = $this->where( $map )->select();
        return $data;
    }
}