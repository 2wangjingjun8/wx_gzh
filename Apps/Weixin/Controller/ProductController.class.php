<?php 
namespace Weixin\Controller;

use Think\Controller;
/**
* 商品列表控制器
*/
class ProductController extends CommonController
{
	public function homepage()
	{
		// $this->config();
		$this->assign("title","首页");
		$this->display();
	}

	public function pro_sort()
	{
		$this->assign("title","分类");
		$this->display();
	}
}