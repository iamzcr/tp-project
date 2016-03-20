<?php
namespace Home\Controller;
use Think\Controller;
class ApiController extends HomeController {
    /**
     * 获取二维码ticket
     * @return bool
     */
    public function qrcodeAction()
    {
        $data = I('post.user_id');
        if(!$data){
            echo json_encode(array('status'=>1,'msg'=>'no params'));
            exit;
        }
        $wechat = new \Wx_WechatJSON;
        $scene = $data;
        $ticket = $wechat->GetQrCodeTicket($scene,'QR_LIMIT_SCENE');
        if($ticket){
            echo json_encode(array('status'=>0,'msg'=>$ticket));
            exit;
        }else{
            echo json_encode(array('status'=>1,'msg'=>'no ticket'));
            exit;
        }

    }
    /**
     * 发送模板消息接口
     */
     public  function templateAction()
     {
         $data = $_POST;
         if($data){
             $template = D('temp');
             $user = D('user');
             $user_detail = $user->get_user_by_shop_id($data['user_id']);
             if(!$user_detail){
                 echo json_encode(array('status'=>2,'msg'=>'not openid'));
                 exit;
             }
             $open_id  = $user_detail['openid'];
             switch($data['template']){
                 case $data['template'] == 'order_submit_success':
                     $temp_data = $template->get_order_submit_success_template($open_id,$data);
                     break;
                 case $data['template'] == 'refund_success_msg':
                     $temp_data = $template->get_refund_success_msg_template($open_id,$data);
                     break;
                 case $data['template'] == 'user_cash_msg':
                     $temp_data = $template->get_user_cash_msg_template($open_id,$data);
                     break;
                 case $data['template'] == 'op_check_code_tips':
                     $temp_data = $template->get_op_check_code_tips_template($open_id,$data);
                     break;
                 case $data['template'] == 'acount_money_change_tips':
                     $temp_data = $template->get_acount_money_change_tips_template($open_id,$data);
                     break;
                 case $data['template'] == 'good_repay_msg':
                     $temp_data = $template->get_good_repay_msg_template($open_id,$data);
                     break;
                 case $data['template'] == 'goods_refund_msg':
                     $temp_data = $template->get_goods_refund_msg_template($open_id,$data);
                     break;
                 case $data['template'] == 'sale_after_deal_msg':
                     $temp_data = $template->get_sale_after_deal_msg_template($open_id,$data);
                     break;
                 case $data['template'] == 'order_status_update':
                     $temp_data = $template->get_order_status_update_template($open_id,$data);
                     break;
                 case $data['template'] == 'order_pay_for_success_msg':
                     $temp_data = $template->get_order_pay_for_success_msg_template($open_id,$data);
                     break;
                 case $data['template'] == 'order_changer_tips':
                     $temp_data = $template->get_order_changer_tips_template($open_id,$data);
                     break;
             }
             if(!$temp_data){
                 echo json_encode(array('status'=>3,'msg'=>'not template'));
                 exit;
             }
             $wechat = new \Wx_WechatJSON;
             $res = $wechat->call('/message/template/send', $temp_data,  \Wx_WechatJSON::JSON);
             $temp_log = D('temp_log');
             if($res['msgid']){
                 $log['create_time'] = time();
                 $log['msg_type'] = $res['errmsg'];
                 $log['msg_id'] = $res['msgid'];
                 $log['openid'] = $open_id;
                 $temp_log->add($log);
                 echo json_encode(array('status'=>0,'msg'=>'success!!'));
                 exit;
             }else{
                 $log['create_time'] = time();
                 $log['msg_type'] = 'error';
                 $log['msg_id'] = 0;
                 $log['openid'] = $open_id;
                 $temp_log->add($log);
                 echo json_encode(array('status'=>1,'msg'=>'fail!!'));
                 exit;
             }
         }else{
             echo json_encode(array('error_code'=>0,'error_msg'=>'params error'));
             exit;
         }
     }
    /**
     * api授权接口
     */
    public  function  oauthAction(){
        $data = I('get.user');
        if($data){
            $this->redirect('Home/Api/bing');
        }

    }
    public function bingAction()
    {
        layout(false); // 临时关闭当前模板的布局功能
        $APPID=	C('appid');	//'wxa1cb85886aa0682b';
        $REDIRECT_URI='http://wx.yfcity.net/index.php/home/login/wechat_return';
//            $scope='snsapi_base';
        $scope='snsapi_userinfo';//需要授权
        $state = 'STATE';
        $url='https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$APPID.'&redirect_uri='.urlencode($REDIRECT_URI).'&response_type=code&scope='.$scope.'&state='.$state.'#wechat_redirect';
        header("Location:".$url);
    }
    //微信账号绑定登录
    public  function  wechat_returnAction()
    {
        $appid = 	C('appid');	//"wxa1cb85886aa0682b";
        $secret = 	C('appsecret');	//"e8a2ab04ebf48639af3cfd331ee6e707";

        $code = $_GET["code"];
        $get_token_url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$get_token_url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $res = curl_exec($ch);
        $errors = curl_error($ch);
        curl_close($ch);
        $json_obj = json_decode($res,true);
        $access_token = $json_obj['access_token'];
        $openid = $json_obj['openid'];
        $ret_url = $json_obj["ret_url"];
        session('openid',$openid);
        $user = M('user');
        $cnt = $user->where("openid = '%s'",$openid)->find();
        if( $cnt )
        {
            session('user_id',$cnt['user_id']);
            session('open_id',$openid);
            $this->success('已授权，正在登录......',U('Home/User/index'));
        }
        else //
        {
            $data['username'] = ' ';
            $data['password'] = ' ';
            $data['email'] = ' ';
            $data['create_time'] = time();
            $data['openid'] = $openid;
            $data['status'] = 1;

            $user->create($data);
            $user_id = $user->add();
            if($user_id){
                session('user_id',$user_id);
                session('open_id',$openid);
                $this->success('授权成功，正在自动登录......',U('Home/User/index'));
            }else{
                $this->success('授权失败',U('Home/Index/index'));
            }
        }
    }
}