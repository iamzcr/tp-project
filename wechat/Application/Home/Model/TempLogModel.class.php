<?php
namespace Home\Model;
use Think\Model;
/**
 * 模板模型
 */
class TempLogModel extends Model {
    public function get_temp_log_list()
    {
        $data = $this->select();
        return $data;
    }
}