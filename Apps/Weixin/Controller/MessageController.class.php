<?php 
namespace Weixin\Controller;

use Think\Controller;

/**
* 消息回复控制器
*/
class MessageController extends CommonController
{
	//消息类型选择回复
 	public function to_select_reply($msg,$accept_type='text')
 	{
		 switch ($accept_type) {
		 	case 'text':
		 		$this->reply_text($msg);
		 		break;
		 	case 'image':
		 		$this->reply_image($msg);
		 		break;
		 	case 'news':
		 		$this->reply_news($msg);
		 		break;
		 	
		 	default:
		 		# code...
		 		break;
		 }

 	}

	//回复文本消息
 	public function reply_text($msg)
 	{
		$reply_xml = "<xml><ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%d</CreateTime>
			<MsgType><![CDATA[text]]></MsgType>
			<Content><![CDATA[%s]]></Content>
			</xml>";
		$str = mb_substr($msg,-2,-1,'utf-8');
		// dump($str);exit;
		if($str =="天"){
			$city = mb_substr($msg,0,-2,'utf-8');
			$reply_weather = $this->api->get_weather($city);
			// dump($reply_weather);exit;
			if($reply_weather ==''){
				$reply_weather = '无效的城市名';
			}
			//消息回复处理方法
			$reply_xml = sprintf($reply_xml,$this->user_id ,$this->server_id,time(),$reply_weather);
			echo $reply_xml;exit;
		}else{
			//消息回复处理方法
			$reply_xml = sprintf($reply_xml,$this->user_id ,$this->server_id,time(),$msg);
			echo $reply_xml;exit;
		}
 	}

 	//回复图片消息
 	public function reply_image($msg)
 	{
		$reply_xml = "<xml><ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%d</CreateTime>
			<MsgType><![CDATA[image]]></MsgType>
	                     <Image>
	                     <MediaId><![CDATA[%s]]></MediaId>
	                     </Image>
			</xml>";
		//消息回复处理方法
		$reply_xml = sprintf($reply_xml,$this->user_id ,$this->server_id,time(),$msg);
		echo $reply_xml;
 	}

 	//回复图文消息
 	public function reply_news($msg)
 	{
 		// echo '123';exit;
		$reply_xml = "";
		$reply_xml .= "<xml>";
		$reply_xml .= "<ToUserName><![CDATA[%s]]></ToUserName>";
		$reply_xml .= "<FromUserName><![CDATA[%s]]></FromUserName>";
                                   $reply_xml .= "<CreateTime>%d</CreateTime>";
                                   $reply_xml .= " <MsgType><![CDATA[news]]></MsgType>";
                                   $reply_xml .= "<ArticleCount>".count($msg)."</ArticleCount>";
                                   $reply_xml .= "<Articles>";
                                   foreach ($msg as $key => $v) {
			$reply_xml .= "<item>";
			$reply_xml .= "<Title><![CDATA[".$v['Title']."]]></Title>";
		           $reply_xml .= "<Description><![CDATA[".$v['Description']."]]></Description>";
			$reply_xml .= "<PicUrl><![CDATA[".$v['PicUrl']."]]></PicUrl>";
			$reply_xml .= "<Url><![CDATA[".$v['Url']."]]></Url>";
			$reply_xml .= "</item>";
                               }

                                    $reply_xml .= "</Articles></xml>";
		//消息回复处理方法
		$reply_xml = sprintf($reply_xml,$this->user_id ,$this->server_id,time());
		echo $reply_xml;
 	}

	
}