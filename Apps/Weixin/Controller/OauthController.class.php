<?php 
namespace Weixin\Controller;

use Think\Controller;

/**
* 微信网页授权控制器
*/
class OauthController extends CommonController
{
	public function wx_login()
	{
		$userinfo = session('userinfo');
		if($userinfo ){
			return $userinfo;
		}else{
			//没有缓存用户信息的时候，就去授权获取用户信息
			$userinfo = $this->to_auth();//
		}
	}

	//去授权
	public function to_auth($scope="snsapi_userinfo")
	{
		if(!I('code')){
			//没有获取到code,就执行第一步
			//重定向网页授权地址域名要在公众号后台进行配置，不然会报错地址与配置不一致
			$redirect_uri = "http://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
			// dump($redirect_uri);
			$redirect_uri = urlencode($redirect_uri);
			// dump($redirect_uri);
			$api_url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".C('appid')."&redirect_uri=".$redirect_uri."&response_type=code&scope=".$scope."&state=1#wechat_redirect";
			//dump($api_url);exit;
			redirect($api_url);
		}else{
			$code = I('code');
			if(I('state') =='1'){
				//如果有获取得到状态码，执行第二步：根据code去获取授权access_token
				$api_url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".C('appid')."&secret=".C('secret')."&code=".$code."&grant_type=authorization_code";
				$res = getRequest($api_url);
				if($res['errcode']){
					exit($res['errmsg']);
				}else{
					//拉取用户消息
					$api_url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$res['access_token']."&openid=".$res['openid']."&lang=zh_CN";
					// dump($api_url);exit;
					$userinfo = getRequest($api_url);
					//判断是否获取用户信息成功
					if($userinfo['errcode']){
						exit($userinfo['errmsg']);
					}else{
						//保存用户信息到浏览器中，并返回
						session('userinfo',$userinfo);
						return $userinfo;
					}
				}
			}else{
				echo '授权失败';
			}
		}
	}
}