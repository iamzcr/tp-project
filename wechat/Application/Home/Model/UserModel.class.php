<?php
namespace Home\Model;
use Think\Model;

/**
 * 用户基础模型
 */
class UserModel extends Model {
    function get_user_list()
    {
        return $this->select();
    }
    function get_user_by_shop_id($shop_id)
    {
        $where['shop_id'] = $shop_id;
        return $this->where($where)->find();
    }
}