<?php
//// 获取access_token，自动带缓存功能
//function get_access_token() {
//    $appid = C('appid');
//    $secret = C('appsecret');
//    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$secret;
//    $access_token = http_get_function($url);
//    $tempArr = json_decode ( $access_token, true );
//    if (@array_key_exists ( 'access_token', $tempArr )) {
//       session('access_token',$tempArr ['access_token']);
//        return $tempArr ['access_token'];
//    } else {
//        return 0;
//    }
//}
///**
// * GET 请求
// *
// * @param string $url
// */
//function http_get_function($url) {
//    $oCurl = curl_init ();
//    if (stripos ( $url, "https://" ) !== FALSE) {
//        curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );
//        curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, FALSE );
//    }
//    curl_setopt ( $oCurl, CURLOPT_URL, $url );
//    curl_setopt ( $oCurl, CURLOPT_RETURNTRANSFER, 1 );
//    $sContent = curl_exec ( $oCurl );
//    $aStatus = curl_getinfo ( $oCurl );
//    curl_close ( $oCurl );
//    if (intval ( $aStatus ["http_code"] ) == 200) {
//        return $sContent;
//    } else {
//        return false;
//    }
//}
//
///**
// * POST 请求
// *
// * @param string $url
// * @param array $param
// * @return string content
// */
//function http_post_function($url, $param) {
//    $oCurl = curl_init ();
//    if (stripos ( $url, "https://" ) !== FALSE) {
//        curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYPEER, FALSE );
//        curl_setopt ( $oCurl, CURLOPT_SSL_VERIFYHOST, false );
//    }
//    if (is_string ( $param )) {
//        $strPOST = $param;
//    } else {
//        $aPOST = array ();
//        foreach ( $param as $key => $val ) {
//            $aPOST [] = $key . "=" . urlencode ( $val );
//        }
//        $strPOST = join ( "&", $aPOST );
//    }
//    curl_setopt ( $oCurl, CURLOPT_URL, $url );
//    curl_setopt ( $oCurl, CURLOPT_RETURNTRANSFER, 1 );
//    curl_setopt ( $oCurl, CURLOPT_POST, true );
//    curl_setopt ( $oCurl, CURLOPT_POSTFIELDS, $strPOST );
//    $sContent = curl_exec ( $oCurl );
//    $aStatus = curl_getinfo ( $oCurl );
//    curl_close ( $oCurl );
//    if (intval ( $aStatus ["http_code"] ) == 200) {
//        return $sContent;
//    } else {
//        return false;
//    }
//}
//?>