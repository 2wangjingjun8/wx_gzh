<?php
namespace app\admin\controller;

class Index extends Publics
{
    public function index()
    {
		$this->checklogin();
		$data = $_POST;
		// var_dump($data);exit;
		//加载模板
		return $this->view->fetch('index',array(
            'title'=>'后台首页',		
	   ));
    }
	//管理员登录
    public function login()
    {
	     if($this->req->isAjax()){
			$data = $_POST;
			//检查验证码是否正确
			if(!captcha_check($data['captcha'])){
		        echo '验证码错误';exit;
			}else{
				//检查用户名
				$rs = $this->db->name('adminuser')->where(array(
					'count_name'=>$data['count_name'],
				))->find();
				if($rs){
					if($rs['password'] != md5($data['password'])){
						echo '密码错误';
					}else{
						session('AdminUser',$rs);
						$group = $this->db->name('group')->where(array(
						    'id'=>$rs['group_id'],
						))->find();
						session('AdminUser.admin_scope',$group['rulestr']);
						// dump(session('AdminUser'));exit;
						echo '登录成功';
					}
				}else{
					echo '用户名不存在';
				}
			}

		 }else{
		   return $this->view->fetch('login',array(
				'title'=>'管理员登录',
		    ));
		 }
		
    }
	//管理员退出
    public function logout()
    {
		session('AdminUser',null);
        $this->success('退出成功','admin/index/login');
    }
	
	//验证验证码是否正确
	function check_verify($code, $id = ''){
		$captcha = new Captcha();
		return $captcha->check($code, $id);
	}
}
