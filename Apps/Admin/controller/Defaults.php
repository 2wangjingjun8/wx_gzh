<?php
namespace app\admin\controller;
/*
后台默认控制器
*/
class Defaults extends Publics
{
	//首页
    public function index()
    {
		
		
		//加载模板
		return $this->view->fetch('index',array(
            'title'=>'管理员列表',
			'html'=>$html,
	   ));
    }
	
	//管理员登录
    public function login()
    {
		
		   return $this->view->fetch('login',array(
				'title'=>'管理员登录',	
		    ));
		
    }
	//管理员退出
    public function logout()
    {
         $this->redirect('defaults/login', array(), 302, ['data' => '管理员退出成功']);
    }
	
}
?>