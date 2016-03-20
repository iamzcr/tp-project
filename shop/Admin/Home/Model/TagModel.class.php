<?php
/**
 * Created by PhpStorm.
 * User: mixmedia
 * Date: 15-9-7
 * Time: 下午12:22
 */
namespace Home\Model;
use Think\Model;
class TagModel extends Model {

    public function get_tag_list()
    {
        $data = $this->select();
        return $data;
    }
}