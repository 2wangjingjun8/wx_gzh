<?php 
namespace Weixin\Controller;

use Think\Controller;

/**
* Api接口查询
*/
class ApiController extends CommonController
{
	//天气接口查询
	public function get_weather($city)
	{
		$api_url = 'http://wthrcdn.etouch.cn/weather_mini?city='.$city;
		// echo $api_url;
		$res = request($api_url);
		// dump($res);exit;
		if($res['status'] == '1000'){
			$data = $res['data'];
			$city = $data['city'];
			$reply_content .="日期：".$data['forecast'][0]['date']."\n";
			$reply_content .="城市：".$city."\n";
			$reply_content .="状态：".$data['forecast'][0]['type']."\n";
			$reply_content .="温度：".$data['wendu']."℃\n";
			$reply_content .="感冒指数：".$data['ganmao']."\n";
	                     // dump($reply_content);exit;
		}else{
			$reply_content .='输入了无效的城市名';
		}
		return $reply_content;
	}

	//翻译接口查询
	public function translate()
	{
		$this->assign("title","百度翻译");
		$this->display();
	}

	//视频查询
	public function translink()
	{
		$link = I('link');
		$url = "http://jx.618g.com/?url=".$link;
		$this->assign("url",$url);
		$this->display();
	}
}