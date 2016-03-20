<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-12-5
 * Time: 上午6:25
 */
namespace Home\Model;
use Think\Model;

/**
 * 用户基础模型
 */
class ArticleModel extends Model {
    function get_article_list()
    {
        return $this->select();
    }
    public function get_article_by_category($category_id) {
        return $this->where('category_id = %d',$category_id)->select();
    }
    public function get_article_by_id($article_id) {
        return $this->where('article_id = %d',$article_id)->find();
    }
}