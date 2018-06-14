<?php 
namespace Weixin\Controller;

use Think\Controller;

/**
* 用户中心控制器
*/
class UserController extends CommonController
{
	public function get_userinfo()
	{
		$api_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=".S('access_token')."&openid=".$this->user_id."&lang=zh_CN";
		$data = file_get_contents($api_url);
		$data = json_decode($data,true);
		// dump($data);exit;
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
		return $userinfo;
	}
}