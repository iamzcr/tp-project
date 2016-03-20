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
    public function  get_show_category_list() {

       return $this->where('if_show = 1')->select();

    }

}