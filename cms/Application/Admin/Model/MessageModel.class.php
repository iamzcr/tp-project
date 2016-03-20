<?php
namespace Admin\Model;
use Think\Model;

class MessageModel extends Model {
    function get_message_list()
    {
        return $this->select();
    }
}