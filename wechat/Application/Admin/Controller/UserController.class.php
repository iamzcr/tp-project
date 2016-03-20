<?php
namespace Admin\Controller;
use Think\Controller;
class UserController extends AdminController {
    public function indexAction(){
        $wechat_user = D('user');
        $list = $wechat_user->get_user_list();
        $this->assign('data',$list);
        $this->display();
    }
    public function  syncAction()
    {
        $wechat = new \Wx_WechatJSON;
        $wechat_user =  D('user');
        $i = 0;$res = null;
        $user_group = array();
        $params = array();
        do {
            if($res && $res['count'] == 10000) {
                $params = array('next_openid' => $res['next_openid']);
            }
            $res = $wechat->call('/user/get', $params);
            if($res) {
                $user_group[$i] = $res['data']['openid'];
                $i++;
            } else {
                break;
            }
        } while($res && $res['count'] == 10000);

        if(count($user_group)) {
                foreach ($user_group as $users) {
                    foreach ($users as $user) {
                        if($user) {
                            $data = $wechat_user->where("openid = '%s'", $user)->find();
                            if(empty($data)){
                                $user_info = $wechat->call('/user/info', array(
                                    'openid' => $user,

                                ), \Wx_WechatJSON::GET);

                                if($user_info){
                                    $wechat_user->create($user_info);
                                    $wechat_user->add();

                                }
                            }
                        }
                    }
                    $this->redirect('User/index');
                }
            $this->redirect('User/index');
        }
        $this->redirect('User/index');
    }
    /**
     * 移动用户
     */
    public function removeAction()
    {
        $w_id = I('get.w_id');
        $user = D('user');
        $detail = $user->where('w_id = %d',$w_id)->find();
        $this->assign('data',$detail);
        $this->display();
    }
    /**
     * 备注用户
     */
    public function remarkAction()
    {
        if(IS_AJAX){
            $data = I('post.user');
            $wechat = new \Wx_WechatJSON;
            $params['openid'] = $data['openid'];
            $params['remark'] = $data['nickname'];
            $res  = $wechat->call('/user/info/updateremark',$params,\Wx_WechatJSON::JSON);
            if($res['errcode'] == 0){
                $u = M("user");
                $record['openid'] = $data['openid'];
                $record['nickname'] = $data['nickname'];
                $rec = $u->where('w_id = %d',$data['w_id'])->save($record);
                $datas = array('status'=>1,'info'=>'添加成功','url'=>U('Admin/User/index'));
                $this->ajaxReturn($datas);
            }else{
                $datas = array('status'=>0,'info'=>'添加成功','url'=>U('Admin/User/index'));
                $this->ajaxReturn($datas);
            }
        }
        $w_id = I('get.w_id');
        $user = D('user');
        $detail = $user->where('w_id = %d',$w_id)->find();
        $this->assign('data',$detail);
        $this->display();
    }
}