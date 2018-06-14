<?php
namespace Weixin\Controller;

use Think\Controller;

class CommonController extends Controller {
	public static $arr_xml;       // 接受到的全部消息
	public static $Content;      //接收到的消息内容
	public static $server_id;    //公众号id
	public static $user_id;      //用户openid
	public static $MsgType;   //消息类型
	public static $message;   //实例化消息处理控制器
	public static $event;        //实例化事件处理控制器
	public static $user;         //实例化用户管理控制器
	public static $oauth;       //实例化网页授权控制器
	
 	//获取access_token和保存
 	public function get_access_token()
 	{ 
 		$access_token = S('access_token');
 		if($access_token == ''){
	 		$str_apiurl = 'https://api.weixin.qq.com/cgi-bin/token';
	 		$arr_param = array(
	                                         "grant_type"=>"client_credential",
	                                         "appid"=>C('appid'),
	                                         "secret"=>C('secret'),
	 			);
	 		$res = Request($str_apiurl,$arr_param);
	                     $access_token = $res['access_token'];
	 		S('access_token',$access_token);
 		}
	 	return $access_token;
 	}
}