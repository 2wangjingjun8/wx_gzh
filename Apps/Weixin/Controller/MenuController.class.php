<?php 
namespace Weixin\Controller;

use Think\Controller;

/**
* 公众号菜单控制器
*/
class MenuController extends CommonController
{
	
/*	function __construct(argument)
	{
		# code...
	}*/
	//公众号的菜单创建
	function create_menu()
	{
		$this->get_access_token();
		$api_url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".S('access_token');
		// dump($api_url);exit;
		$json_menu =C('MENU');

		 // $arr_menu = json_decode($json_menu,true);
		 // dump($json_menu);
		 $res = curl_post_https($api_url,$json_menu);
		 $arr_res = json_decode($res,true);
		 // dump($arr_res);exit;
		 if($arr_res['errcode'] == '0'){
		 	echo '创建菜单成功';
		 }else{
		 	echo '创建菜单失败';
		 }
	}

	//公众号的菜单删除
	public function del_menu()
	{
		$api_url = "https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".S('access_token');
		$res = postRequest($api_url);
		if($res['errcode'] == '0'){
			echo '菜单删除成功';
		}
	}
}
