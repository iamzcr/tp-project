<?php

namespace Home\Controller;
use Think\Controller;
class OauthController extends HomeController {
    public  function  indexAction(){
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
?>