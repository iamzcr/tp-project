<?php
namespace Admin\Model;
use Think\Model;

/**
 * 菜单模型
 */
class MenuModel extends Model {

    /**
     * 获取后台菜单列表
     * @return array
     */
    public function get_menu_list()
    {
        $data = $this->select();
        $res = $this->sub_list($data);
        return $res;
    }
    public  function sub_list($sourceArr,$pid=0){
        $tempArr=array();
        foreach($sourceArr as $v){
            if($v['parent_id'] == $pid){
                $v['sub_button']= $this->sub_list($sourceArr,$v['menu_id']);
                $tempArr[]=$v;
            }
        }
        return $tempArr;
    }

    /**
     * 组装微信菜单格式
     * @return mixed
     */
    public function get_wechat_menu_list()
    {
        $data = $this->select();
        foreach($data as $k=>$v){
            if($v['parent_id'] ==  0){
                $sub = $this->get_sub_detail($v['menu_id']);
                if($sub){
                    foreach($sub as $sub_k => $sub_v){
                        $menu_data['button'][$k]['name'] = $v['name'];
                        $menu_data['button'][$k]['sub_button'][$sub_k]['name'] = $sub_v['name'];
                        $menu_data['button'][$k]['sub_button'][$sub_k]['type'] = $sub_v['type'];

                        if($sub_v['type'] == 'view'){
                            $menu_data['button'][$k]['sub_button'][$sub_k]['url'] = $sub_v['keyword'];
                        }else{
                            $menu_data['button'][$k]['sub_button'][$sub_k]['key'] = $sub_v['keyword'];
                        }
                    }
                }else{
                    $menu_data['button'][$k]['name'] = $v['name'];
                    $menu_data['button'][$k]['type'] = $v['type'];
                    if($v['type'] == 'view'){
                        $menu_data['button'][$k]['url'] = $v['keyword'];
                    }else{
                        $menu_data['button'][$k]['key'] = $v['keyword'];
                    }
                }
            }
        }

        return $menu_data;
    }
    public  function get_sub_detail($menu_id){

       return $this->where("parent_id = %d",$menu_id)->select();

    }
}