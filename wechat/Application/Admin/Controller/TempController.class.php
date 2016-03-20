<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-10-11
 * Time: 上午7:04
 */
namespace Admin\Controller;
use Think\Controller;
class TempController extends AdminController {
    //https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=ACCESS_TOKEN
    public function  indexAction()
    {
        $temp = D('temp');
        $data = $temp->get_temp_list();
        $this->assign('data',$data);
        $this->display();
    }
    /**
     *拉取所有模板消息id
     */
    public function sysAction()
    {
        $wechat = new \Wx_WechatJSON;
        $res = $wechat->call('/template/get_all_private_template');
        var_export($res);
    }
    public function seAction()
    {
        $wechat = new \Wx_WechatJSON;
        $params = array('template_id_short'=>'TM00016');
        $res = $wechat->call('/template/api_add_template',$params,\Wx_WechatJSON::JSON);
        var_export($res);

    }
    /**
     * 添加微信消息模板
     */
    public function addAction()
    {
        if(IS_AJAX){
            $data = I('post.temp');
            $data['create_time'] = time();
            $temp = M('temp');
            $temp->create($data);
            if($temp->add()){
                $data = array('status'=>1,'info'=>'新增成功','url'=>U('Admin/Temp/index'));
                $this->ajaxReturn($data);
            }else{
                $data = array('status'=>0,'info'=>'新增失败','url'=>U('Admin/Temp/index'));
                $this->ajaxReturn($data);
            }
        }
        $this->display();
    }

    /**
     * 删除消息模板
     */
    public  function  deleteAction()
    {
        $temp_id = I('get.temp_id');
        $temp = M('temp');
        $res = $temp->delete($temp_id);
        if($res){
            $this->redirect('Admin/Temp/index');
        }else{
            $this->redirect('Admin/Temp/index');
        }
    }
    /**
     *发送消息模板日志
     */
    public function temp_logAction()
    {
        $temp_log = D('temp_log');
        $data = $temp_log->get_temp_log_list();
        $this->assign('data',$data);
        $this->display();
    }
    /**
     * 获取行业信息
     */
    public function infoAction()
    {
        $wechat = new \Wx_WechatJSON;
        $res = $wechat->call('/template/get_industry');
        var_export($res);
    }
}