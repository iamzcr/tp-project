<?php
namespace Admin\Model;
use Think\Model;

/**
 * 用户基础模型
 */
class MaterialModel extends Model {
    function get_material_list()
    {
        return $this->select();
    }
}