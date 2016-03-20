<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-12-5
 * Time: 上午6:25
 */
namespace Admin\Model;
use Think\Model;

/**
 * 用户基础模型
 */
class ArticleModel extends Model {
    function get_article_list()
    {
        return $this->select();
    }
}