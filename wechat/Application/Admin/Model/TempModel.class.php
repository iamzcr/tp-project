<?php
namespace Admin\Model;
use Think\Model;

/**
 * 菜单模型
 */
class TempModel extends Model {

    public function get_temp_list()
    {
        $data = $this->select();
        return $data;
    }
}