<?php
/**
 * Created by PhpStorm.
 * User: mixmedia
 * Date: 15-9-7
 * Time: 下午12:22
 */
namespace Home\Model;
use Think\Model;
class OptionsModel extends Model {

    public function get_option($key = '', $default = '') {
        $map['option_key'] = $key;
        $res = $this->where($map)->find();
        return $res['option_value'];
    }
}