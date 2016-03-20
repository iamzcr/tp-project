<?php
/**
 * 微信素材
 */
namespace Admin\Controller;
use Think\Controller;
class MaterialController extends AdminController {
    const
        VOICE = 'voice_count',
        VIDEO = 'video_count',
        IMAGE = 'image_count',
        NEWS = 'news_count';

    public function indexAction()
    {
        $option = D('options');

        $voice_count = $option->get_option(self::VOICE);
        $video_count = $option->get_option(self::VIDEO);
        $image_count = $option->get_option(self::IMAGE);
        $news_count = $option->get_option(self::NEWS);

        $this->assign('voice_count',$voice_count);
        $this->assign('video_count',$video_count);
        $this->assign('image_count',$image_count);
        $this->assign('news_count',$news_count);

        $material = D('material');
        $data = $material->get_material_list();
        $this->assign('data',$data);
        $this->display();
    }
    public function sysAction()
    {
        $type = I('get.type');
        $params = array('type'=>$type, "offset"=>0,"count"=>20);
        $wechat = new \Wx_WechatJSON;
        $res = $wechat->call('/material/batchget_material',$params,\Wx_WechatJSON::JSON);
        if($res){
            $material = D('material');
            $data = array();
            if($type == 'news'){
                foreach($res['item'] as $v){
                    $where['media_id'] = $v['media_id'];
                    $record = $material->where($where)->find();
                    if(!$record){
                        $data['media_id'] =  $v['media_id'];
                        $data['create_time'] =  $v['update_time'];
                        $data['url'] =  json_encode($v['content']);
                        $data['confine'] = 'permanent';
                        $data['type'] = $type;
                        $material->create($data);
                        $material->add();
                    }
                }
            }else{
                foreach($res['item'] as $v){
                    $where['media_id'] = $v['media_id'];
                    $record = $material->where($where)->find();
                    if(!$record){
                        $data['media_id'] =  $v['media_id'];
                        $data['create_time'] =  $v['update_time'];
                        $data['url'] =  $v['url'];
                        $data['confine'] = 'permanent';
                        $data['type'] = $type;
                        $material->create($data);
                        $material->add();
                    }
                }
            }
            $this->redirect('Material/index');
        }
    }
    public function countAction()
    {
        $wechat = new \Wx_WechatJSON;
        $res = $wechat->call('/material/get_materialcount');

        if($res){
            $options = D('options');
            $options->set_option(self::VOICE,$res['voice_count']);
            $options->set_option(self::VIDEO,$res['video_count']);
            $options->set_option(self::IMAGE,$res['image_count']);
            $options->set_option(self::NEWS,$res['news_count']);
            $this->redirect('Material/index');
        }
    }
    public function imageAction()
    {
        if(IS_POST){
            $wechat = new \Wx_WechatJSON;
            $confine = I('post.confine');
            $type = I('post.type');
            $upload = new \Think\Upload();// 实例化上传类
            $upload->autoSub = false;// 关闭子目录
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      './Public/Upload/media/'; // 设置附件上传根目录
            $upload->saveName = time().'_'.mt_rand();
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['my-file']);

            if($info){
                $filePath = DOC_ROOT.'/Public/Upload/media/'.$info['savename'];
                $res = $wechat->materialUpload($filePath,$type,$confine, array());
                if ($res) {
                    $material = D('material');
                    $material_data['confine'] = $confine;
                    $material_data['type'] = $type;
                    $material_data['media_id'] = $res['media_id'];
                    $material_data['url'] = $res['url'];
                    $material_data['create_time'] = time();
                    $material->create($material_data);
                    if($material->add()){
                        $uploads = D('uploads');
                        $uploads_data['path'] = $info['savename'];
                        $uploads_data['type'] = $info['ext'];
                        $uploads_data['create_time'] = time();
                        $uploads_data['media_id'] = $res['media_id'];
                        $uploads->create($uploads_data);
                        $uploads->add();
                        $this->redirect('Material/index');
                    }

                }else{
                    $this->redirect('Material/video');
                }
            }
        }
        $this->display();
    }
    public function newsAction()
    {

        $this->display();
    }
    public function thumbAction()
    {

        $this->display();
    }
    public function voiceAction()
    {

        $this->display();
    }
    public function videoAction()
    {
        if(IS_POST){
            $wechat = new \Wechat();
            $data = I('post.confine');

            $upload = new \Think\Upload();// 实例化上传类
            $upload->autoSub = false;// 关闭子目录
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
            $upload->rootPath  =      './Public/Upload/media/'; // 设置附件上传根目录
            $upload->saveName = time().'_'.mt_rand();
            // 上传单个文件
            $info   =   $upload->uploadOne($_FILES['my-file']);
            if($info){
                $filePath = __ROOT__.'/Public/Upload/media/'.$info['savename'];
                $res = $wechat->materialUpload($filePath,'video','permanent', array());
                if ($res) {
                    $this->redirect('Material/index');
                }else{
                    $this->redirect('Material/index');
                }
            }
        }
        $this->display();
    }
    public function deleteAction()
    {
        $id = I('get.id');

        $material = D('material');
        $record = $material->where('id = %d',$id)->find();
        if($record){
            $wechat = new \Wx_WechatJSON;
            $params['media_id'] = $record['media_id'];
            $res = $wechat->call('/material/del_material',$params,\Wx_WechatJSON::JSON);
            if($res['errcode'] == 0){
                $material->delete($id);
            }
        }
        $this->redirect('Material/index');
    }
}