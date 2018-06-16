<?php 
namespace Weixin\Controller;

use Think\Controller;
/**
* 购物车控制器
*/
class CartController extends CommonController
{
	public function shop_cart()
	{
		$this->assign("title","购物车");
		$this->display('Cart:shop_cart');
	}
}