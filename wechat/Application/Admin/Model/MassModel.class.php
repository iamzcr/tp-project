<?php
namespace Admin\Model;
use Think\Model;

/**
 * 用户基础模型
 */
class MassModel extends Model {
    function get_mass_list()
    {
        return $this->select();
    }
}