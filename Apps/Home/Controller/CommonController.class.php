<?php
namespace Home\Controller;

use Think\Controller;

class CommonController extends Controller {
	public static $arr_xml;       // 接受到的全部消息
	public static $Content;      //接收到的消息内容
	public static $server_id;    //公众号id
	public static $user_id;      //用户openid
	public static $user;         //实例化用户管理控制器
	public static $product;       //实例化商品列表控制器
	public function _initialize()
	{
		//检查登录范围
		$action_name = ACTION_NAME;
		// DUMP($action_name);EXIT;
		$need_checkLogin = array(
				'membercenter',
				'shop_cart',
			);
		if(in_array($action_name,$need_checkLogin)){
			$this->checklogin();
		}

		//获取普通的access_token
		$weiixin_common = A("Weixin/Common");
		$weiixin_common->get_access_token();
		
		// 执行JSSDK,以便获取需要的接口
		$this->config();
	}



	//检查登录
	public function checklogin()
	{
		$userinfo = session('userinfo');
		if(!$userinfo){
			if(IS_AJAX){
				$return["errcode"] = '100';
				$return['errmsg'] = '请先登录';
				$this->ajaxReturn($return);exit;
			}else{
				//缓存中有用户消息的时候，就跳转到用户中心
				$this->redirect('User/login');
			}
		}
	}

 	//获取配置JSSDK，调用接口
 	public function config()
 	{
		$timestamp = time();// 必填，生成签名的时间戳
		$nonceStr = $this->get_nonceStr(); // 必填，生成签名的随机串
		$signature = $this->get_signature($nonceStr,$timestamp);
		// dump($signature);exit;
		$data = array(
				'appId'=>C('appid'),
				'timestamp'=>$timestamp,
				'nonceStr'=>$nonceStr,
				'signature'=>$signature,
			);
		$this->assign("data",$data);
 	}
 	public function get_signature($nonceStr,$timestamp)
 	{
		$jsapi_ticket = $this->get_ticket();
		$url = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		//组装数组
		$array = array(
				'timestamp'=>$timestamp,
				'noncestr'=>$nonceStr,
				'jsapi_ticket'=>$jsapi_ticket,
				'url'=>$url,
			);
		//对数组进行排序
		ksort($array);
		//使用URL键值对的格式（即key1=value1&key2=value2…）拼接成字符串string1
		foreach($array as $k=>$v){
			$string1 .= "&".$k."=".$v;
		}
		//去除左边的&符号
		$string1 = trim($string1,"&");
		// dump($string1);
		//对string1作sha1加密
		$signature =sha1($string1);// 必填，签名
		// dump($signature);exit;
		return $signature;
 	}

 	//获取随机字符串，nonceStr
 	public function get_nonceStr()
 	{
 		$arr1 = range("a", "z");
 		$arr2 = range("A", "Z");
 		$arr3 = range("0", "9");
 		$arr = array_merge($arr1,$arr2,$arr3);
 		//初始化一个空字符串
 		$str = '';
 		for($i = 0; $i<16;$i++){
 			$str .= $arr[array_rand($arr)];
 		}
 		return $str;
 	}

 	//获取jsapi_ticket
 	public function get_ticket()
 	{
 		$api_url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token=".S('access_token')."&type=jsapi";
 		$res = getRequest($api_url);
		// dump($res);exit;
 		if($res['errcode'] == "0"){
 			return $res['ticket'];
 		}else{
 			return false;
 		}

 	}

}