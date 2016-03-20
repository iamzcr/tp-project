<?php
/**
 * Created by PhpStorm.
 * User: mixmedia
 * Date: 15-9-7
 * Time: 下午12:22
 */
namespace Home\Model;
use Think\Model;
class ProductModel extends Model {
    /*
     * 获取推荐产品
     */
    public function get_recommend_product_list()
    {
        $map['if_show'] = 1;
        $map['if_recommend'] = 1;
        $data = $this->limit(3)->where( $map )->select();
        return $data;
    }
    /*
     * 获取新产品
     */
    public function get_features_product_list()
    {
        $map['if_show'] = 1;
        $map['if_features'] = 1;
        $data = $this->limit(3)->where( $map )->select();
        return $data;
    }
    /*
     * 获取产品列表
     */
    public function get_product_list()
    {
        $map['if_show'] = 1;
        $data = $this->where( $map )->select();
        return $data;
    }
    /*
     * 获取产品详情
     */
    public  function  get_one_product_by_id($id)
    {
        $data = $this->where("product_id = %d and if_show = %d",$id,1)->find();
        $data['category_name'] = $this->get_category_name($data['cate_id']);
        $data['brand_name'] = $this->get_brand_name($data['brand_id']);
        $data['count_wish'] = $this->get_count_wish($data['product_id']);
        if($data){
            return $data;
        }else{
            return false;
        }
    }
    /*
     * 获取产品分类名字
     */
    public function get_category_name($cate_id)
    {
        $category = D('category');
        $data = $category->where($cate_id)->find();
        return $data['cate_name'];
    }
    /*
     * 获取产品品牌名字
     */
    public function get_brand_name($brand_id)
    {
        $brand = D('brand');
        $data = $brand->where($brand_id)->find();
        return $data['name'];
    }
    /*
     * 获取产品收藏数
     */
    public function get_count_wish($product_id)
    {

        $wish= D('wish');
        $data = $wish->where('product_id = %d',$product_id)->select();
        return count($data);
    }
    /*
     * 产品搜索
     */
    public function  get_search_product($params)
    {
        $data = $this->where("name LIKE %s% or description = %s%",$params,$params)->select();
        if($data){
            return $data;
        }else{
            return false;
        }
    }
    /*
     * 获取标签.分类.品牌产品
     */
    public  function  get_tag_category_brand_product($id,$type)
    {
        $data = array();
        switch($type){
            case $type == 'category':
                $data['product'] = $this->where("cate_id = %d",$id)->select();
                $data['cate_name'] = $this->get_category_name($id);
                return $data;
                break;
            case $type == 'brand':
                $data['product'] = $this->where("brand_id = %d",$id)->select();
                $data['brand_name'] = $this->get_brand_name($id);
                return $data;
                break;
            case $type == 'tag':
                $data = D('product_tag_relation')->where('tag_id = %d',$id)->select();
                $arr['product'] = $this->get_product_by_tag($data);
                $arr['tag_name'] = $this->get_tag_name($id);
                return $arr;
                break;


        }
    }
    /*
     * 获取标签名字
     */
    public function get_tag_name($id)
    {
        $tag = D('tag');
        $data = $tag->where($id)->find();
        return $data['name'];
    }
    public function get_product_by_tag($data)
    {
        $row = array();
        foreach($data as $k=>$v){
            $row[$k] = $this->get_product_by_id($v['product_id']);
        }
        return $row;
    }
    public function get_product_by_id($id)
    {
        $product =  D('product');
        $data = $product->where("product_id = %d and if_show = %d",$id,1)->find();
        return $data;
    }
}