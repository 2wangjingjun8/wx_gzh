<?php
namespace app\admin\controller;
/*
后台产品控制器
*/
class Product extends Publics
{
		//接受到ajax请求，查询属性数据并返回
	public function getAttr(){
		$this->checklogin();
		$this->checkLevel();
		$rs=$this->db->name('attributecate')
		->alias('ac')
		->join('aa_attribute a','ac.attrid=a.id')
		->where(array(
		   'ac.catid'=>$this->req->get('catid'),
		))
		->select(); 
		// var_dump($rs);exit;
		$html='';
		echo '<p>单选输入：请用;号分隔每一个单选的值</p>';
		foreach($rs AS $k=>$v){
			
			if($v['attrtype']==1){
			inputTag(''.$v['attrname'].'','attr['.$v['id'].']');	
			}
		}
		echo '<p>固定输入</p>';
		foreach($rs AS $k=>$v){
			
			if($v['attrtype']==0){
			inputTag(''.$v['attrname'].'','attr['.$v['id'].']');	
			}
		}		
	}
    public function index()
    {
		$this->checklogin();
		$this->checkLevel();
		//直接执行sql语句
		$result=$this->db->name(strtolower($this->c))
		->alias('p')
		->join('aa_brand b','p.brand_id=b.id')
		->join('aa_category c','p.catid=c.id')
		->field($this->field[$this->req->controller()])
		->where(array())->order('p.id desc')
		->limit('')
		->paginate(5);
		// var_dump($result);exit;
		$html='';
			// dump($result);exit;
 		foreach($result AS $k=>$v){
						 $html.='<tr class="list-group-item-success">';	
                            foreach($v AS $key=>$value){
											  if($key=='pro_name'){
										            $html.='<td width="180">'.$value.'</td>';	
												  continue;
											  }
											  if($key=='gtype'){
												    if($value==1){
														$value='上架';
													}else{
														$value='下架';				
													}
											  }
											  if($key=='is_new'){
												    if($value==1){
														$value='是';
													}else{
														$value='不是';				
													}
											  }
											  if($key=='is_hot'){
												    if($value==1){
														$value='是';
													}else{
														$value='不是';				
													}
											  }
											  if($key=='is_good'){
												    if($value==1){
														$value='是';
													}else{
														$value='不是';				
													}
											  }
											  if($key=='pro_photo'){
                                                    $value='<img src="'.$value.'" width="50" height="50">';
											  }
											  if($key=='brand_logo'){
                                                    $value='<img src="'.$value.'" width="50" height="50">';
											  }
								
										      $html.='<td>'.$value.'</td>';	
							}
							
							$html.='<td><a class="btn btn-xs btn-primary"  href=" '.$this->req->module().'/inventory/add?proid='.$v['id'].'"><i class="glyphicon glyphicon-search" ></i>商品进货</ a>
							<a class="btn btn-xs btn-primary"  href=" '.$this->req->module().'/'.$this->req->controller().'/update?id='.$v['id'].'&page='.$this->req->get('page').'"><i class="glyphicon glyphicon-search" ></i>编辑</ a>
							<a class="btn btn-xs btn-primary"  href=" '.$this->req->module().'/Productpage/index?proid='.$v['id'].'"><i class="glyphicon glyphicon-search" ></i>套餐管理</ a>
							<a class="btn btn-xs btn-danger del"  href="index.php/'.$this->req->module().'/'.$this->req->controller().'/del?id='.$v['id'].'"><i class="glyphicon glyphicon-home del"></i>删除</ a></td>';	
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
	
	//产品添加
    public function add()
    {
		$this->checklogin();
		$this->checkLevel();
		if ($this->req->isPost()){
		    //保存提交的信息
		    $data=$_POST;
            $data=$this->data_handle($data);
			$data['content']=addslashes($_POST['content']);
			$attr=$_POST['attr'];
			unset($data['attr']);
			//添加数据到数据库
			$result=$this->db->name(strtolower($this->c))->insert($data);
			// var_dump($result);exit;
			//获取最后添加的产品的id
			$insertId =$this->db->name('product')->getLastInsID();
			if($result>0){
				foreach($attr AS $k=>$v){
					 $data=array(
					     'proid'=>$insertId,
						 'attrid'=>$k,
						 'attrval'=>$v,
					 );
					 $res=$this->db->name('goods_attr')->insert($data);
				 }
				$this->success('添加成功','admin/'.strtolower($this->c).'/index');
				//$this->redirect('admin/'.strtolower($this->c).'/index',Null,3,'添加成功，页面跳转中......');
			}else{
				$this->error('添加失败');
			}
	   }else{
		   return $this->view->fetch('add',array(
				'title'=>$this->title[$this->c].'添加',	
			    'name'=>$this->title[$this->c].'中心',
			   'value'=>'0.00',
			   'gtype'=>array('下架','上架'),
			   'unit'=>array('瓶','罐','件','盒'),
			   'is_true'=>array('否','是'),
		   ));
	   }
		
    }
	//产品更新
    public function update()
    {
		$this->checklogin();
		$this->checkLevel();
		if(!empty($_GET['id'])){
				if ($this->req->isPost()){
				// dump($_FILES);
				 //保存提交的信息
				$data=$_POST;
                $data=$this->data_handle($data);
			    //判断提交过来的图片和多图是否为空
			    if(empty($data['pro_photo']) || $data['pro_photo']==null){
				    unset($data['pro_photo']);
			    }
				$a_photo_detail=explode(',',$data['a_photo_detail']);
				//判断多图是否为空
			    if(empty($data['photo_detail']) || $data['photo_detail']==null){
				    unset($data['photo_detail']);
			    }else{
					foreach($data['photo_detail'] as $k=>$v){
						if(empty($v)){
							$data['photo_detail'][$k]=$a_photo_detail[$k];
						}
					}
					$data['photo_detail']=implode(',',$data['photo_detail']);
				}
			    unset($data['a_photo_detail']);
				// dump($data);exit;
				
				//把商品介绍的内容用addslashes进行转义去保存数据库
				$data['content']=addslashes($_POST['content']);
				//把勾选的快递方式数组格式化成字符串
				$data['explist']=implode(',',$_POST['explist']);
				
				$attr=$_POST['attr'];
				unset($data['attr']);
				/*  */// var_dump($data);exit;
				//判断提交过来的图片和多图是否为空
				if(empty($data['pro_photo']) || $data['pro_photo']==null){
					unset($data['pro_photo']);
				}
				if(empty($data['photo_detail']) || $data['photo_detail']==null){
					unset($data['photo_detail']);
				}
				
				//先删除掉全部产品属性信息
				$delAttr=$this->db->name('goods_attr')->where('proid', $this->req->post('id'))->delete();
				//重新插入信息
				foreach($attr AS $k=>$v){
						 $attrdata=array(
							 'proid'=>$this->req->post('id'),
							 'attrid'=>$k,
							 'attrval'=>$v,
						 );
						 $this->db->name('goods_attr')->insert($attrdata);
				}
				
				$result=$this->db->name(strtolower($this->c))->where('id', $this->req->post('id'))->update($data);
				if($result>0){
					$this->success('更新成功',url('admin/product/index',array('page'=>$this->req->get('page'))));
				}elseif($result==0){
					$this->error('更新失败,请至少修改一个信息');
				}else{
					$this->error('更新失败');
				}
		   }else{
			   //输出到前台
			   $result=$this->db->name(strtolower($this->c))->where('id',$this->req->get('id'))->find();
			   //内容的转义去掉
				// var_dump($result);exit; 
			   $result['content']=stripslashes($result['content']);
			   $picDetail=explode(',',$result['photo_detail']);
			   
			   //商品关联的属性信息查到
			   $attr=$this->db->name('goods_attr')
				->alias('ga')
				->join('aa_attribute a','ga.attrid=a.id')
				->where(array(
				   'proid'=>$this->req->get('id'),
				))->order('a.id asc')
				->limit('')
				->select();
				
				//商品快递方式
				$express=$this->db->name('express')->select();
				foreach($express AS $k=>$v){
					 $expList[$v['id']]=$v['exp_name'];	
				}
			   return $this->view->fetch('update',array(
					'title'=>$this->title[$this->c].'更新',
					'a_photo_detail'=>$result['photo_detail'],
					'rs'=>$result,
					'gtype'=>array('下架','上架'),
					'unit'=>array('瓶','罐','件','盒'),
					'is_true'=>array('否','是'),
					'picDetail'=>$picDetail,
					'attr'=>$attr,
					'expList'=>$expList,	
			   ));
		   }
		}else{
			$this->error('无法操作');
		}

    }
	
	//产品del
    public function del()
    {
		$this->checklogin();
		$this->checkLevel();
		if(!empty($_GET['id'])){
		    $this->db->name('goods_attr')->where('proid', $this->req->get('id'))->delete();
			$result=$this->db->name(strtolower($this->c))->where('id', $this->req->get('id'))->delete();
			if($result>0){
					$this->success('删除成功','admin/'.strtolower($this->c).'/index');
			}else{
					$this->error('删除失败');
			}
		}else{
			$this->error('无法操作');
		}

    }
}
?>