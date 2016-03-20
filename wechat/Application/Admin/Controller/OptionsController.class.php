<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-10-11
 * Time: 上午7:04
 */
namespace Admin\Controller;
use Think\Controller;
class OptionsController extends AdminController {


    const
        TOKEN = 'token',
        APPID = 'appid',
        APPSECTET = 'appsecret',
        APPKEY = 'appkey';


    public function wechatAction()
    {

        if(IS_AJAX){
            $options = D('options');
            $data = I('post.wechat');
            $options->set_option(self::TOKEN,$data['token']);
            $options->set_option(self::APPID,$data['appid']);
            $options->set_option(self::APPSECTET,$data['appsecret']);
            $options->set_option(self::APPKEY,$data['appkey']);

            $data = array('status'=>1,'info'=>'设置成功','url'=>U('Admin/Options/wechat'));
            $this->ajaxReturn($data);
        }

        $option = D('options');

        $token = $option->get_option(self::TOKEN);
        $appid = $option->get_option(self::APPID);
        $appsecret = $option->get_option(self::APPSECTET);
        $appkey = $option->get_option(self::APPKEY);

        $this->assign('token',$token);
        $this->assign('appid',$appid);
        $this->assign('appsecret',$appsecret);
        $this->assign('appkey',$appkey);

        $this->display();
    }

}