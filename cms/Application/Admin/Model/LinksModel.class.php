<?php
namespace Admin\Model;
use Think\Model;

class LinksModel extends Model {
    function get_links_list()
    {
        return $this->select();
    }
}