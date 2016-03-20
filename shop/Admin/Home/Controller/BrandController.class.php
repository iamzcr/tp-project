<?php
namespace Home\Controller;
class BrandController extends HomeController {
    public function indexAction()
    {
        $brand = D('brand');

        $data = $brand->get_brand_list();
        $this->assign('data',$data);
        $this->display();
    }
    public function addAction()
    {
        $this->display();

        if(IS_POST){
            $postData = I('post.brand');
            $postData['create_time'] = time();

            $brand = M('brand');
            $brand->create($postData);

            $res = $brand->add();
            if($res){
                $this->success('添加成功',U('Home/Brand/index'));
            }else{
                $this->error('添加失败',U('Home/Brand/index'));
            }
        }
    }
    public  function  deleteAction()
    {
        $brand_id = I('get.brand_id');

        $brand = M('brand');
        $res = $brand->delete($brand_id);
        if($res){
            $this->success('删除成功',U('Home/Brand/index'));
        }else{
            $this->error('删除失败',U('Home/Brand/index'));
        }
    }
}