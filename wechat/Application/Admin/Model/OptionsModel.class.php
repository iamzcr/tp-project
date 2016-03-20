<?php
namespace Admin\Model;
use Think\Model;

/**
 * 用户基础模型
 */
class OptionsModel extends Model {
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