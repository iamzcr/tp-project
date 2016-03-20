<?php
class WechatSignUtil {
    public function sign($content, $key) {
        try {
            if (null == $key) {
                throw new \Exception("财付通签名key不能为空！" . "<br>");
            }
            if (null == $content) {
                throw new \Exception("财付通签名内容不能为空" . "<br>");
            }
            $signStr = $content . "&key=" . $key;
            return strtoupper(md5($signStr));
        } catch (\Exception $e) {
            echo ($e->getMessage());
        }
    }

    public static function verifySignature($content, $sign, $md5Key) {
        $signStr = $content . "&key=" . $md5Key;
        $calculateSign = strtolower(md5($signStr));
        $tenpaySign = strtolower($sign);
        return $calculateSign == $tenpaySign;
    }
}