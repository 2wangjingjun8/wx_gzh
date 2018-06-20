<?php 
namespace Home\Controller;

use Think\Controller;
/**
* 购物车控制器
*/
class CartController extends CommonController
{
	//加入购物车
	public function add_cart()
	{
		if(IS_AJAX){
			//检查登录，然后获取session中用户信息
			$check = $this->checklogin();
			$useinfo = session('userinfo');
			if($useinfo){
				//加购物车前要先查询购物车是否已经有该商品了
				$rs = M('cart')->where(array(
						'memberid'=>session('userinfo.user_id'),
						'proid'=> I('proid')
					))->find();
				if(!$rs){
					//如果购物车不存在，则加入
					//要加入购物车的数据
					$data['proid'] = I('proid');
					$data['memberid'] = session('userinfo.user_id');
					$data['ctime'] = date("Y-m-d H:i:s",time());
					$data['buy_num'] =1;
					//加入购物车
					$add_cart = M("cart")->add($data);
				}else{
					$add_cart = M("cart")->where(array('cart_id'=>$rs['cart_id']))->setInc("buy_num");
				}
				//加购物车成功
				if($add_cart){
					$return['errcode'] = 0;
					$return['errmsg'] = '成功加入购物车';
					$this->ajaxReturn($return);
				}
			}
		}else{
			$check = $this->checklogin();
		}
	}

	//购物车列表
	public function shop_cart()
	{
		$cartlist = M('cart')->alias("c")->join("LEFT JOIN wx_product as p ON c.proid=p.id")
		->where(array(
				"memberid"=>session("userinfo.user_id")
			))->field("p.*,c.buy_num,c.cart_id")->select();
		// dump($cartlist);exit;
		$this->assign("cartlist",$cartlist);
		$this->assign("title","购物车");
		$this->display();
	}

	//修改购物车
	public function changecart()
	{
		$cart_id = I("cart_id");
		$buy_num = I("buy_num");
		$cart = M('cart');
		$res = $cart->where(array("cart_id"=>$cart_id))->save(array('buy_num'=>$buy_num));
		if($res){
			$return['errcode'] = 0;
			$return['errmsg'] = '修改成功';
			$this->ajaxReturn($return);
		}else{
			$return['errcode'] = 1;
			$return['errmsg'] = '修改失败';
			$this->ajaxReturn($return);
		}
	}
}