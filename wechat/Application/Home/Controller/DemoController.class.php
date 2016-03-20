<?php
/**
 * Created by PhpStorm.
 * User: mixmedia
 * Date: 16-1-22
 * Time: 上午11:27
 */
namespace Home\Controller;
use Think\Controller;
class DemoController extends HomeController {

    public function testAction(){



        /**
         * 微信公众号消息模板配置文件
         * $Author: lintendo $
         */

//微信发送url
        $wx_url = "http://192.168.1.103:81/hirourou/trunk/feisu_wechat/index.php/home/api/template";

//微信发送密匙
        $wx_key = "D5683HES73ZKGA7";

//微信发送模板
        $templates = array(

            //资金变动提醒模板
            "user_crash_msg"=>array(

                // 模板名称
                "template" => "user_crash_msg",

                // 用户ID
                "user_id" => "",

                // 顶部文字颜色
                "topcolor" => "#FF0000",

                // 消息跳转链接
                "url" => "http://118.26.204.228/user.php?act=account_detail",

                // 消息开头内容
                'first' => array(
                    "value" => "",
                    "color" => "#173177"
                ),

                // 消息变动时间
                "date" => array(
                    "value" => "",
                    "color" => "#173177"
                ),

                // 变动金额
                "adCharge" => array(
                    "value" => "",
                    "color" => "#173177"
                ),

                // 账户类型
                "type" => array(
                    "value" => "现金",
                    "color" => "#173177"
                ),

                // 当前余额
                "cashBalance" => array(
                    "value" => "",
                    "color" => "#173177"
                ),

                // 资金变动备注
                "remark" => array(
                    "value" => "",
                    "color" => "#173177"
                )

            ),
            // 资金变动模板结束

            //订单状态更改模板
            "order_changer_tips"=>array(

                // 模板名称
                "template" => "order_changer_tips",

                // 用户ID
                "user_id" => "",

                // 顶部文字颜色
                "topcolor" => "#FF0000",

                // 消息跳转链接
                "url" => "",

                // 消息开头内容
                'first' => array(
                    "value" => "",
                    "color" => "first"
                ),

                // 订单编号
                "keyword1" => array(
                    "value" => "",
                    "color" => "#173177"
                ),

                // 服务类型
                "keyword2" => array(
                    "value" => "",
                    "color" => "#173177"
                ),

                // 变更时间
                "keyword3" => array(
                    "value" => "",
                    "color" => "#173177"
                ),

                // 变更内容
                "keyword4" => array(
                    "value" => "",
                    "color" => "#173177"
                ),

                // 订单变动备注
                "remark" => array(
                    "value" => "",
                    "color" => "#173177"
                )

            ),
            // 订单变更模板结束


            //售后状态更改模板
            "sale_after_deal_msg"=>array(

                // 模板名称
                "template" => "sale_after_deal_msg",

                // 用户ID
                "user_id" => "",

                // 顶部文字颜色
                "topcolor" => "#FF0000",

                // 消息跳转链接
                "url" => "",

                // 消息开头内容
                'first' => array(
                    "value" => "",
                    "color" => "first"
                ),

                // 服务类型
                "HandleType" => array(
                    "value" => "",
                    "color" => "#173177"
                ),

                // 处理状态
                "Status" => array(
                    "value" => "",
                    "color" => "#173177"
                ),

                // 处理状态
                "RowCreateDate" => array(
                    "value" => "",
                    "color" => "#173177"
                ),

                // 当前进度
                "LogType" => array(
                    "value" => "",
                    "color" => "#173177"
                ),

                // 售后备注
                "remark" => array(
                    "value" => "",
                    "color" => "#173177"
                )

            )


        );

        $data = array(

            "first" => "账户充值成功",
            "date"	=> "2011-01-19",
            "adCharge" => "50",
            "cashBalance" => "100",
            "remark" => "看看"
        );
        $template_name = "user_crash_msg";
        $user_id = '18145';
        $templates[$template_name]['user_id'] = $user_id;

        if(!empty($url)){
            $templates[$template_name]['url'] = $url;
        }

        foreach ($data as $key => $value){

            $templates[$template_name][$key]["value"] = $value;

        }

        $res = $this->post_qrcode_by_curl($wx_url,  $templates[$template_name]);
    }

    function post_qrcode_by_curl($url,$data)
    {
        //对空格进行转义
        $url = str_replace(' ','+',$url);
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch,CURLOPT_TIMEOUT,3);  //定义超时3秒钟
        // POST数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // 把post的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));    //所需传的数组用http_bulid_query()函数处理一下，就ok了

        //执行并获取url地址的内容
        $output = curl_exec($ch);
        $errorCode = curl_errno($ch);

        //释放curl句柄
        curl_close($ch);
        if(0 !== $errorCode) {
            return false;
        }
        return $output;
    }

}