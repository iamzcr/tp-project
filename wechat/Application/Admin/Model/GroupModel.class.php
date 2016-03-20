<?php
namespace Admin\Model;
use Think\Model;

/**
 * 菜单模型
 */
class GroupModel extends Model {

    public function get_group_list()
    {
        $data = $this->select();
        return $data;
    }
}