<?php
namespace Admin\Model;
use Think\Model;

/**
 * 用户基础模型
 */
class UploadsModel extends Model {
    function get_uploads_list()
    {
        return $this->select();
    }
}