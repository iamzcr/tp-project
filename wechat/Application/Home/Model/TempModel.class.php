<?php
namespace Home\Model;
use Think\Model;
/**
 * 模板模型
 */
class TempModel extends Model {

    public function get_temp_list()
    {
        $data = $this->select();
        return $data;
    }
    public function get_temp_detail($type)
    {
       $where['type'] = $type;
       return $this->where($where)->find();
    }
    /**
     * 订单提交成功消息模板
     * @param $open_id
     * @param $data
     * @return array
     */
    public function get_order_submit_success_template($open_id,$data)
    {

        $temp_detail = $this->get_temp_detail($data['template']);
        if(!$temp_detail){
            return null;
        }
        $template_id = $temp_detail['template_id'];
        $temp_data = array(
            'touser'=>$open_id,
            'template_id'=>$template_id,
            "topcolor"=>"#FF0000",
            'data'=>array(
                'first'=>array(
                    "value"=>"订单提交成功",
                    "color"=>"#173177"
                ),
                'orderID'=>array(
                    "value"=>"订单单号",
                    "color"=>"#173177"
                ),
                'orderMoneySum'=>array(
                    "value"=>"订单金额",
                    "color"=>"#173177"),
                'backupFieldName'=>array(
                    "value"=>"商品名字",
                    "color"=>"#173177"),
                'backupFieldData'=>array(
                    "value"=>"提交时间",
                    "color"=>"#173177"),
                'remark'=>array(
                    "value"=>"订单消息",
                    "color"=>"#173177"
                ),
            ));
        return $temp_data;
    }
    /**
     * 退款成功消息模板
     * @param $open_id
     * @param $data
     * @return array
     */
    public function get_refund_success_msg_template($open_id,$data)
    {
        $temp_detail = $this->get_temp_detail($data['template']);
        if(!$temp_detail){
            return null;
        }
        $template_id = $temp_detail['template_id'];
        $temp_data = array(
            'touser'=>$open_id,
            'template_id'=>$template_id,
            "topcolor"=>"#FF0000",
            'data'=>array(
                'first'=>array(
                    "value"=>"退款金额",
                    "color"=>"#173177"
                ),
                'orderProductPrice'=>array(
                    "value"=>"商品详情",
                    "color"=>"#173177"
                ),
                'orderProductName'=>array(
                    "value"=>"订单金额",
                    "color"=>"#173177"),
                'orderName'=>array(
                    "value"=>"订单编号",
                    "color"=>"#173177"),
                'remark'=>array(
                    "value"=>"备注",
                    "color"=>"#173177"),
            ));
        return $temp_data;
    }

    /**
     * 会员充值通知消息模板
     * @param $open_id
     * @param $data
     * @return array
     */
    public function get_user_cash_msg_template($open_id,$data)
    {
        $temp_detail = $this->get_temp_detail($data['template']);
        if(!$temp_detail){
            return null;
        }
        $template_id = $temp_detail['template_id'];
        $temp_data = array(
            'touser'=>$open_id,
            'template_id'=>$template_id,
            "topcolor"=>"#FF0000",
            'data'=>array(
                'first'=>array(
                    "value"=>"会员充值通知",
                    "color"=>"#173177"
                ),
                'accountType'=>array(
                    "value"=>"会员卡号",
                    "color"=>"#173177"
                ),
                'account'=>array(
                    "value"=>"会员卡号",
                    "color"=>"#173177"),
                'amount'=>array(
                    "value"=>"充值金额",
                    "color"=>"#173177"),
                'result'=>array(
                    "value"=>"充值状态",
                    "color"=>"#173177"),
                'remark'=>array(
                    "value"=>"备注",
                    "color"=>"#173177"
                ),
            ));
        return $temp_data;
    }
    /**
     * 操作验证码提醒消息模板
     * @param $open_id
     * @param $data
     * @return array
     */
    public function get_op_check_code_tips_template($open_id,$data)
    {
        $temp_detail = $this->get_temp_detail($data['template']);
        if(!$temp_detail){
            return null;
        }
        $template_id = $temp_detail['template_id'];
        $temp_data = array(
            'touser'=>$open_id,
            'template_id'=>$template_id,
            "topcolor"=>"#FF0000",
            'data'=>array(
                'first'=>array(
                    "value"=>"操作验证码提醒",
                    "color"=>"#173177"
                ),
                'keyword1'=>array(
                    "value"=>"当前操作",
                    "color"=>"#173177"
                ),
                'keyword2'=>array(
                    "value"=>"验证码",
                    "color"=>"#173177"),
                'remark'=>array(
                    "value"=>"订单消息",
                    "color"=>"#173177"
                ),
            ));
        return $temp_data;
    }
    /**
     * 帐户资金变动提醒消息模板
     * @param $open_id
     * @param $data
     * @return array
     */
    public function get_acount_money_change_tips_template($open_id,$data)
    {
        $temp_detail = $this->get_temp_detail($data['template']);
        if(!$temp_detail){
            return null;
        }
        $template_id = $temp_detail['template_id'];
        $temp_data = array(
            'touser'=>$open_id,
            'template_id'=>$template_id,
            "topcolor"=>"#FF0000",
            'data'=>array(
                'first'=>array(
                    "value"=>"帐户资金变动提醒",
                    "color"=>"#173177"
                ),
                'date'=>array(
                    "value"=>"变动时间",
                    "color"=>"#173177"
                ),
                'adCharge'=>array(
                    "value"=>"变动金额",
                    "color"=>"#173177"),
                'type'=>array(
                    "value"=>"账户类型",
                    "color"=>"#173177"),
                'cashBalance'=>array(
                    "value"=>"帐户余额",
                    "color"=>"#173177"),
                'remark'=>array(
                    "value"=>"备注",
                    "color"=>"#173177"
                ),
            ));
        return $temp_data;
    }
    /**
     * 商品补款通知消息模板
     * @param $open_id
     * @param $data
     * @return array
     */
    public function get_good_repay_msg_template($open_id,$data)
    {
        $temp_detail = $this->get_temp_detail($data['template']);
        if(!$temp_detail){
            return null;
        }
        $template_id = $temp_detail['template_id'];
        $temp_data = array(
            'touser'=>$open_id,
            'template_id'=>$template_id,
            "topcolor"=>"#FF0000",
            'data'=>array(
                'first'=>array(
                    "value"=>"商品补款通知消息",
                    "color"=>"#173177"
                ),
                'keyword1'=>array(
                    "value"=>"商品编号",
                    "color"=>"#173177"
                ),
                'keyword2'=>array(
                    "value"=>"商品名称",
                    "color"=>"#173177"),
                'keyword3'=>array(
                    "value"=>"补款金额",
                    "color"=>"#173177"),
                'remark'=>array(
                    "value"=>"备注",
                    "color"=>"#173177"
                ),
            ));
        return $temp_data;
    }
    /**
     * 缺货退款通知消息模板
     * @param $open_id
     * @param $data
     * @return array
     */
    public function get_goods_refund_msg_template($open_id,$data)
    {
        $temp_detail = $this->get_temp_detail($data['template']);
        if(!$temp_detail){
            return null;
        }
        $template_id = $temp_detail['template_id'];
        $temp_data = array(
            'touser'=>$open_id,
            'template_id'=>$template_id,
            "topcolor"=>"#FF0000",
            'data'=>array(
                'first'=>array(
                    "value"=>"缺货退款通知",
                    "color"=>"#173177"
                ),
                'orderID'=>array(
                    "value"=>"订单号",
                    "color"=>"#173177"
                ),
                'commodityTitle'=>array(
                    "value"=>"商品名称",
                    "color"=>"#173177"),
                'remark'=>array(
                    "value"=>"备注",
                    "color"=>"#173177"
                ),
            ));
        return $temp_data;
    }
    /**
     * 售后服务处理进度提醒消息模板
     * @param $open_id
     * @param $data
     * @return array
     */
    public function get_sale_after_deal_msg_template($open_id,$data)
    {
        $temp_detail = $this->get_temp_detail($data['template']);
        if(!$temp_detail){
            return null;
        }
        $template_id = $temp_detail['template_id'];
        $temp_data = array(
            'touser'=>$open_id,
            'template_id'=>$template_id,
            "topcolor"=>"#FF0000",
            'data'=>array(
                'first'=>array(
                    "value"=>"售后服务处理进度提醒",
                    "color"=>"#173177"
                ),
                'HandleType'=>array(
                    "value"=>"服务类型",
                    "color"=>"#173177"
                ),
                'Status'=>array(
                    "value"=>"处理状态",
                    "color"=>"#173177"),
                'RowCreateDate'=>array(
                    "value"=>"提交时间",
                    "color"=>"#173177"),
                'LogType'=>array(
                    "value"=>"当前进度",
                    "color"=>"#173177"),
                'remark'=>array(
                    "value"=>"备注",
                    "color"=>"#173177"
                ),
            ));
        return $temp_data;
    }

    /**
     * 订单状态更新消息模板
     * @param $open_id
     * @param $data
     * @return array
     */
    public function get_order_status_update_template($open_id,$data)
    {
        $temp_detail = $this->get_temp_detail($data['template']);
        if(!$temp_detail){
            return null;
        }
        $template_id = $temp_detail['template_id'];
        $temp_data = array(
            'touser'=>$open_id,
            'template_id'=>$template_id,
            "topcolor"=>"#FF0000",
            'data'=>array(
                'first'=>array(
                    "value"=>"订单状态更新",
                    "color"=>"#173177"
                ),
                'OrderSn'=>array(
                    "value"=>"订单编号",
                    "color"=>"#173177"
                ),
                'OrderStatus'=>array(
                    "value"=>"订单状态",
                    "color"=>"#173177"),
                'remark'=>array(
                    "value"=>"备注",
                    "color"=>"#173177"
                ),
            ));
        return $temp_data;
    }
    /**
     * 订单支付成功消息模板
     * @param $open_id
     * @param $data
     * @return array
     */
    public function get_order_pay_for_success_msg_template($open_id,$data)
    {
        $temp_detail = $this->get_temp_detail($data['template']);
        if(!$temp_detail){
            return null;
        }
        $template_id = $temp_detail['template_id'];
        //{{Remark.DATA}}
        $temp_data = array(
            'touser'=>$open_id,
            'template_id'=>$template_id,
            "topcolor"=>"#FF0000",
            'data'=>array(
                'first'=>array(
                    "value"=>"订单支付成功",
                    "color"=>"#173177"
                ),
                'orderMoneySum'=>array(
                    "value"=>"支付金额",
                    "color"=>"#173177"
                ),
                'orderProductName'=>array(
                    "value"=>"商品信息",
                    "color"=>"#173177"),
                'remark'=>array(
                    "value"=>"备注",
                    "color"=>"#173177"
                ),
            ));
        return $temp_data;
    }
    /**
     * 订单变更提醒消息模板
     * @param $open_id
     * @param $data
     * @return array
     */
    public function get_order_changer_tips_template($open_id,$data)
    {
        $temp_detail = $this->get_temp_detail($data['template']);
        if(!$temp_detail){
            return null;
        }
        $template_id = $temp_detail['template_id'];
        $temp_data = array(
            'touser'=>$open_id,
            'template_id'=>$template_id,
            "topcolor"=>"#FF0000",
            'data'=>array(
                'first'=>array(
                    "value"=>"订单变更提醒",
                    "color"=>"#173177"
                ),
                'keyword1'=>array(
                    "value"=>"业务单号",
                    "color"=>"#173177"
                ),
                'keyword2'=>array(
                    "value"=>"服务类型",
                    "color"=>"#173177"),
                'keyword3'=>array(
                    "value"=>"变更时间",
                    "color"=>"#173177"),
                'keyword4'=>array(
                    "value"=>"变更内容",
                    "color"=>"#173177"),
                'remark'=>array(
                    "value"=>"备注",
                    "color"=>"#173177"
                ),
            ));
        return $temp_data;
    }
}