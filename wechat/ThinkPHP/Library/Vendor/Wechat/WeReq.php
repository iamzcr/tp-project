<?php

class WeReq extends Wx_WechatXML {
    const
        PARAM_TO_USER_NAME = 'gh_d1724fef1e22', //接收方帐号（该公众号ID）
        PARAM_FROM_USER_NAME = 'fromusername', //发送方帐号（代表用户的唯一标识）
        PARAM_CREATETIME = 'createtime', //消息创建时间（时间戳）
        PARAM_MSGID = 'msgid', //消息ID（64位整型）
        MSG_TYPE_EVENT = 'event', //事件消息类型
        MSG_TYPE_TEXT = 'text', //文本消息类型
        MSG_TYPE_IMAGE = 'image', //图片消息类型
        MSG_TYPE_LOCATION = 'location', //地图消息类型
        MSG_TYPE_SERVICE = 'transfer_customer_service', //客服类型
        MSG_EVENT_MASS = 'MASSSENDJOBFINISH', //群发消息类型
        MSG_TYPE_LINK = 'link', //链接消息类型
        MSG_TYPE = 'msgtype', //消息类型
        MEDIA_ID = 'mediaid', //媒体ID
        VOICE_FORMAT = 'format',
        VOICE_RECOGNITION = 'recognition',
        VIDEO_THUMBMEDIAID = 'thumbmediaid',
        PARAM_CONTENT = 'content', //文本消息内容
        PARAM_PICURL = 'picurl', //图片链接
        PARAM_LOCATION_X = 'location_x', //地理位置纬度
        PARAM_LOCATION_Y = 'location_y', //地理位置经度
        PARAM_SCALE = 'scale', //地图缩放大小
        PARAM_LABEL = 'label', //地理位置信息
        PARAM_TITLE = 'title', //消息标题
        PARAM_DESCRIPTION = 'description', // 消息描述
        PARAM_URL = 'url', //消息链接
        PARAM_EVENT_KEY = 'eventkey', // 事件 Key 值，与自定义菜单接口中 Key 值对应
        EVENT_SUBSCRIBE = 'subscribe', //关注事件
        EVENT_UNSUBSCRIBE = 'unsubscribe', // 取消关注事件
        EVENT_CLICK = 'click', // 自定义菜单点击事件（未验证）
        WECHAT_CONFIG_KEY = 'wechat', //系统配置键值
        TESTCOMPONENT_MSG_TYPE_TEXT = 'TESTCOMPONENT_MSG_TYPE_TEXT', //全网发布匹配文本值
        QUERY_AUTH_CODE = 'QUERY_AUTH_CODE'; //全网发布匹配API文本值

    protected static $api, $config, $instance;

    public $appid_flatform, $verifyticket, $authorizer_appid;

    static public function getInstance($config) {
        if (empty(self::$instance)) {
            $options = array('appid' => $config['appid'], 'token' => $config['token'], 'aeskey' => $config['aeskey'],);
            self::$config = $config;
            self::$instance = new self ($options);
        }
        return self::$instance;
    }

    public function parse() {
        $this->run();
    }

    protected function onText() {
        $params = $this->getRequest();
        $this->msgHanlder($params);
    }

    private function msgHanlder($params) {
        $toUser = $params[self::PARAM_TO_USER_NAME];
        $msgType = $params[self::MSG_TYPE];
        $where = array("tousername='{$toUser}'");
        switch($msgType) {
            case 'event' :
                switch($params['event']) {
                    case 'subscribe' :
                        $where[0] .= " and msgtype=?";
                        $where[] = 'subscribe';
                        break;
                    case 'CLICK' :
                        $where[0] .= " and msgtype=? and keyword=?";
                        $where[] = 'menu';
                        $where[] = $params[self::PARAM_EVENT_KEY];
                        //$where = array('tousername=? and msgtype=? and keyword=?', $toUser, 'menu', $params[self::PARAM_EVENT_KEY]);
                        break;
                    case 'SCAN' :
                        $where[0] .= " and msgtype=? and keyword=?";
                        $where[] = $params[self::MSG_TYPE_EVENT];
                        $where[] = $params[self::PARAM_EVENT_KEY];
                        //$where = array('tousername=? and msgtype=? and keyword=?', $toUser, $params[self::MSG_TYPE_EVENT], $params[self::PARAM_EVENT_KEY]);
                        break;
                }
                break;
            default :
                $content = $params[self::PARAM_CONTENT];
                $explode = explode(' ', $content);
                $count = count($explode);
                for($i = 0; $i < $count; $i++) {
                    $where_str .= ' or keyword like ?';
                }
                foreach($explode as $item) {
                    $where[] = '%'.$item.'%';
                }
                $where_str = substr($where_str, 3 );
                $where[0] .= " and msgtype='{$msgType}' and ({$where_str})";
        }
        $res = Response::find(Response::FIRST, array(
            'where' => $where
        ));
        if( ! $res->isNew()) {
            switch($res['msgreply']) {
                case 'text' :
                    $this->responseText($res['content']); break;
                case 'image' :
                    $this->responseImage($res['content']); break;
                case 'voice' :
                    $this->responseVoice($res['content']); break;
                case 'video' :
                    $data = (array) json_decode($res['content']);
                    $this->responseVideo($data['mediaid'], $data['title'], $data['description']); break;
                case 'music' :
                    $data = (array) json_decode($res['content']);
                    $this->responseMusic($data['title'], $data['description'], $data['musicurl'], $data['hqmusicurl'], $data['thumbmediaid']);
                    break;
                case 'news' :
                    $data = (array) json_decode($res['content']);
                    $news = array();
                    $news[] = new NewsResponseItem($data['title'], $data['description'],$data['picurl'], $data['url']);
                    $this->responseNews($news);
                    break;
            }
        }
        if(MST_Core::inDev()) {
            $this->responseText('未找到该事件的响应消息');
        } else {
            exit('success');
        }
    }
}