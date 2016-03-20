<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-12-5
 * Time: 上午6:25
 */
namespace Admin\Model;
use Think\Model;

class ColumnModel extends Model {
    function get_column_list()
    {
        return $this->select();
    }
}