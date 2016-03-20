<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends HomeController {

    protected
        $wx, $config;

    public function _initialize(){
        parent::_initialize();

        $this->config['appid'] = C('appid');
        $this->config['secret'] = C('appsecret');
        $this->config['token'] = C('token');
        $this->config['aeskey'] = C('aeskey');
        if(!empty($_GET['echostr'])){

            $this->valid();

        }else{

            $this->responseMsg();
        }

        exit;

    }
    public function indexAction(){
       layout(false); // 临时关闭当前模板的布局功能
       $this->display();
    }
    /**
     * 绑定url、token信息
     */
    public function valid(){
        $echoStr = $_GET["echostr"];
        if ($this->checkSignature()) {
            echo $echoStr;
        }
        exit();
    }
    /**
     * 检查签名，确保请求是从微信发过来的
     */
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = "feisudaifa";//与在微信配置的token一致，不可泄露
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 接收消息，并自动发送响应信息
     */
    public function responseMsg(){

        //验证签名
        if ($this->checkSignature()){
            $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
            $this->log_request_info();

            //提取post数据
            if (!empty($postStr)){
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;//发送人
                $toUsername = $postObj->ToUserName;//接收人
                $MsgType = $postObj->MsgType;//消息类型
                $MsgId = $postObj->MsgId;//消息id
                $time = time();//当前时间做为回复时间

                //如果是文本消息（表情属于文本信息）
                if($MsgType == 'text'){
                    $content = trim($postObj->Content);//消息内容
                    if(!empty( $content )){
                        //如果文本内容是图文，则回复图文信息，否则回复文本信息
                        if($content == "图文"){
                            //回复图文消息,ArticleCount图文消息个数,多条图文消息信息，默认第一个item为大图
                            $ArticleCount = 2;
                            $newsTpl = "<xml>
                                <ToUserName><![CDATA[%s]]></ToUserName>
                                <FromUserName><![CDATA[%s]]></FromUserName>
                                <CreateTime>%s</CreateTime>
                                <MsgType><![CDATA[%s]]></MsgType>
                                <ArticleCount>%s</ArticleCount>
                                <Articles>
                                <item>
                                <Title><![CDATA[%s]]></Title>
                                <Description><![CDATA[%s]]></Description>
                                <PicUrl><![CDATA[%s]]></PicUrl>
                                <Url><![CDATA[%s]]></Url>
                                </item>
                                <item>
                                <Title><![CDATA[%s]]></Title>
                                <Description><![CDATA[%s]]></Description>
                                <PicUrl><![CDATA[%s]]></PicUrl>
                                <Url><![CDATA[%s]]></Url>
                                </item>
                                </Articles>
                                </xml>";
                            $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, 'news',
                                $ArticleCount,'我是图文信息','我是描述信息','http://www.test.com/DocCenterService/image?photo_id=236',
                                'http://www.test.com','爱城市网正式开通上线','描述2','http://jn.test.com/ac/skins/img/upload/img/20131116/48171384568991509.png',
                                'http://www.test.com');
                            echo $resultStr;
                            $this->log($resultStr);
                        }else{
                            //回复文本信息
                            $textTpl = "<xml>
                                            <ToUserName><![CDATA[%s]]></ToUserName>
                                            <FromUserName><![CDATA[%s]]></FromUserName>
                                            <CreateTime>%s</CreateTime>
                                            <MsgType><![CDATA[%s]]></MsgType>
                                            <Content><![CDATA[%s]]></Content>
                                            <FuncFlag>0</FuncFlag>
                                            </xml>";
                            $contentStr = '你发送的信息是：接收人：'.$toUsername.',发送人:'.$fromUsername.',消息类型：'.$MsgType.',消息内容：'.$content.' www.icity365.com';
                            $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
                            echo $resultStr;
                            $this->log($resultStr);
                        }
                    }else{
                        echo  $resultStr = "Input something...";
                        $this->log($resultStr);
                    }
                    //如果是图片消息
                }else if($MsgType == 'event'){

                    $Event = $postObj->Event;

                    //subscribe(关注，也叫订阅)
                    if($Event == 'subscribe'){

                        $EventKey = $postObj->EventKey;//事件KEY值，qrscene_为前缀，后面为二维码的参数值
                        //未关注时，扫描二维码
                        if(!empty($EventKey)){
                            $Ticket = $postObj->Ticket;//二维码的ticket，可用来换取二维码图片
                            $EventKey = explode('_',$EventKey);
                            $open_id = trim($fromUsername);

                            $user = D('user');
                            $where['openid'] = $open_id;
                            $user_detail = $user->where($where)->find();
                            if(!$user_detail){
                                //微信用户
                                $user_data = array(
                                    'openid'=>$open_id,
                                    'shop_id'=>$EventKey[1],
                                    'ticket'=>$Ticket
                                );
                                $user->create($user_data);
                                $user->add();
                            }
                            $this->log($open_id.$EventKey[1]);
                        }else{
                            $this->log($fromUsername.$EventKey.'关注我了！');
                        }

                        //unsubscribe(取消关注)
                    }elseif ($Event == 'unsubscribe'){
                        $this->log($fromUsername.'取消关注我了！');
                        //已关注时，扫描二维码事件
                    }elseif($Event == 'SCAN' || $Event == 'scan'){

                        $EventKey = $postObj->EventKey;//事件KEY值，是一个32位无符号整数，即创建二维码时的二维码scene_id
                        $EventKey = explode('_',$EventKey);

                        $Ticket = $postObj->Ticket;//二维码的ticket，可用来换取二维码图片
                        $open_id = trim($fromUsername);

                        $user = D('user');
                        $where['openid'] = $fromUsername;
                        $user_detail = $user->where($where)->find();
                        if($user_detail){
                            $record['shop_id'] = $EventKey[1];
                            $record['ticket'] = $Ticket;
                            $user->where('user_id = %d',$user_detail['user_id'])->save($record);
                        }else{
                            $user_data = array(
                                'openid'=>$open_id,
                                'shop_id'=>$EventKey[1],
                                'ticket'=>$Ticket
                            );
                            $user->create($user_data);
                            $user->add();
                        }
                        //菜单点击事件
                    }elseif($Event == 'CLICK'){
                        $EventKey = $postObj->EventKey;//事件KEY值，与自定义菜单接口中KEY值对应
                        //回复文本信息
                        $textTpl = "<xml>
                                        <ToUserName><![CDATA[%s]]></ToUserName>
                                        <FromUserName><![CDATA[%s]]></FromUserName>
                                        <CreateTime>%s</CreateTime>
                                        <MsgType><![CDATA[%s]]></MsgType>
                                        <Content><![CDATA[%s]]></Content>
                                        <FuncFlag>0</FuncFlag>
                                        </xml>";
                        $contentStr = '你点击了菜单，菜单项key='.$EventKey;
                        $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
                        echo $resultStr;
                        $this->log($resultStr);

                        //其他事件类型
                    }else{
                        $this->log('事件类型：'.$Event);
                    }

                    //其他消息类型，链接、语音等
                }else{
                    //回复文本信息
                    $textTpl = "<xml>
                                    <ToUserName><![CDATA[%s]]></ToUserName>
                                    <FromUserName><![CDATA[%s]]></FromUserName>
                                    <CreateTime>%s</CreateTime>
                                    <MsgType><![CDATA[%s]]></MsgType>
                                    <Content><![CDATA[%s]]></Content>
                                    <FuncFlag>0</FuncFlag>
                                    </xml>";
                    $contentStr = '消息类型：'.$MsgType.'我们还没做处理。。。。【爱城市网】';
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, 'text', $contentStr);
                    echo $resultStr;
                    $this->log($resultStr);
                }

            }else {
                echo "";
                exit;
            }
        }else{
            $this->log("验证签名未通过！");
        }
    }
    /**
     * 记录请求信息
     */
    function log_request_info() {
        $post = '';
        foreach($_POST   as   $key   =>   $value)   {
            $post = $post.$key.' : '.$value.' , ';
        }
        $get = '';
        foreach($_GET   as   $key   =>   $value)   {
            $get = $get.$key.' : '.$value.' , ';
        }
        $this->log("get信息：".$get);
        $this->log("post信息：".$post);
    }
    /**
     * 记录日志
     * @param $str
     * @param $mode
     */
    function log($str){
        $mode='a';//追加方式写
        $file = "log.txt";
        $oldmask = @umask(0);
        $fp = @fopen($file,$mode);
        @flock($fp, 3);
        if(!$fp)
        {
            Return false;
        }
        else
        {
            @fwrite($fp,$str);
            @fclose($fp);
            @umask($oldmask);
            Return true;
        }
    }
}