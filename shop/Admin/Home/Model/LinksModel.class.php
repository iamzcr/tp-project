<?php
/**
 * Created by PhpStorm.
 * User: mixmedia
 * Date: 15-9-7
 * Time: 下午12:22
 */
namespace Home\Model;
use Think\Model;
class LinksModel extends Model {
    public function get_links_list()
    {
        $data = $this->select();
        return $data;
    }
}