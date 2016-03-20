<?php
namespace Admin\Model;
use Think\Model;

/**
 * 用户基础模型
 */
class ResponseModel extends Model {
    function get_response_list()
    {
        return $this->select();
    }
}