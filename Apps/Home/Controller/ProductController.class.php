<?php 
namespace Home\Controller;

use Think\Controller;
/**
* 商品列表控制器
*/
class ProductController extends CommonController
{	
	//商品列表
	public function pro_list()
	{
		$product = M('product');
		$pro_list = $product->select();
		// dump($pro_list);exit;
		$this->assign("pro_list",$pro_list);
		$this->assign("title","商品列表");
		$this->display();
	}

	//商品详情
	public function pro_details()
	{
		$product = M('product');
		$pro_details = $product->where("id=".$_GET['id'])->find();
		$pro_details['photo_detail'] =explode(',',$pro_details['photo_detail']) ;
		// dump($pro_details);exit;
		$this->assign("pro_details",$pro_details);
		$this->assign("title","商品详情");
		$this->display();
	}

	//商品分类列表
	public function pro_sort()
	{
		$this->assign("title","分类");
		$this->display();
	}

	//商品搜索结果
	public function pro_seach()
	{
		$this->assign("title","搜索");
		$this->display();
	}

}