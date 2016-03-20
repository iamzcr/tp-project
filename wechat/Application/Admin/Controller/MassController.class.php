<?php
/**
 * 微信群发控制器
 */
namespace Admin\Controller;
use Think\Controller;
class MassController extends AdminController {
    public $group_id;
    public function _initialize(){
        parent::_initialize();

        $group = D('group');
        $this->group_id = $group->get_group_list();

    }
    public function indexAction(){
        $mass = D('mass');
        $data = $mass->get_mass_list();
        $this->assign('data',$data);
        $this->display();
    }
    public function textAction(){
        if(IS_AJAX){

            $data  = I('post.text');
            $params['filter']['is_to_all'] = false;
            $params['filter']['group_id'] = $data['group_id'];
            $params['text']['content'] = $data['content'];
            $params['msgtype'] = 'text';

            $wechat = new \Wx_WechatJSON;
            $res = $wechat->call('/message/mass/sendall',$params,\Wx_WechatJSON::JSON);

            if($res){
                $mass = D('mass');
                $res['create_time'] = time();
                $res['type'] = 'text';
                $mass->create($res);
                if($mass->add()){
                    $data = array('status'=>1,'info'=>'群发成功','url'=>U('Admin/Mass/index'));
                    $this->ajaxReturn($data);
                }
            }
            $data = array('status'=>0,'info'=>'群发失败','url'=>U('Admin/Mass/index'));
            $this->ajaxReturn($data);
        }
        $this->assign('data', $this->group_id);
        $this->display();
    }
    public function imageAction(){
        $this->display();
    }
    public function videoAction(){
        $this->display();
    }
    public function voiceAction(){
        $this->display();
    }
    public function newsAction(){
        $this->display();
    }
}