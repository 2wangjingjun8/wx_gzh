<?php
return array(
	//'配置项'=>'配置值'
	'TOKEN'=>'wangjingjun',
	'appid'=>'wxeb69dcc420d95b6b',//wxe917ffe5098a8e4f
	'secret'=>'212f044fcdd1a399b2c4209ecf42c6ab',//0b8e9effc192e686a2703804555ff4a0
	//公众号菜单配置
           'MENU'     =>   '{
		           "button":[
			           {
					 "type":"click", "name":"查询天气", "key":"广州"
			           },
			          {
				           "name":"菜单",
				           "sub_button":[
					           {    
					               "type":"view",
					               "name":"搜索",
					               "url":"http://www.soso.com/"
					            },
					           {    
					               "type":"view",
					               "name":"淘宝",
					               "url":"http://www.taobao.com/"
					            },
					           {    
					               "type":"view",
					               "name":"用户中心",
					               "url":"http://www.ice20.top/weixin2/Wechat.php/index/get_userinfo"
					            },
					            {
					               "type":"click",
					               "name":"赞一下我们",
					               "key":"good"
					            }
				            ]
			           },
			          {
				         	"type":"click", "name":"用户中心", "key":"userinfo"
			           },
		           ]
		 }',

);