<?php
/**
 * Created by PhpStorm.
 * User: mixmedia
 * Date: 15-9-7
 * Time: 下午12:22
 */
namespace Home\Model;
use Think\Model;
class CategoryModel extends Model {

    public function get_category_list()
    {
        $map['if_show'] = 1;
        $map['parent'] = 0;
        $data = $this->where( $map )->limit(5)->select();
        return $data;
    }
}