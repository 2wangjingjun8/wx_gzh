<?php
namespace app\admin\controller;
/*
后台库存控制器
*/
class Inventory extends Publics
{
    public function index()
    {
		$this->checklogin();
		$this->checkLevel();
		//直接执行sql语句
		$result=$this->db->name(strtolower($this->c))
		->alias('i')
		->join('aa_product p','i.proid=p.id')
		->field($this->field[$this->c])
		->where(array())
		->limit('')
		->paginate($this->prePage=5);
		// var_dump($result);exit;
		
		//查找属性名
		$attribute=$this->db->name('attribute')->where(array(
		   'attrtype'=>1,
		))->select();
		// var_dump($attribute);
		//调用函数把数组的默认下标全部替换成id
        $attribute=$this->formatIdArray($attribute);
/* 		foreach($attribute AS $k=>$v){
			$temp[''.$v['id'].'']=$v;
		}
		$attribute=$temp; */
		// var_dump($attribute);exit;
		
		$html='';
 		foreach($result AS $k=>$v){
						 $html.='<tr class="list-group-item-success">';	
                            foreach($v AS $key=>$value){
                                              if($key=='attrid'){
												   $attrid=explode('|',$value);
											       $attrval=explode('|',$v['attrval']);
												   //把属性的下标和值拼接在p标签里进行输出
												   foreach($attrid AS $key1=>$value1){
                                                         $attrid[$key1]='<p>'.$attribute[$value1]['attrname'].':'.$attrval[$key1].'</p>';
												   }
												   // dump($attrid);exit;
												   $value=implode('',$attrid);
											  }
                                              if($key=='attrval' || $key=='proid'){
											      continue;
                                              }
							   $html.='<td>'.$value.'</td>';
							}
							$html.='<td>
							<a class="btn btn-xs btn-primary update"  href="admin/'.strtolower($this->c).'/update?proid='.$v['proid'].'&id='.$v['id'].'"><i class="glyphicon glyphicon-search" ></i>商品补货</a></td>';	
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
	//库存添加
    public function add()
    {
		$this->checklogin();
		$this->checkLevel();
		if ($this->req->isPost()){
		    //保存提交的信息
		    $data=$_POST;
            $data=$this->data_handle($data);
			$data['attrval']=implode('|',$data['attrval']);
			// var_dump($data);exit;
			//检查库存商品是否已经存在,存在就不能添加重复
			$Inventory=$this->db->name('Inventory')->where(array(
			   'proid'=>$data['proid'],
			   'attrval'=>$data['attrval'],
			))->find();
			// var_dump($data);exit;
			if(empty($Inventory)){
				//添加数据到数据库
				$result=$this->db->name(strtolower($this->c))->insert($data);
				// var_dump($result);exit;
				if($result>0){
					$this->success('添加成功');
					//$this->redirect('admin/'.strtolower($this->c).'/index',Null,3,'添加成功，页面跳转中......');
				}else{
					$this->error('添加失败');
				}
			}else{
				 $this->error('商品已经存在,不需要添加'); 		
			}
	   }else{
			//关联属性表和产品属性表，关联字段是属性表id和产品属性表的属性id
			$goodsAttr=$this->db->name('attribute')
			->alias('a')
			->join('aa_goods_attr ga','a.id=ga.attrid')
			->where(array(
			   'a.attrtype'=>1,
			   'ga.proid'=>$this->req->get('proid')
			))
			->select();
			//初始化一个空的id字符串
			$attrid='';
			foreach($goodsAttr AS $k=>$v){
				$goodsAttr[$k]['attrval']=explode(';',$v['attrval']);
				$attrid.=$v['id'].'|';
			}
			// var_dump($goodsAttr);exit;
			$attrid=substr($attrid,0,-1);
			//查询产品信息并显示，以便大家知道为什么产品添加库存
			$goods=$this->db->name('product')->where('id',$this->req->get('proid'))->find();
			// var_dump($goods);exit;
			
			//查找库存与产品表关联的数据
			$result=$this->db->name(strtolower($this->c))
				->alias('i')
				->join('aa_product p','i.proid=p.id')
				->field($this->field[$this->c])
				->where(array(
				  'i.proid'=>$this->req->get('proid')
				))
				->limit('')
				->select();
				
			// var_dump($result);
			//查找属性名
			$attribute=$this->db->name('attribute')->where(array(
			   'attrtype'=>1,
			))->select();
			// var_dump($attribute);exit;
			//调用函数把数组的默认下标全部替换成id
			$attribute=$this->formatIdArray($attribute);
				
			$html='';
			foreach($result AS $k=>$v){
							 $html.='<tr class="list-group-item-success">';	
								foreach($v AS $key=>$value){
												  if($key=='attrid'){
													   $attridtemp=explode('|',$value);
													   $attrvaltemp=explode('|',$v['attrval']);
													   //把属性的下标和值拼接在p标签里进行输出
													   foreach($attridtemp AS $key1=>$value1){
															 $attridtemp[$key1]='<p>'.$attribute[$value1]['attrname'].':'.$attrvaltemp[$key1].'</p>';
													   }
													   // dump($attrid);exit;
													   $value=implode('',$attridtemp);
												  }
												  if($key=='attrval' || $key=='proid'|| $key=='pro_name'){
													  continue;
												  }
								   $html.='<td>'.$value.'</td>';
								}
								$html.='<td>
								<a class="btn btn-xs btn-primary update"  href="admin/'.strtolower($this->c).'/update?proid='.$v['proid'].'&id='.$v['id'].'"><i class="glyphicon glyphicon-search" ></i>商品补货</a></td>';	
							$html.='</tr>';
			}
			
		   return $this->view->fetch('add',array(
			   'title'=>$this->title[$this->c].'添加',	
			   'name'=>$this->title[$this->c].'中心',
			   'proid'=>$this->req->get('proid'),
			   'attrid'=>$attrid,
			   'goodsAttr'=>$goodsAttr,
			   'goods'=>$goods,
			   'html'=>$html
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
				$data=$_POST;
                $data=$this->data_handle($data);
						$result=$this->db->name(strtolower($this->c))->where('id', $this->req->post('id'))->update($data);
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
				//查询产品信息并显示，以便大家知道为什么产品添加库存
				$goods=$this->db->name('product')->where('id',$this->req->get('proid'))->find();
				
				//查找属性名
				$attribute=$this->db->name('attribute')->where(array(
				   'attrtype'=>1,
				))->select();
				//dump($attribute);
				//调用函数把数组的默认下标全部替换成id
				// $attribute=$this->formatIdArray($attribute);
				foreach($attribute AS $k=>$v){
					$temp[''.$v['id'].'']=$v;
				}
				$attribute=$temp;
				// dump($attribute);
			   //把数据表里面的attrid和attrval分割成数组
			   $attrid=explode('|',$result['attrid']);
			   $attrval=explode('|',$result['attrval']);
			   
				// dump($attrid);
			   //初始化一个空白字符串 
			   $attrStr='';
				//把属性的下标和值拼接在p标签里进行输出
			   foreach($attrid AS $key1=>$value1){
					   $attrStr.='<p>'.$attribute[$value1]['attrname'].':'.$attrval[$key1].'</p>';
			   }
		       // dump($attrStr);exit;
		   
			   return $this->view->fetch('update',array(
					'title'=>$this->title[$this->c].'更新',
					'rs'=>$result,
			        'name'=>$this->title[$this->c].'中心',
					'proid'=>$this->req->get('proid'),	
					'goods'=>$goods,
					'attrStr'=>$attrStr	
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
		if(!empty($_GET['id'])){
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