<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-12-6
 * Time: 上午3:14
 */
namespace Home\Model;
use Think\Model;

class ColumnContentModel extends Model {
    public function  get_content_list($column_id) {

        return $this->where('column_id = %d',$column_id)->find();

    }
}