<?php
/**
 * 微信用户分组
 */
namespace Admin\Controller;
use Think\Controller;
class GroupController extends AdminController {
    public function indexAction(){
        $group = D('group');
        $data = $group->get_group_list();
        $this->assign('data',$data);
        $this->display();
    }

    /**
     * 同步分组
     */
    public function sysAction()
    {
        $wechat = new \Wx_WechatJSON;
        $group = D('group');
        $groups = $wechat->call('/groups/get');
        if ($groups) {
            foreach ($groups['groups'] as $g) {

                $id = (int)$g['id'];
                $groups_tb = $group->where('id = %d',$id)->find();
                if ( empty($groups_tb) ) {
                    $data = array(
                        'auth_id' => $this->manager_id,
                        'id' => $id,
                        'name' => $g['name'],
                        'count' => $g['count'],
                    );
                    $group->create($data);
                    $group->add();
                }
            }
            $this->redirect('Group/index');
        } else {
            $this->redirect('Group/index');
        }
    }

    /**
     * 添加分组
     */
    public function  addAction()
    {
        if(IS_AJAX){
            $data = I('post.group');
            $wechat = new \Wx_WechatJSON;
            $params['group']['name'] = $data['name'];
            $res  = $wechat->call('/groups/create',$params,\Wx_WechatJSON::JSON);
            if($res){
                $g = D('group');
                $group['count'] = 0;
                $group['id'] = $res['group']['id'];
                $group['name'] = $res['group']['name'];
                $g->create($group);
                $g->add();
                $data = array('status'=>1,'info'=>'添加成功','url'=>U('Admin/Group/index'));
                $this->ajaxReturn($data);
            }else{
                $data = array('status'=>0,'info'=>'添加成功','url'=>U('Admin/Group/index'));
                $this->ajaxReturn($data);
            }
        }
        $this->display();
    }

    /**
     * 删除分组
     */
    public function deleteAction()
    {
        $group_id = I('get.group_id');
        $g = D('group');
        $group = $g->where('group_id = %d',$group_id)->find();
        if($group){
            $wechat = new \Wx_WechatJSON;
            $params['group']['id'] = $group['id'];
            $res  = $wechat->call('/groups/delete',$params,\Wx_WechatJSON::JSON);
            if($res['errcode'] == 0){
                $g->delete($group_id);
                $this->redirect('Admin/Group/index');
            }else{
                $this->redirect('Admin/Group/index');
            }
        }
    }
    /**
     * 修改分组
     */
    public function renameAction()
    {

        if(IS_AJAX){
            $data = I('post.group');
            $wechat = new \Wx_WechatJSON;
            $params['group']['id'] = $data['id'];
            $params['group']['name'] = $data['name'];
            $res  = $wechat->call('/groups/update',$params,\Wx_WechatJSON::JSON);
            if($res['errcode'] == 0){
                $g = M("group");
                $group['name'] = $data['name'];
                $group['id'] = $data['id'];
                $record = $g->where('group_id = %d',$data['group_id'])->save($group);
                var_export($record);
                $datas = array('status'=>1,'info'=>'添加成功','url'=>U('Admin/Group/index'));
                $this->ajaxReturn($datas);
            }else{
                $datas = array('status'=>0,'info'=>'添加成功','url'=>U('Admin/Group/index'));
                $this->ajaxReturn($datas);
            }
        }
        $group_id = I('get.group_id');
        $g = D('group');
        $group = $g->where('group_id = %d',$group_id)->find();
        $this->assign('data',$group);
        $this->display();
    }
}