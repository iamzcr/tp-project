<?php
class Wx_Pay_WechatPay {
    const
        POST = 'post',
        BANK_TYPE = 'bank_type',
        BODY = 'body',
        PARTNER = 'partner',
        OUT_TRADE_NO = 'out_trade_no',
        TIMESTAMP = 'time_stamp',
        TOTAL_FEE = 'total_fee',
        FEE_TYPE = 'fee_type',
        NOTIFY_URL = 'notify_url',
        SPBILL_CREATE_IP = 'spbill_create_ip',
        INPUT_CHARSET = 'input_charset',
        APPID = 'appid',
        APPSERCER = 'appsercer',
        PAYSIGNKEY = 'appkey',
        MCH_ID = 'mch_id',
        APIKEY = 'apikey',
        SIGNTYPE = 'signtype',
        TRADE_TYPE = 'trade_type',
        AUTH_CODE = 'auth_code',
        JSAPI = 'JSAPI',
        NATIVE = 'NATIVE',
        APP = 'APP',
        OPENID = 'openid',
        PRODUCT_ID = 'product_id',
        IS_SUBSCRIBE = 'is_subscribe',
        TRANSACTION_ID = 'transaction_id',
        TIME_END = 'time_end',
        SIGN = 'sign',
        CRET_TYPE = 'pem',
        API_UNIFIEDORDER = 'https://api.mch.weixin.qq.com/pay/unifiedorder',
        API_ORDERQUERY = 'https://api.mch.weixin.qq.com/pay/orderquery',
        API_CLOSEORDER = 'https://api.mch.weixin.qq.com/pay/closeorder',
        API_REFUND = 'https://api.mch.weixin.qq.com/secapi/pay/refund',
        API_REFUNDQUERY = 'https://api.mch.weixin.qq.com/pay/refundquery',
        SSLCERT = '/apiclient_cert.pem',
        SSLKEY = '/apiclient_key.pem';

    public
        $params = array(), $error_number, $error;

    static protected
        $_instance;

    protected
        $_appid, $_appsercer, $_signtype, $_apikey,$_request;

    static public function  getInstance(array $options = array()) {
        if (empty(self::$_instance)) {
            self::$_instance = new self ($options);
        }
        return self::$_instance;
    }

    public function __construct(array $options = array()) {
        $this->_appid = $options[self::APPID];
        $this->_appsercer = $options[self::APPSERCER];
        $this->_mch_id = $options[self::MCH_ID];
        $this->_apikey = $options[self::APIKEY];
        $this->_signtype = $options[self::SIGNTYPE];
    }

    public function setParams($param, $paramValue) {
        $this->params[Wx_Pay_WechatCommonUtil::trimString($param)] = Wx_Pay_WechatCommonUtil::trimString($paramValue);
    }

    public function getParams($param) {
        return $this->params[$param];
    }

    public function verifySignature() {
        $parmas = $this->getRequest();
        unset($parmas[self::SIGN]);
        return $this->getPaySign($parmas) == $this->getRequest(self::SIGN);
    }

    public function getRequest($param = FALSE) {
        if ($param === FALSE)
            return $this->_request;
        $param = strtolower($param);
        if (isset($this->_request[$param]))
            return $this->_request[$param];
        return NULL;
    }

    public function checkParams() {
        //必要的签名的参数
        if ($this->params[self::BODY] == null || $this->params[self::NOTIFY_URL] == null ||
            $this->params[self::OUT_TRADE_NO] == null || $this->params[self::TOTAL_FEE] == null ||
            $this->params[self::TRADE_TYPE] == null || ($this->params[self::TRADE_TYPE] == self::JSAPI && $this->params[self::OPENID] == null) ||
            ($this->params[self::TRADE_TYPE] == self::NATIVE && $this->params[self::PRODUCT_ID] == null)
        ) {
            return false;
        }
        return true;
    }

    /**
     *    作用：设置标配的请求参数，生成签名，生成接口参数xml
     */
    public function createXml() {
        $this->params["appid"] = $this->_appid;//公众账号ID
        $this->params["mch_id"] = $this->_mch_id;//商户号
        $this->params["nonce_str"] = Wx_Pay_WechatCommonUtil::createNoncestr();//随机字符串
        $this->params["sign"] = $this->getPaySign($this->params);//签名
        return Wx_Pay_WechatCommonUtil::arrayToXml($this->params);
    }

    /*
     * 调用接口
     */
    public function call($url, $params = array(), $type = '') {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        //curl_setopt($ch,CURLOPT_PROXY, '8.8.8.8');
        //curl_setopt($ch,CURLOPT_PROXYPORT, 8080);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        if ($type == self::CRET_TYPE) { //默认格式为PEM，可以注释
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLCERT, MST_Core::getPathOf(self::SSLCERT_PATH, MST_Core::P_PUBLIC));
            curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM');
            curl_setopt($ch, CURLOPT_SSLKEY, MST_Core::getPathOf(self::SSLKEY_PATH, MST_Core::P_PUBLIC));
        }
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        $res = curl_exec($ch);
        $this->error_number = curl_errno($ch);
        $this->error = curl_error($ch);
        curl_close($ch);

        if ($this->error_number) {
            return false;
        }
        return $res;
    }

    /*
     * 生成签名方法
     */
    public function getPaySign($signObj) {
        foreach ($signObj as $k => $v) {
            //$signParams[strtolower($k)] = $v; @这里被坑了，JSSDK是不能转小写的
            $signParams[$k] = $v;
        }
        try {
            if ($this->_apikey == "") {
                throw new \Exception("API KEY为空！" . "<br>");
            }
            //签名步骤一：按字典序排序参数
            ksort($signParams);
            $String = Wx_Pay_WechatCommonUtil::formatBizQueryParaMap($signParams, false);
            //签名步骤二：在string后加入KEY
            $String = $String . "&key=" . $this->_apikey;
            //签名步骤三：MD5加密
            $String = md5($String);
            //签名步骤四：所有字符转为大写
            return strtoupper($String);
        } catch (\Exception $e) {
            echo($e->getMessage());
        }
        return false;
    }

    /*
     * 支付模式二必要的prepay_id
     */
    public function getPrepayId() {
        try {
            if (!$this->checkParams()) {
                throw new \Exception("必须签名的参数有漏!" . "<br>");
            }
            $xml = $this->createXml();
            $res = $this->call(self::API_UNIFIEDORDER, $xml);
            return Wx_Pay_WechatCommonUtil::xmlToArray($res);
        } catch (\Exception $e) {
            echo($e->getMessage());
        }
        return false;
    }

    /*
     * 组织JSSDK的数据包
     */
    public function getJsapi() {
        $res = $this->getPrepayId();
        if( ! isset($res["prepay_id"])) {
            return $res;
        } else {
            $jsApiObj['appId'] = $this->_appid;
            $jsApiObj['timeStamp'] = time();
            $jsApiObj['nonceStr'] = Wx_Pay_WechatCommonUtil::createNoncestr();
            $jsApiObj['package'] = 'prepay_id=' . $res["prepay_id"];
            $jsApiObj['signType'] = $this->_signtype;
            $jsApiObj['paySign'] = $this->getPaySign($jsApiObj);
            return $jsApiObj;
        }
    }

    public function getNativeUrl($product_id = null) {
        try {
            if($product_id == null) {
                throw new \Exception("缺少Native支付二维码链接必填参数product_id！"."<br>");
            }
            $native["appid"] = $this->_appid;
            $native["mch_id"] = $this->_mch_id;
            $native["time_stamp"] = time();
            $native["nonce_str"] = Wx_Pay_WechatCommonUtil::createNoncestr();
            $native["sign"] = $this->getPaySign($native);
            return "weixin://wxpay/bizpayurl?" . Wx_Pay_WechatCommonUtil::formatBizQueryParaMap($native, false);
        } catch (\Exception $e) {
            echo ($e->getMessage());
        }
        return false;
    }

    public function orderQuery($params) {
        try {
            if ( ! (isset($params['appid']) && isset($params['mch_id']) && (isset($params['transaction_id']) || isset($params['out_trade_no'])))) {
                throw new \Exception("必须查询订单的参数有漏!" . "<br>");
            }
            $params['nonce_str'] = Wx_Pay_WechatCommonUtil::createNoncestr();
            $params['sign'] = $this->getPaySign($params);
            $xml =  Wx_Pay_WechatCommonUtil::arrayToXml($params);
            $res = $this->call(self::API_ORDERQUERY, $xml);
            $arr_res =  Wx_Pay_WechatCommonUtil::xmlToArray($res);
            return $arr_res;
        } catch (\Exception $e) {
            //echo($e->getMessage());
        }
        return false;
    }

    public function closeOrder($params) {
        try {
            if ( ! (isset($params['appid']) && isset($params['mch_id']) && isset($params['out_trade_no']))) {
                throw new \Exception("关闭订单必须的参数有漏!" . "<br>");
            }
            $params['nonce_str'] =  Wx_Pay_WechatCommonUtil::createNoncestr();
            $params['sign'] = $this->getPaySign($params);
            $xml =  Wx_Pay_WechatCommonUtil::arrayToXml($params);
            $res = $this->call(self::API_CLOSEORDER, $xml);
            $arr_res =  Wx_Pay_WechatCommonUtil::xmlToArray($res);
            return $arr_res;
        } catch (\Exception $e) {
            //echo($e->getMessage());
        }
        return false;
    }

    /*
     * 新版订单退款接口
     */
    public function refundOrder($params) {
        try {
            if ( ! (isset($params['appid']) &&
                isset($params['mch_id']) &&
                isset($params['out_trade_no']) &&
                isset($params['transaction_id']) &&
                isset($params['total_fee'])
            )) {
                throw new \Exception("订单退款必须的参数有漏!" . "<br>");
            }
            $params['nonce_str'] =  Wx_Pay_WechatCommonUtil::createNoncestr();
            $params['refund_fee'] = $params['total_fee'];
            $params['op_user_id'] = $params['mch_id'];
            $params['sign'] = $this->getPaySign($params);
            $xml =  Wx_Pay_WechatCommonUtil::arrayToXml($params);
            $res = $this->call(self::API_REFUNDQUERY, $xml, self::CRET_TYPE);
            $arr_res = Wx_Pay_WechatCommonUtil::xmlToArray($res);
            return $arr_res;
        } catch (\Exception $e) {
            echo($e->getMessage());
        }
        return false;
    }

    static public function create_signature($token, $out_trade_no) {
        $signatureArray = array($token, $out_trade_no);
        sort($signatureArray, SORT_STRING);
        return sha1(implode($signatureArray));
    }
}

if (isset($argc)  && $argc >= 1 && $argv[0] == __FILE__) {

}
