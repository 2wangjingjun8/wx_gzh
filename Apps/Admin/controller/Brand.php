<?php
namespace app\admin\controller;
/*
后台产品品牌控制器
*/
class Brand extends Publics
{
    public function index()
    {
		$this->checklogin();
		$this->checkLevel();
		//直接执行sql语句
		$result=$this->db->name(strtolower($this->c))->field($this->field[$this->c],true)->where(array(
	   ))->order('id desc')->limit('')->paginate($this->prePage=5);
		// var_dump($result);exit;
		$html='';
 		foreach($result AS $k=>$v){
						 $html.='<tr class="list-group-item-success">';	
                            foreach($v AS $key=>$value){
										if($key=='brand_logo'){
                                            $value='<img src="'.$value.'" width="100" height="100">';
										}
										$html.='<td>'.$value.'</td>';	
							}
							$html.='<td><a class="btn btn-xs btn-primary update"  href=" '.$this->req->module().'/'.$this->req->controller().'/update?id='.$v['id'].'&page='.$this->req->get('page').'"><i class="glyphicon glyphicon-search" ></i>编辑</ a><a class="btn btn-xs btn-danger del"  href="index.php/'.$this->req->module().'/'.$this->req->controller().'/del?id='.$v['id'].'"><i class="glyphicon glyphicon-home del"></i>删除</ a></td>';	
						$html.='</tr>';
		}
		//加载模板
		return $this->view->fetch('index',array(
            'title'=>$this->title[$this->c].'列表',
			'html'=>$html,
			'field'=>$this->item[$this->c],
			'name'=>$this->title[$this->c].'中心',
			'rs'=>$result,
	   ));
    }
	
	//管理员添加
    public function add()
    {
		$this->checklogin();
		$this->checkLevel();
		if ($this->req->isPost()){
		    //保存提交的信息
		    $data=$_POST;
            $data=$this->data_handle($data);
			//添加数据到数据库
			$result=$this->db->name(strtolower($this->c))->insert($data);
			// var_dump($result);exit;
			if($result>0){
				$this->success('添加成功','admin/'.strtolower($this->c).'/index');
				//$this->redirect('admin/'.strtolower($this->c).'/index',Null,3,'添加成功，页面跳转中......');
			}else{
				$this->error('添加失败');
			}
	   }else{
		   return $this->view->fetch('add',array(
				'title'=>$this->title[$this->c].'添加',	
			    'name'=>$this->title[$this->c].'中心',
		   ));
	   }
		
    }
	//管理员更新
    public function update()
    {
		$this->checklogin();
		$this->checkLevel();
		if(!empty($_GET['id'])){
				if ($this->req->isPost()){
				 //保存提交的信息
				$data=$_POST;
				//判断提交过来的图片和多图是否为空
				if(empty($data['brand_logo']) || $data['brand_logo']==null){
					unset($data['brand_logo']);
				}
				// var_dump($data);exit;
				$result=$this->db->name(strtolower($this->c))->where('id', $this->req->get('id'))->update($data);
				if($result>0){
					$this->success('更新成功',url('admin/'.strtolower($this->c).'/index',array('page'=>$this->req->get('page'))));
				}elseif($result==0){
					$this->error('更新失败,请至少修改一个信息');
				}else{
					$this->error('更新失败');
				}
		   }else{
			   //输出到前台		   
			   $result=$this->db->name(strtolower($this->c))->where('id',$this->req->get('id'))->find();
			   return $this->view->fetch('update',array(
					'title'=>$this->title[$this->c].'更新',
					'rs'=>$result,
			        'name'=>$this->title[$this->c].'中心',
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
		$product=$this->db->name('product')->where(array(
			   'brand_id'=>$this->req->get('id')
			))->order('')->limit('')->select();
		if(empty($product)){
			$result=$this->db->name(strtolower($this->c))->where('id', $this->req->get('id'))->delete();
			if($result>0){
					$this->success('删除成功','admin/'.strtolower($this->c).'/index');
			}else{
					$this->error('删除失败');
			}			
		}else{
			$this->error('品牌下还有商品,无法删除');
		}		   

    }
}
?>