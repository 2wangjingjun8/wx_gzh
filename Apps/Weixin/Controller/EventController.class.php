<?php 
namespace Weixin\Controller;

use Think\Controller;

/**
* 事件回复控制器
*/
class EventController extends CommonController
{
	public function select_event()
	{
		// dump($this->arr_xml);
		//关注和取消关注，消息事件处理回复
		if($this->MsgType == 'event'){
			if($this->arr_xml['Event'] == 'subscribe'){
				//回复关注消息的欢迎语
				$this->message->to_select_reply('欢迎关注王静俊的公众号');
			}elseif($this->arr_xml['Event'] == 'SCAN'){
				$EventKey = $this->arr_xml['EventKey'];
				//回复扫码关注消息的欢迎语
				$this->message->to_select_reply('欢迎您的扫码关注,你的场景值是'.$EventKey);
			}elseif($this->arr_xml['Event'] == 'CLICK'){
				$EventKey = $this->arr_xml['EventKey'];
				if($EventKey == '广州'){
					$return_weather = $this->api->get_weather($EventKey);
					// dump($return_weather);
					//回复查询天气的消息
					$this->message->to_select_reply($return_weather);
				}elseif($EventKey == 'userinfo'){
					$userinfo = $this->user->get_userinfo();
					//回复个人信息的消息
					$this->message->to_select_reply($userinfo);
				}
			}elseif($this->arr_xml['Event'] == 'unsubscribe'){
			           //用户取消关注时，保存记录
			           $data['wx_id'] = $this->user_id;
			           $data['time'] = time();
			           $unuser = M('unsubscribe_user');
	              	          $unuser->add($data);exit;
			}
		}
	}
}