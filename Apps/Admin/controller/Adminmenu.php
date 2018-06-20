<?php
namespace app\admin\controller;
/*
后台导航栏控制器
*/
class Adminmenu extends Publics
{
    public function index()
    {
		$this->checklogin();
		$this->checkLevel();
		//直接执行sql语句
		$result=$this->db->name(strtolower($this->c))->where(array(

	   ))->order('id asc')->limit('')->select();//从数据表查全部
		// var_dump($result);exit;
		//初始化一个数组
		$tempRs=array();
		//遍历查出来的全部分类数据
		foreach($result AS $k=>$v){
			//取出pid为0的
			if($v['pid']==0){
				$tempRs[$v['id']]=$v;
				//遍历查出来的全部分类数据
				foreach($result AS $k1=>$v1){
				//取出pid等于上一级id的
					if($v1['pid']==$v['id']){
					  $tempRs[$v['id']]['son'][$v1['id']]=$v1;
					  //遍历查出来的全部分类数据
					  foreach($result AS $k2=>$v2){
						   if($v2['pid']==$v1['id']){
							   //取出pid等于上一级id的
						      $tempRs[$v['id']]['son'][$v1['id']]['grandson'][$v2['id']]=$v2;
						   }
					    }
					}
				}
			}
		}
		 //dump($tempRs);exit;
		$result=$tempRs;
		//加载模板
		return $this->view->fetch('index',array(
            'title'=>$this->title[$this->c].'列表',
			'name'=>$this->title[$this->c].'中心',
			'rs'=>$result,
	   ));
    }
	
	/*
	后台添加方法
	*/
    public function addson(){
		$this->checklogin();
		$this->checkLevel();
     if ($this->req->isPost()){
            //保存提交的信息
		    $data=$this->req->post();
			//添加数据到数据库
			$rs=$this->db->name('adminmenu')->insert($data);
			//获取添加成功id
            $insertId =$this->db->name('adminmenu')->getLastInsID();
			if($insertId>0){
				 $this->success('添加成功','Admin/Adminmenu/index');
			}else{
				 $this->error('添加失败'); 
			}
		}else{
			return $this->view->fetch('addson',array(
			   'title'=>'后台子导航添加',
			   'pid'=>$this->req->get('id'),//把提交过来的id
			   'level'=>$this->req->get('level')+1,//把提交过来的level
			));	
		}				
	}
	/*
	后台添加方法
	*/
    public function addtop(){
		$this->checklogin();
		$this->checkLevel();
     if ($this->req->isPost()){
            //保存提交的信息
		    $data=$this->req->post();
			//添加数据到数据库
			$rs=$this->db->name('adminmenu')->insert($data);
			//获取添加成功id
            $insertId =$this->db->name('adminmenu')->getLastInsID();
			if($insertId>0){
				 $this->success('添加成功','Admin/Adminmenu/index');
			}else{
				 $this->error('添加失败'); 
			}
		}else{
			return $this->view->fetch('addtop',array(
			   'title'=>'后台顶级导航添加',
			));	
		}				
	}
	
			//更新
    public function update()
    {
		$this->checklogin();
		$this->checkLevel();
		if(!empty($_GET['id'])){
				if ($this->req->isPost()){
				 //保存提交的信息
				$data=$this->req->post();
                $data=$this->data_handle($data);
				$result=$this->db->name(strtolower($this->c))->where('id', $this->req->post('id'))->update($data);
				if($result>0){
					$this->success('更新成功','admin/'.strtolower($this->c).'/index');
				}elseif($result==0){
					$this->error('更新失败,请至少修改一个信息');
				}else{
					$this->error('更新失败');
				}
		   }else{
			   //输出到前台		   
			   $result=$this->db->name(strtolower($this->c))->where('id',$this->req->get('id'))->find();
			   return $this->view->fetch('update',array(
					'title'=>'后台导航更新',
					'rs'=>$result,
			   ));
		   }
		}else{
			$this->error('无法操作');
		}

    }
	//更新
    public function updateson()
    {
		$this->checklogin();
		$this->checkLevel();
		if(!empty($_GET['id'])){
				if ($this->req->isPost()){
				 //保存提交的信息
				$data=$this->req->post();
                $data=$this->data_handle($data);
				$result=$this->db->name(strtolower($this->c))->where('id', $this->req->post('id'))->update($data);
				if($result>0){
					$this->success('更新成功','admin/'.strtolower($this->c).'/index');
				}elseif($result==0){
					$this->error('更新失败,请至少修改一个信息');
				}else{
					$this->error('更新失败');
				}
		   }else{
			   //输出到前台		   
			   $result=$this->db->name(strtolower($this->c))->where('id',$this->req->get('id'))->find();
			   return $this->view->fetch('updateson',array(
					'title'=>'后台导航更新',
					'id'=>$this->req->get('id'),//把提交过来的id
					'rs'=>$result,
			   ));
		   }
		}else{
			$this->error('无法操作');
		}

    }
		//更新
    public function updatetop()
    {
		$this->checklogin();
		$this->checkLevel();
		if(!empty($_GET['id'])){
				if ($this->req->isPost()){
				 //保存提交的信息
				$data=$this->req->post();
                $data=$this->data_handle($data);
				$result=$this->db->name(strtolower($this->c))->where('id', $this->req->post('id'))->update($data);
				if($result>0){
					$this->success('更新成功','admin/'.strtolower($this->c).'/index');
				}elseif($result==0){
					$this->error('更新失败,请至少修改一个信息');
				}else{
					$this->error('更新失败');
				}
		   }else{
			   //输出到前台		   
			   $result=$this->db->name(strtolower($this->c))->where('id',$this->req->get('id'))->find();
			   return $this->view->fetch('updatetop',array(
					'title'=>'后台导航更新',
					'rs'=>$result,
			   ));
		   }
		}else{
			$this->error('无法操作');
		}

    }
	//管理员del
    public function del()
    {
		$this->checklogin();
		$this->checkLevel();
		//删除，查询一下是否有子集
		$sons=$this->db->name('adminmenu')->field($this->field[$this->req->controller()],true)->where(array(
		     'pid'=>$this->req->get('id'),
		))->select();
		if(empty($sons)){
			$rs=$this->db->name('adminmenu')->where('id', $this->req->get('id'))->delete();

			if($rs>0){
					$this->success('删除成功','index/Admin/adminmenu/index');
				}else{
					$this->error('删除失败'); 
			}	
		}else{
			$this->error('不能删除，请先删除子分类'); 
			
		}	   

    }
}
?>