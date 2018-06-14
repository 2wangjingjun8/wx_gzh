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
		$api_url = 'https://www.sojson.com/open/api/weather/json.shtml?city='.$city;
		// echo $api_url;
		$res = request($api_url);
		// dump($res);exit;
		if($res['status'] == '200'){
			$data = $res['data'];
			$city = $res['city'];
			$reply_content .="今天天气\n";
			$reply_content .="城市：".$city."\n";
			$reply_content .="湿度：".$data['shidu']."\n";
			$reply_content .="空气质量：".$data['quality']."\n";
			$reply_content .="温度：".$data['wendu']."\n";
			$reply_content .="感冒指数：".$data['ganmao']."\n";
	                     // dump($reply_content);exit;
		}else{
			$reply_content .='输入了无效的城市名';
		}
		return $reply_content;
	}
}