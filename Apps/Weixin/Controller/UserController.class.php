<?php 
namespace Weixin\Controller;

use Think\Controller;

/**
* 用户中心控制器
*/
class UserController extends CommonController
{
	//用户签到
	public function sign()
	{
		$user = M('sign_in');
		$result = $this->get_userinfo();
		$userinfo = $result['data'];

		//实例化签到表
		$sign = M('sign_in');
		//判断用户当天是否签到
		$check = $sign->where(array('year'=>date("Y"),'month'=>date("m"),'day'=>date("d")))->find();
		if($check){
			$reply = "亲，您今天已经签到过了";
		}else{
			//插入数据库
			$rs = $sign->add(array(
					'user_id'=>$userinfo['openid'],
					'nickname'=>$userinfo['nickname'],
					'year'=>date("Y"),
					'month'=>date("m"),
					'day'=>date("d"),
					'time'=>date("H").':'.date("i").':'.date("s"),
				));
			if($rs){
				$reply = '签到成功,奖励经验值10点';
			}
		}
		// dump($reply);exit;
		return $reply;

	}
	public function get_userinfo()
	{
		$api_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".S('access_token')."&openid=".$this->user_id."&lang=zh_CN";
		$data = file_get_contents($api_url);
		$data = json_decode($data,true);
		// dump($data);exit;
		$result['data'] = $data;
		$userinfo = '';
		$userinfo.="昵称：".$data['nickname']."\n";
		if($data['sex'] == '1'){
			$userinfo.='性别：男'."\n";
		}elseif($data['sex'] == "0"){
			$userinfo.='性别：女'."\n";
		}else{
			$userinfo.='性别：未知'."\n";
		}
		$userinfo.='住址：'.$data['province'].$data['city'].'';
		$result['userinfo'] = $userinfo;
		return $result;
	}
	public function membercenter()
	{
		$this->display('User:membercenter');
	}
}