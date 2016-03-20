<?php
/**
 * Created by PhpStorm.
 * User: mixmedia
 * Date: 15-9-7
 * Time: 下午12:22
 */
namespace Home\Model;
use Think\Model;
class OptionModel extends Model {


    public function get_option($key = '', $default = '') {
        $map['option_key'] = $key;
        $res = $this->where($map)->find();
        return $res['option_value'];
    }

    public function set_option($key, $value) {
        $map['option_key'] = $key;
        $res = $this->where($map)->find();

        if($res){
            $this->where($map)->save(array(
                'option_key' => $key,
                'option_value' => $value,
                'create_time' => time(),
            ));
        }else{
            $this->add(array(
                'option_key' => $key,
                'option_value' => $value,
                'create_time' => time(),
            ));
        }
    }
}