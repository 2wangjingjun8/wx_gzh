<?php
namespace app\admin\controller;
use think\Request;
class Error extends Publics
{
	public function __construct(){
		echo '页面不存在';
		die;
	} 
    public function index(Request $request)
    {

    }

}