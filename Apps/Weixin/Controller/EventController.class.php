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
				// echo 123;
				$EventKey = $this->arr_xml['EventKey'];
				if($EventKey == 'weather'){
					//回复查询天气的消息
					$this->message->to_select_reply('查询天气，请回复格式如下“广州天气”');
				}elseif($EventKey == 'userinfo'){
					//查询个人信息
					$result = $this->user->get_userinfo();
					$userinfo = $result['userinfo'];
					//回复个人信息的消息
					$this->message->to_select_reply($userinfo);
				}elseif($EventKey == 'sign'){
					//点击签到功能
					$msg = $this->user->sign();
					dump($msg);
					$this->message->to_select_reply($msg);
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