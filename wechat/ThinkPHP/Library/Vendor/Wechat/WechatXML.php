<?php
class Wx_WechatXML{

    /**
     * 调试模式，将错误通过文本消息回复显示
     * @var boolean
     */
    private $debug;

    /**
     * 以数组的形式保存微信服务器每次发来的请求
     * @var array
     */
    private $request;

    protected $msgCrypt, $timeStamp, $nonce;

    /**
     * 初始化，判断此次请求是否为验证请求，并以数组形式保存
     * @param string $token 验证信息 因加密改动改为数组
     * @param boolean $debug 调试模式，默认为关闭
     */
    public function __construct(Array $params, $debug = FALSE)
    {
        if (!$this->validateSignature($params['token'])) {
            exit('签名验证失败');
        }

        if ($this->isValid()) {
            // 网址接入验证
            exit($_GET['echostr']);
        }

        if (!isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
            exit('缺少数据');
        }
        $this->debug = $debug;
        // 设置错误处理函数，将错误通过文本消息回复显示
        set_error_handler(array($this, 'errorHandler'));

        //encodeingAeskey
        if($this->isEncode()) {
            global $wecaht_xml;
            $wecaht_xml = $this;
            $this->msgCrypt = new Wx_WXBizMsgCrypt($params['token'], $params['aeskey'], $params['appid']);
            $this->timeStamp = $_GET['timestamp'];
            $this->nonce = $_GET['nonce'];
            $msg_sign = $_GET['msg_signature'];
            $msg = '';
            $errCode = $this->msgCrypt->decryptMsg($msg_sign, $this->timeStamp, $this->nonce, $GLOBALS['HTTP_RAW_POST_DATA'], $msg);
            if($errCode == 0) {
                $xml = $this->xml2arr(new SimpleXmlIterator($msg, null));
            } else {
                exit($errCode);
            }
        } else {
            //原明文方式
            $xml = $this->xml2arr(new SimpleXmlIterator($GLOBALS['HTTP_RAW_POST_DATA'], null));
        }
        // 将数组键名转换为小写，提高健壮性，减少因大小写不同而出现的问题
        $this->request = array_change_key_case($xml, CASE_LOWER);
    }

    /*
     * 处理POST过来的XML字符串转成数组
     */
    function xml2arr(SimpleXmlIterator $dom) {
        $arr = array();
        foreach($dom as $key=>$val)
            $arr[$key] = ($dom->hasChildren()) ?  $this->xml2arr($val)  : strval($val);
        return $arr;
    }

    /* 判断是否消息加密模式 */
    public function isEncode() {
        return isset($_GET['encrypt_type']) && $_GET['encrypt_type'] == 'aes' ? 1:0;
    }

    /*
     * 消息加密回复
     */

    public function encrypt($xml){
        $encryptMsg = '';
        $errCode = $this->msgCrypt->encryptMsg($xml, $this->timeStamp, $this->nonce, $encryptMsg);
        if ($errCode == 0) {
            return $encryptMsg;
        }
        exit($errCode);
    }

    /**
     * 判断此次请求是否为验证请求
     *
     * @return boolean
     */
    private function isValid()
    {
        return isset($_GET['echostr']);
    }

    /**
     * 验证此次请求的签名信息
     *
     * @param  string $token 验证信息
     * @return boolean
     */
    private function validateSignature($token)
    {
        if (!(isset($_GET['signature']) && isset($_GET['timestamp']) && isset($_GET['nonce']))) {
            return FALSE;
        }

        $signature = $_GET['signature'];
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $signatureArray = array($token, $timestamp, $nonce);
        sort($signatureArray, SORT_STRING);
        return sha1(implode($signatureArray)) == $signature;
    }

    /**
     * 获取本次请求中的参数，不区分大小
     *
     * @param  string $param 参数名，默认为无参
     * @return mixed
     */
    public function getRequest($param = FALSE)
    {
        if ($param === FALSE) {
            return $this->request;
        }

        $param = strtolower($param);

        if (isset($this->request[$param])) {
            return $this->request[$param];
        }

        return NULL;
    }

    /**
     * 用户关注时触发，用于子类重写
     *
     * @return void
     */
    protected function onSubscribe(){}

    /**
     * 用户取消关注时触发，用于子类重写
     *
     * @return void
     */
    protected function onUnsubscribe(){}

    /**
     * 收到文本消息时触发，用于子类重写
     *
     * @return void
     */
    protected function onText(){}

    /**
     * 收到图片消息时触发，用于子类重写
     *
     * @return void
     */
    protected function onImage(){}

    /**
     * 收到地理位置消息时触发，用于子类重写
     *
     * @return void
     */
    protected function onLocation(){}

    /**
     * 收到链接消息时触发，用于子类重写
     *
     * @return void
     */
    protected function onLink(){}

    /**
     * 收到未知类型消息时触发，用于子类重写
     *
     * @return void
     */
    protected function onUnknown(){}

    protected function onMenu() {}
    protected function onView() {}
    protected function onScan() {}
    protected function onVoice() {}
    protected function onVideo() {}
    protected function scancode_push() {}
    protected function scancode_waitmsg() {}
    protected function pic_sysphoto() {}
    protected function pic_photo_or_album() {}
    protected function pic_weixin() {}
    protected function location_select() {}
    protected function mass_send_job_finish() {}
    protected function template_send_job_finish() {}

    //卡券相关
    protected function oncard_pass_check() {}
    protected function oncard_not_pass_check() {}
    protected function user_get_card() {}
    protected function user_del_card() {}
    protected function user_consume_card() {}
    protected function user_pay_from_pay_cell() {}
    protected function user_view_card() {}
    protected function update_member_card() {}
    protected function user_enter_session_from_card() {}
    protected function card_sku_remind() {}

    /**
     * 回复文本消息
     *
     * @param  string $content  消息内容
     * @param  integer $funcFlag 默认为0，设为1时星标刚才收到的消息
     * @return void
     */
    public function responseText($content, $funcFlag = 0)
    {
        exit(new TextResponse($this->getRequest('fromusername'), $this->getRequest('tousername'), $content, $funcFlag));
    }

    /**
     * 回复图片消息
     * @param media_id
     * @param  integer $funcFlag 默认为0，设为1时星标刚才收到的消息
     * @return void
     */
    public function responseImage($media_id, $funcFlag = 0)
    {
        exit(new ImageResponse($this->getRequest('fromusername'), $this->getRequest('tousername'), $media_id, $funcFlag));
    }

    /**
     * 回复语音消息
     * @param media_id
     * @param  integer $funcFlag 默认为0，设为1时星标刚才收到的消息
     * @return void
     */
    public function responseVoice($media_id, $funcFlag = 0)
    {
        exit(new VoiceResponse($this->getRequest('fromusername'), $this->getRequest('tousername'), $media_id, $funcFlag));
    }

    /**
     * 回复视频消息
     * @param media_id title description
     * @param  integer $funcFlag 默认为0，设为1时星标刚才收到的消息
     * @return void
     */
    public function responseVideo($media_id, $title, $description, $funcFlag = 0)
    {
        exit(new VideoResponse($this->getRequest('fromusername'), $this->getRequest('tousername'), $media_id, $title, $description, $funcFlag));
    }

    /**
     * 回复音乐消息
     *
     * @param  string $title       音乐标题
     * @param  string $description 音乐描述
     * @param  string $musicUrl    音乐链接
     * @param  string $hqMusicUrl  高质量音乐链接，Wi-Fi 环境下优先使用
     * @param  integer $funcFlag    默认为0，设为1时星标刚才收到的消息
     * @return void
     */
    protected function responseMusic($title, $description, $musicUrl, $hqMusicUrl, $thumbMediaId, $funcFlag = 0)
    {
        exit(new MusicResponse($this->getRequest('fromusername'), $this->getRequest('tousername'), $title, $description, $musicUrl, $hqMusicUrl, $thumbMediaId, $funcFlag));
    }

    /**
     * 回复图文消息
     * @param  array $items    由单条图文消息类型 NewsResponseItem() 组成的数组
     * @param  integer $funcFlag 默认为0，设为1时星标刚才收到的消息
     * @return void
     */
    public function responseNews($items, $funcFlag = 0)
    {
        exit(new NewsResponse($this->getRequest('fromusername'), $this->getRequest('tousername'), $items, $funcFlag));
    }

    /**
     * 转到客服接口
     *
     */
    public function responseService($msgType, $funcFlag = 0)
    {
        exit(new ServiceResponse($this->getRequest('fromusername'), $this->getRequest('tousername'), $msgType, $funcFlag));
    }

    /**
     * 分析消息类型，并分发给对应的函数
     *
     * @return void
     */
    public function run()
    {
        switch ($this->getRequest('msgtype')) {

            case 'event':
                switch ($this->getRequest('event')) {

                    case 'subscribe':
                        $this->onSubscribe();
                        break;

                    case 'unsubscribe':
                        $this->onUnsubscribe();
                        break;

                    case 'click' :
                    case 'CLICK' :
                        $this->onMenu();
                        break;
                    case 'scan' :
                    case 'SCAN' :
                        $this->onScan();
                        break;
                    case 'view':
                    case 'VIEW':
                        $this->onView();
                        break;
                    case 'scancode_push' :
                        $this->scancode_push();
                        break;
                    case 'scancode_waitmsg' :
                        $this->scancode_waitmsg();
                        break;
                    case 'pic_sysphoto' :
                        $this->pic_sysphoto();
                        break;
                    case 'pic_photo_or_album' :
                        $this->pic_photo_or_album();
                        break;
                    case 'pic_weixin' :
                        $this->pic_weixin();
                        break;
                    case 'location_select' :
                        $this->location_select();
                        break;
                    case 'MASSSENDJOBFINISH' :
                        $this->mass_send_job_finish();
                        break;
                    case 'TEMPLATESENDJOBFINISH' :
                        $this->template_send_job_finish();
                        break;
                    case 'location':
                    case 'LOCATION':
                        $this->onLocation();
                        break;
                    case 'card_pass_check':     //卡券审核通过
                        $this->oncard_pass_check();
                        break;
                    case 'card_not_pass_check': //卡券审核不通过
                        $this->oncard_not_pass_check();
                        break;
                    case 'user_get_card': //用户卡券领取
                        $this->user_get_card();
                        break;
                    case 'user_del_card': //用户卡券删除
                        $this->user_del_card();
                        break;
                    case 'user_consume_card': //用户卡券核销
                        $this->user_consume_card();
                        break;
                    case 'user_pay_from_pay_cell': //用户买单事件
                        $this->user_pay_from_pay_cell();
                        break;
                    case 'user_view_card': //进入会员卡事件
                        $this->user_view_card();
                        break;
                    case 'update_member_card': //更新会员卡信息事件
                        $this->update_member_card();
                        break;
                    case 'user_enter_session_from_card': //从卡券进入公众号事件
                        $this->user_enter_session_from_card();
                        break;
                    case 'card_sku_remind': //最后一个领券时事件
                        $this->card_sku_remind();
                        break;
                }

                break;

            case 'text':
                $this->onText();
                break;

            case 'image':
                $this->onImage();
                break;

            case 'voice':
                $this->onVoice();
                break;

            case 'video':
                $this->onVideo();
                break;

            case 'location':
                $this->onLocation();
                break;

            case 'link':
                $this->onLink();
                break;

            default:
                $this->onUnknown();
                break;

        }
    }

    /**
     * 自定义的错误处理函数，将 PHP 错误通过文本消息回复显示
     * @param  int $level   错误代码
     * @param  string $msg  错误内容
     * @param  string $file 产生错误的文件
     * @param  int $line    产生错误的行数
     * @return void
     */
    public function errorHandler($level, $msg, $file, $line)
    {
        if (!$this->debug) {
            return;
        }

        $error_type = array(
            // E_ERROR             => 'Error',
            E_WARNING => 'Warning',
            // E_PARSE             => 'Parse Error',
            E_NOTICE => 'Notice',
            // E_CORE_ERROR        => 'Core Error',
            // E_CORE_WARNING      => 'Core Warning',
            // E_COMPILE_ERROR     => 'Compile Error',
            // E_COMPILE_WARNING   => 'Compile Warning',
            E_USER_ERROR => 'User Error',
            E_USER_WARNING => 'User Warning',
            E_USER_NOTICE => 'User Notice',
            E_STRICT => 'Strict',
            E_RECOVERABLE_ERROR => 'Recoverable Error',
            E_DEPRECATED => 'Deprecated',
            E_USER_DEPRECATED => 'User Deprecated',
        );

        $template = <<<ERR
PHP 报错啦！

%s: %s
File: %s
Line: %s
ERR;

        $this->responseText(sprintf($template,
            $error_type[$level],
            $msg,
            $file,
            $line
        ));
    }

}

/**
 * 用于回复的基本消息类型
 */
abstract class WechatResponse
{

    protected $toUserName;
    protected $fromUserName;
    protected $funcFlag;
    protected $template;

    public function __construct($toUserName, $fromUserName, $funcFlag)
    {
        $this->toUserName = $toUserName;
        $this->fromUserName = $fromUserName;
        $this->funcFlag = $funcFlag;
    }

    abstract public function __toString();

    public function sprintf() {
        $args = func_get_args();
        /**
         * @var $wecaht_xml WechatXML
         */
        global $wecaht_xml;
        if ($wecaht_xml && $wecaht_xml->isEncode()) {
            $xml = call_user_func_array('sprintf', $args);
            return $wecaht_xml->encrypt($xml);
        }
        return call_user_func_array('sprintf', $args);
    }
}

/**
 * 用于回复的文本消息类型
 */
class TextResponse extends WechatResponse
{

    protected $content;

    public function __construct($toUserName, $fromUserName, $content, $funcFlag = 0)
    {
        parent::__construct($toUserName, $fromUserName, $funcFlag);

        $this->content = $content;
        $this->template = <<<XML
<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[text]]></MsgType>
  <Content><![CDATA[%s]]></Content>
  <FuncFlag>%s</FuncFlag>
</xml>
XML;
    }

    public function __toString()
    {
        return $this->sprintf($this->template,
            $this->toUserName,
            $this->fromUserName,
            time(),
            $this->content,
            $this->funcFlag
        );
    }
}

/**
 * 用于回复的图片消息类型
 */
class ImageResponse extends WechatResponse
{
    protected $media_id;
    protected $template;

    public function __construct($toUserName, $fromUserName, $media_id, $funcFlag)
    {
        parent::__construct($toUserName, $fromUserName, $funcFlag);
        $this->media_id = $media_id;
        $this->template = <<<XML
<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[image]]></MsgType>
    <Image>
    <MediaId><![CDATA[%s]]></MediaId>
    </Image>
</xml>
XML;
    }

    public function __toString()
    {
        return $this->sprintf($this->template,
            $this->toUserName,
            $this->fromUserName,
            time(),
            $this->media_id,
            $this->funcFlag
        );
    }

}

/**
 * 用于回复的语音消息类型
 */
class VoiceResponse extends WechatResponse
{
    protected $media_id;
    protected $template;

    public function __construct($toUserName, $fromUserName, $media_id, $funcFlag)
    {
        parent::__construct($toUserName, $fromUserName, $funcFlag);
        $this->media_id = $media_id;
        $this->template = <<<XML
<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[voice]]></MsgType>
    <Voice>
    <MediaId><![CDATA[%s]]></MediaId>
    </Voice>
</xml>
XML;
    }

    public function __toString()
    {
        return $this->sprintf($this->template,
            $this->toUserName,
            $this->fromUserName,
            time(),
            $this->media_id,
            $this->funcFlag
        );
    }

}

/**
 * 用于回复的视频消息类型
 */
class VideoResponse extends WechatResponse
{
    protected $media_id;
    protected $title;
    protected $description;
    protected $template;

    public function __construct($toUserName, $fromUserName, $media_id, $title, $description, $funcFlag)
    {
        parent::__construct($toUserName, $fromUserName, $funcFlag);
        $this->title = $title;
        $this->media_id = $media_id;
        $this->description = $description;
        $this->template = <<<XML
<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[video]]></MsgType>
    <Video>
    <MediaId><![CDATA[%s]]></MediaId>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
    </Video>
</xml>
XML;
    }

    public function __toString()
    {
        return $this->sprintf($this->template,
            $this->toUserName,
            $this->fromUserName,
            time(),
            $this->media_id,
            $this->title,
            $this->description,
            $this->funcFlag
        );
    }

}

/**
 * 用于回复的音乐消息类型
 */
class MusicResponse extends WechatResponse
{

    protected $title;
    protected $description;
    protected $musicUrl;
    protected $hqMusicUrl;

    public function __construct($toUserName, $fromUserName, $title, $description, $musicUrl, $hqMusicUrl, $thumbMediaId,$funcFlag)
    {
        parent::__construct($toUserName, $fromUserName, $funcFlag);

        $this->title = $title;
        $this->description = $description;
        $this->musicUrl = $musicUrl;
        $this->hqMusicUrl = $hqMusicUrl;
        $this->thumbMediaId = $thumbMediaId;
        $this->template = <<<XML
<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[music]]></MsgType>
  <Music>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
    <MusicUrl><![CDATA[%s]]></MusicUrl>
    <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
    <ThumbMediaId><![CDATA[%s]]></ThumbMediaId>
  </Music>
  <FuncFlag>%s</FuncFlag>
</xml>
XML;
    }

    public function __toString()
    {
        return $this->sprintf($this->template,
            $this->toUserName,
            $this->fromUserName,
            time(),
            $this->title,
            $this->description,
            $this->musicUrl,
            $this->hqMusicUrl,
            $this->thumbMediaId,
            $this->funcFlag
        );
    }

}

/**
 * 用于回复的图文消息类型
 */
class NewsResponse extends WechatResponse
{

    protected $items = array();

    public function __construct($toUserName, $fromUserName, $items, $funcFlag)
    {
        parent::__construct($toUserName, $fromUserName, $funcFlag);

        $this->items = $items;
        $this->template = <<<XML
<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[news]]></MsgType>
  <ArticleCount>%s</ArticleCount>
  <Articles>
    %s
  </Articles>
  <FuncFlag>%s</FuncFlag>
</xml>
XML;
    }

    public function __toString()
    {
        return $this->sprintf($this->template,
            $this->toUserName,
            $this->fromUserName,
            time(),
            count($this->items),
            implode($this->items),
            $this->funcFlag
        );
    }

}

/**
 * 单条图文消息类型
 */
class NewsResponseItem
{

    protected $title;
    protected $description;
    protected $picUrl;
    protected $url;
    protected $template;

    public function __construct($title, $description, $picUrl, $url)
    {
        $this->title = $title;
        $this->description = $description;
        $this->picUrl = $picUrl;
        $this->url = $url;
        $this->template = <<<XML
<item>
  <Title><![CDATA[%s]]></Title>
  <Description><![CDATA[%s]]></Description>
  <PicUrl><![CDATA[%s]]></PicUrl>
  <Url><![CDATA[%s]]></Url>
</item>
XML;
    }

    public function __toString()
    {
        return sprintf($this->template,
            $this->title,
            $this->description,
            $this->picUrl,
            $this->url
        );
    }

}

/**
 * 用于回复转到客服消息类型
 */
class ServiceResponse extends WechatResponse
{

    protected $msgType;

    public function __construct($toUserName, $fromUserName, $msgType, $funcFlag = 0)
    {
        parent::__construct($toUserName, $fromUserName, $funcFlag);
        $this->msgType = $msgType;
        $this->template = <<<XML
<xml>
  <ToUserName><![CDATA[%s]]></ToUserName>
  <FromUserName><![CDATA[%s]]></FromUserName>
  <CreateTime>%s</CreateTime>
  <MsgType><![CDATA[%s]]></MsgType>
  <FuncFlag>%s</FuncFlag>
</xml>
XML;
    }

    public function __toString()
    {
        return $this->sprintf($this->template,
            $this->toUserName,
            $this->fromUserName,
            time(),
            $this->msgType,
            $this->funcFlag
        );
    }
}
