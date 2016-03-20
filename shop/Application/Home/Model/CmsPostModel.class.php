<?php
/**
 * Created by PhpStorm.
 * User: mixmedia
 * Date: 15-9-7
 * Time: 下午12:22
 */
namespace Home\Model;
use Think\Model;
class CmsPostModel extends Model {

    public function get_cms_post_list()
    {
        $map['if_show'] = 1;
        $data = $this->limit(3)->where( $map )->select();
        return $data;
    }
    public  function  get_single_post_by_id($post_id)
    {
        $data = $this->where($post_id)->find();
        return $data;
    }
}