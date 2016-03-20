<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-12-5
 * Time: 上午6:25
 */
namespace Admin\Model;
use Think\Model;
class ProductModel extends Model {
    function get_product_list()
    {
        return $this->select();
    }
}