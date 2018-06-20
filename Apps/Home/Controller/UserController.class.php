<?php 
namespace Home\Controller;

use Think\Controller;

/**
* 用户中心控制器
*/
class UserController extends CommonController
{
	public function registere()
	{
		if(I("name")){
			echo I("name");exit;
		}
		$this->display();
	}
	//登录
	public function login()
	{
		$this->assign('title',"用户登录");
		$this->display();
	}
	//微信登录
	public function wxlogin()
	{
		$oauth = A('Weixin/Oauth');
		$userinfo = $oauth->wx_login();
		$this->redirect("membercenter");
	}

	//退出登录
	public function logout()
	{
		session('userinfo',null);
		$this->redirect("login");
	}
		//
	//用户中心页面
	public function membercenter()
	{
		$userinfo = session("userinfo");
		// dump($userinfo);exit;
		$openid = $userinfo['openid'];
		//用户类型：1表示微信；2表示微博；3表示qq；4表示普通账号
		$user_type = 1;
		//将用户的登录类型写进userinfo中
		$userinfo["login_type"] = $user_type;
		//查询数据库，是否存在当前登录的用户信息
		$res = M('member');
		$find = $res->where(array("user_type"=>$user_type,"other_openid"=>$openid))->find();
		if(!$find){
			//如果当前数据库不存在该登录方式的用户信息，就需要插入数据
			$rs = $res->add(array(
					"user_type"=>$user_type,
					"other_openid"=>$openid,
					"other_userinfo"=>json_encode($userinfo,JSON_UNESCAPED_UNICODE),
					"ctime"=>date("Y-m-d H:i:s",time()),
			));
			$userinfo['user_id'] = $rs;
		}else{
			$userinfo['user_id'] =$find['id'];
		}
		//重新保存session
		session("userinfo",$userinfo);
		// dump(session('userinfo'));
		$this->assign("userinfo",$userinfo);
		$this->display();
	}
}
