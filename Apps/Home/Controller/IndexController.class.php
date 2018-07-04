<?php
namespace Home\Controller;

use Think\Controller;

class IndexController extends CommonController {
	public function homepage()
	{
		$this->assign("title","首页");
		$this->display();

		
	}

 	//获取生成公众号二维码
 	public function get_code()
 	{
 		$str_apiurl ="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token=".S('access_token')."";
 		// dump($str_apiurl);exit;
 		$data = '{
 			"expire_seconds": 604800, 
 			"action_name": "QR_SCENE", 
 			"action_info": {
 				"scene":
 				{
 					"scene_id":0
 				}
 			}
 		}';
 		$arr_param = json_decode($data,true);
 		$res = curl_post_https($str_apiurl,$arr_param);
 		$res = json_decode($res,true);
 		$url = "https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=".UrlEncode($res['ticket']);
 		// dump($res['ticket']);
 		return $url;
 	}

 	//获取服务器ip地址
 	public function get_server_ip()
 	{
 		$server_ip = S('server_ip');
 		if($server_ip == ''){
	 		$str_apiurl = 'https://api.weixin.qq.com/cgi-bin/getcallbackip';
	 		$arr_param = array(
	                                         "access_token"=>$this->get_access_token(),
	 			);
	 		$res = Request($str_apiurl,$arr_param);
	 		$server_ip = $res['ip_list'];
	 		S('server_ip',$server_ip);
 		}
 		return $server_ip;
 	}


}