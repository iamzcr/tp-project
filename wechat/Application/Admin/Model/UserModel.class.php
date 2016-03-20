<?php
namespace Admin\Model;
use Think\Model;

/**
 * 用户基础模型
 */
class UserModel extends Model {
    function get_user_list()
    {
        return $this->select();
    }
}