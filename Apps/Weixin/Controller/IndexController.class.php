<?php
namespace Weixin\Controller;

use Think\Controller;

class IndexController extends CommonController {
	public function _initialize()
	{
		$this->get_access_token();
 		$this->message = A('Message');//实例化消息控制器
 		$this->event = A('Event');//实例化事件控制器
 		$this->api = A('Api');//实例化api控制器
 		$this->user = A('User');//实例化用户中心控制器
 		$this->oauth = A('Oauth');//实例化网页授权控制器
	}
 	public function index(){
	    	if(I('echostr')){
	    		$this->checkSignature();
	    		exit;
	    	}
	            //保存微信服务器推送给我们服务器的数据
	    	$str_xml = file_get_contents("php://input");
	    	/*$str_xml = "<xml><ToUserName><![CDATA[gh_b2a9c5cf5d14]]></ToUserName>
<FromUserName><![CDATA[ouGpi0R2lQ3_HVuauJr_uJMSwxPM]]></FromUserName>
<CreateTime>1528939762</CreateTime>
<MsgType><![CDATA[event]]></MsgType>
<Event><![CDATA[VIEW]]></Event>
<EventKey><![CDATA[http://www.ice20.top/weixin2/Wechat.php/index/get_userinfo]]></EventKey>
<MenuId>474063127</MenuId>
</xml>";*/
	    	 //保存微信服务器推送给我们服务器的数据
		if(!empty($str_xml)){
			//保存微信服务器推送给我们服务器的数据
	              	savexml($str_xml);
	             	//解析并转为数组格式
		            $arr_xml = $this->toArray($str_xml);
			 // dump($arr_xml);exit;
			 $this->arr_xml = $arr_xml;
			 $this->user_id = $arr_xml['FromUserName'];//用户id
			 $this->Content = $arr_xml['Content'];//消息数据类型
			 $this->server_id = $arr_xml['ToUserName'];//公众号id
			 $this->MsgType = $arr_xml['MsgType'];//消息数据类型
		}

		//定义一个图文数组
		$arr = array(
			 array(
				'Title'=>'标题1',
				'Description'=>'优惠券哈哈哈哈',
				'PicUrl'=>'http://mmbiz.qpic.cn/mmbiz_jpg/YleB0GSSOY8PyqRy9An6ozI3ba18QaJNARYOgf0QCIGch0Ad4cKZoP3GJMmzeZ9zFCHgiaFmibIMmYPb33L0y5RA/0',
				'Url'=>'http://ice20.top/maomao',
				),
			 array(
				'Title'=>'标题2',
				'Description'=>'你好水啊',
				'PicUrl'=>'http://mmbiz.qpic.cn/mmbiz_jpg/YleB0GSSOY8PyqRy9An6ozI3ba18QaJNARYOgf0QCIGch0Ad4cKZoP3GJMmzeZ9zFCHgiaFmibIMmYPb33L0y5RA/0',
				'Url'=>'http://www.baidu.com',
				)
		);

		//事件处理
		if($this->MsgType == 'event'){
			$this->event->select_event();
		}

		//接受的是文本类型消息
		if($this->MsgType == 'text'){
			$msg = $this->Content;
			$this->message->to_select_reply($msg,'text');
		}

		//接受的是图片类型消息
		if($this->MsgType == 'image'){
			$pic_url = $arr_xml['PicUrl'];
			$MediaId = $arr_xml['MediaId'];
			$this->message->to_select_reply($MediaId,'image');
		}

		//接受的是图文类型消息
		if($this->MsgType == 'file'){
			 $this->message->to_select_reply($arr,'news');
		}

		//调用生成微信公众号二维码
		//dump($this->get_code());exit;
		// dump($this->get_server_ip());exit;
		// dump($this->oauth);exit;
 	}

 	//获取用户信息
 	public function get_userinfo()
 	{
		// $redirect_uri = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];

		// echo $redirect_uri;exit;
 		$userinfo = $this->oauth->wx_login();
 		dump($userinfo);exit;
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

           //检查签名
	public function checkSignature()
	{
		$signature=$_GET["signature"];
		$timestamp=$_GET["timestamp"];
		$nonce=$_GET["nonce"];
		$token=C('TOKEN');

		$tmpArr = array($token,$timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );

		if( $tmpStr == $signature ){
			echo $_GET['echostr'];
		}else{
			echo '';
		}
	}

	//xml格式转为数组
	public function toArray($str_xml)
	{
		//xml格式转为对象
		$obj_xml = simplexml_load_string($str_xml);
		//对象转为数组(仅限一维)
		$arr_xml = (array)$obj_xml;
		//二维对象转为字符串
		foreach($arr_xml as $k=>$v){
			if(is_object($v)){
				$arr_xml[$k] = (string)$v;
			}
		}
		return $arr_xml;
	}
}