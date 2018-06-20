<?php
namespace app\admin\controller;
/*
后台产品套餐控制器
*/
class Productpage extends Publics
{
	
	/*
	后台列表方法
	*/
    public function index(){
		$this->checklogin();
		$this->checkLevel();
	  if ($this->req->isPost()){
           //保存提交的信息
		    $data=$_POST;
			$data['package_info']=implode(',',$data['package_info']);
			// $data['inventory_id']=implode(',',$data['inventory_id']);
			//添加数据到数据库
			$rs=$this->db->name('goods_package')->insert($data);
			//获取添加成功id
			$insertId =$this->db->name('goods_package')->getLastInsID();
			if($insertId>0){
					 $this->success('添加套餐成功');
			}else{
					 $this->error('添加套餐失败'); 
			}							  
	  }else{
     	 //查询产品信息并显示，以便大家知道为什么产品添加套餐
		 $goods=$this->db->name('product')->where('id',$this->req->get('proid'))->find();
		//查询产品套餐信息
        $rs=$this->db->name('goods_package')
/* 		->alias('gp')
		->join('aa_inventory i','gp.inventory_id=i.id') */
		->field($this->field[$this->req->controller()])
		->where(array(
 		   'proid'=>$this->req->get('proid'),            
		))
		->select(); 
		// dump($rs);
		//查找套餐关联可选库存
		$attribute=$this->db->name('inventory')->where(array(
		   'proid'=>$this->req->get('proid'),
		))->select();
		// dump($attribute);exit;
       $html='';
		foreach($rs AS $k=>$v){
					 $html.='<tr class="list-group-item-success">';	
                                            foreach($v AS $key=>$value){ 	
                                              if($key=='attrval' || $key=='proid'  || $key=='pakage_info' || $key=='inventory_id'){	
											      continue;
                                              }											  
											  $html.='<td>'.$value.'</td>';	
										    }
											$html.='<td><a class="btn btn-xs btn-primary update"  href="index.php/'.$this->req->module().'/inventory/update?proid='.$v['proid'].'&id='.$v['id'].'"><i class="glyphicon glyphicon-search" ></i>商品补货</a></td>';		
					$html.='</tr>';			
		}			
		 /*----------------------------------------------------------------------------------------------------*/
		 	/* $inventoryLink=$this->db->name('inventory')
			->alias('i')
			->join('aa_product p','i.proid=p.id')
			->field($this->field['Inventory'])
			->where(array(
			    'i.proid'=>$this->req->get('proid')
			))
            ->select();
			// dump($inventoryLink);exit;
		 	//查找属性名
			$attribute=$this->db->name('attribute')->where(array(
			   'attrtype'=>1,
			))->select();
			//dump($attribute);
			//调用函数把数组的默认下标全部替换成id
			$attribute=$this->formatIdArray($attribute);
			$checkbox_html='';
			foreach($inventoryLink AS $k=>$v){
						 $checkbox_html.='<tr class="list-group-item-success">';	
												foreach($v AS $key=>$value){ 	
												  if($key=='attrid'){
													   $attridtemp=explode('|',$value);
													   $attrvaltemp=explode('|',$v['attrval']);
													   //把属性的下标和值拼接在p标签里进行输出
													   foreach($attridtemp AS $key1=>$value1){
															 $attridtemp[$key1]='<p>'.$attribute[$value1]['attrname'].':'.$attrvaltemp[$key1].'</p>';
													   }
													   $value=implode('',$attridtemp);
												  }	
												  if($key=='attrval' || $key=='proid' || $key=='pro_name'){	
													  continue;
												  }	
												  if($key=='id'){	
													  $checkbox_html.='<td><input type="checkbox" name="inventory_id[]" value="'.$value.'"></td>';	
												  }								  
												  $checkbox_html.='<td>'.$value.'</td>';	
												}	
						$checkbox_html.='</tr>';			
			}			 */
		 /*----------------------------------------------------------------------------------------------------*/
		 
			 // $rs=$this->db->name('fitting')->where(array())->select();
			 // dump($rs);exit;
		 
		//加载模板
		return $this->view->fetch('index',array(
		     'title'=>'套餐列表',
			 'proid'=>$this->req->get('proid'),
			 'fitting'=>$this->db->name('fitting')->where(array())->select(),
			 'goods'=>$goods,
			 'html'=>$html,
		));	
	  }		
	}
	
}