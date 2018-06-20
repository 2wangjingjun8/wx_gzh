<?php
namespace app\admin\controller;
use think\Controller;
use think\db;//用数据库
use think\View;//用视图
use think\Session;//用session
use think\Request;//提交用
/****
----------后台公共控制器
****/
class Publics extends Controller
{
	public $view;///公共属性 保存运行成功的视图类
	public $db;//公共属性 保存运行成功的数据库类
	public $req;//公共属性 保存运行成功的提交类
	public $c;//  Admin/news/index  =>  news   控制器名
	public $m;//  Admin/news/index  =>  Admin  模块文件夹
	public $a;//  Admin/news/index  =>  index  控制器里面方法名
	public $prePage=1;//公共属性,分页，每页显示一条
	//公共标题库
	public $title=array(
	    'Adminuser'=>'管理员',
	    'Product'=>'产品',
	    'Brand'=>'品牌',
	    'Hotword'=>'热词',	
	    'Comment'=>'评论',	
	    'Article'=>'帮助',	
	    'Group'=>'管理员组别',
	    'Brand'=>'产品品牌',
	    'Member'=>'会员',
	    'Memberlevel'=>'会员等级',
	    'Waybill'=>'运单',
	    'Express'=>'快递方式',
	    'Order'=>'订单',
	    'Orderaddress'=>'订单地址',
	    'Paytype'=>'支付方式',
	    'Payrec'=>'支付记录',
	    'Category'=>'产品分类',
	    'Articlecat'=>'帮助分类',
	    'Inventory'=>'库存',
	    'Attribute'=>'产品属性',
	    'Adminmenu'=>'后台导航',
	    'Homemenu'=>'前台导航',
	);
	//字段库,不显示
	public $field=array(
	    'Adminuser' =>'email,password,error_times',
	    'Product'   =>'p.id,p.pro_name,p.ctime,p.shop_price,p.gtype,c.cat_name,b.brand_logo,p.pro_photo,p.is_new,p.is_good,p.is_hot',
	    'Brand'     =>'brand_info',
	    'Hotword'   =>'',
	    'Comment'   =>'',
	    'Article'   =>'catid,content',
	    'Group'     =>'rulestr',
	    'Member'     =>'error_times,repassword,password,portrait,province,city,district',
	    'Memberlevel'=>'',
	    'Waybill'=>'',
	    'Express'=>'exp_info',
	    'Order'=>'id,order_no,order_msg,order_total,ctime,inventory_id,paytype,pay_id,memberid',
	    'Orderaddress'=>'province,city,district',
	    'Paytype'=>'pay_intro',
	    'Payrec'=>'',
	    'Cateattr'=>'',
	    'Category'=>'',
	    'Articlecat'=>'',
	    'Inventory'=>'i.id,i.proid,p.pro_name,i.attrid,i.attrval,i.num',
	    'Attribute'=>'',
	    'Adminmenu'=>'',
	    'Homemenu'=>'',
	    'Productpage'=>'',
	);
	//显示字段名
	public $item=array(
	    'Adminuser' =>array('管理员id','账户名','管理员姓名','性别','创建时间','管理员qq','管理员住址','管理员电话'),
	    'Product'   =>array('产品id','产品名称','发布时间','本店价','是否上架','所属分类','所属品牌','产品图片','是否新品','是否精品','是否热品'),
	    'Brand'     =>array('品牌表id','品牌名称','品牌logo图','官网地址'),
	    'Hotword'   =>array('热词id','关键词','被搜索次数'),
	    'Comment'   =>array('评论id','用户id','评论内容','评价等级','评论时间'),
	    'Article'   =>array('文章id','文章标题','创建时间','文章分类id'),
	    'Articlecat'   =>array(),
		'Group'     =>array('组别id','组别名','权限描述'),
		'Brand'     =>array('品牌id','品牌名','品牌logo','官网地址'),
		'Member'     =>array('会员id','账号名','姓名','性别','电话','邮箱','会员住址','添加时间'),
		'Memberlevel'     =>array('id','会员等级','最小积分','最大积分'),
		'Waybill'     =>array('id','运单号','地址id','发货时间','签收时间','目前状态'),
		'Express'     =>array('id','快递公司名','快递公司地址'),
		'Order'     =>array('id','订单编号','订单留言','订单总价','下单时间','库存ID','支付状态','支付方式','会员id'),
		'Orderaddress'     =>array('id','收货人','会员id','收货地址','联系电话','是否默认地址'),
		'Paytype'     =>array('id','支付方名称','官方网址','logo'),
		'Payrec'     =>array('id','订单id','支付时间'),
		'Category'     =>array('id','产品分类名','分类pid'),
		'Attribute'     =>array('id','属性名','属性类型','是否筛选的属性'),
		'Inventory'     =>array('id','商品名称','商品属性','仓库数量'),
		'Cateattr'     =>array('id','分类id','属性id','属性值'),
		'Adminmenu'     =>array('id','分类id','属性id','属性值'),
		'Homemenu'     =>array('id','分类id','属性id','属性值'),
	);
	/*后台公共控制器构造函数*/
	public function __construct()
	{
		$this->view=new View();//运行视图类，保存在公共属性view
		$this->db=new db\Query();//运行数据库类，保存在公共属性db
		$this->req=\think\Request::instance();
	    //查询出m,c,a
		$this->m = $this->req->module();
		$this->c = $this->req->controller();
		$this->a = $this->req->action();	
		
		//模板输出变量    $this->view->变量名=变量值
		$this->view->baseurl="http://junice.51zuopin.com/public/";//公共变量：网站域名
		$this->view->category=$this->getCategory('商品分类');
		$this->view->category1=$this->getCategory('帮助分类');
		$this->view->adminnav=$this->getAdminNav();
		$this->view->c=strtolower($this->req->controller());
		if(!empty($_FILES)){
			  //判断上传的只有一副图片
			  if(count($_FILES)==1){
				   foreach($_FILES AS $k=>$v){
					   if($_FILES[$k]['error']!=4){
					       //执行单图上传
					       $_POST[$k]=$this->singleUpload($k);
					   }else{
						   $_POST[$k]=''; 
					   }
				   }
			  }else{
				  
				   //如果多图和单图全部都在$_FILES
				   //先判断哪一个是单图，那一个多图
				   foreach($_FILES AS $k=>$v){				   
					   if(count($v['name'])>1){
						   foreach($_FILES[$k]['name'] AS $key=>$value){
							   if(empty($value)){  
								   $_POST[$k][]=array();
							   }else{
								   $_FILES['photos']=array(
								      'name'=>$_FILES[$k]['name'][$key],
									  'type'=>$_FILES[$k]['type'][$key],
									  'tmp_name'=>$_FILES[$k]['tmp_name'][$key],
									  'error'=>$_FILES[$k]['error'][$key],
									  'size'=>$_FILES[$k]['size'][$key],
								   );
								   $_POST[$k][]=$this->singleUpload('photos');
								   $_FILES['photos']=array();
							   }
                               
						   }
					   }else{
					       if($_FILES[$k]['error']!=4){
					          $_POST[$k]=$this->singleUpload($k);
						   }else{
							  $_POST[$k]=''; 
						   }	   
					   }
				   }
			  }	  
		}
	}
	
	//检查登录
	public function checklogin()
	{
		if($this->req->isAjax()){
			if(session('?AdminUser') == false){
				echo json_encode('no1');
				exit;
			}
		}else{
			if(session('?AdminUser') == false){
				$this->error('请先登录','/admin/index/login');
			}
		}
	}
	
	//检查权限
	function checkLevel(){
		//拿即将要访问的地址去查nav表拿到相关id
		$group = $this->db->name('adminmenu')->where(array(
			'fun'=>$this->c,
			'act'=>$this->a,
		))->find();
		// dump($group);exit;
		$scopearr = explode(',',session('AdminUser.admin_scope'));
		// dump($scopearr);exit;
		//查看id是否在允许操作的范围
		if(!in_array($group['id'],$scopearr)){		
			echo "<script>alert('没有权限操作');window.history.back();</script>";exit;
		}
    }

	
	/*
	  单图上传
	*/	
 	public function singleUpload($name){
		// 获取表单上传文件 例如上传了001.jpg
		$file = request()->file($name);
		
		// 移动到框架应用根目录/public/uploads/ 目录下
		if($file){
			$info = $file->move(ROOT_PATH . 'public/uploads');
			// dump($info);exit;
			if($info){
              				
			    // 成功上传后 获取上传信息
				// 输出  jpg  图片后缀
				//echo $info->getExtension().'<br/>';
				// 输出  保存路径
				//echo $info->getSaveName().'<br/>';
				// 输出 
				//echo $info->getFilename().'<br/>';  
			
			    
				return 'uploads/'.$info->getSaveName();
			}else{
				// 上传失败获取错误信息
				$this->error($file->getError());
			}
		}
	} 
	/*
	  多图上传
	*/
 	public function multiUpload($name){
		// 获取表单上传文件
		$files = request()->file($name);
		// dump($files);
		$res=array();
		foreach($files as $file){
			// 移动到框架应用根目录/public/uploads/ 目录下
			$info = $file->move(ROOT_PATH . 'public/uploads');
		    // dump($info);
			if($info){
				$res[]='uploads/'.$info->getSaveName();	
				// dump($res);exit;
			}else{
				// 上传失败获取错误信息
				$this->error($file->getError()); 
			}    
		}
		
		return implode(',',$res);
    } 
	
	//获取后台导航数据
	public function getAdminNav()
	{
		$adminnav = $this->db->name('adminmenu')->select();
		return $adminnav;
	}
	
	//获取分类数据
	public function getCategory($cat_name){
		$result1=$this->db->name('category')->where(array(
	         'pid'=>0,
			 'cat_name'=>$cat_name
	    ))->order('id desc')->limit('')->select();
	   //拿第一层的id作为第二层分类的pid去查询
	    $result2=$this->db->name('category')->where(array(
	         'pid'=>$result1[0]['id'],
	   ))->order('id desc')->limit('')->select();
	    foreach($result2 AS $k=>$v){
		   //
		   $result3=$this->db->name('category')->where(array(
	         'pid'=>$v['id'],
	       ))->order('id desc')->limit('')->select();
		   $result2[$k]['sonCat']=$result3;
        }	   	   
		$html='';
		$html.='<div class="form-group">';
		$html.='<label>所属分类</label>';
			$html.='<select name="catid">';
				$html.='<option value="">...请选择...</option>';
				foreach($result2 AS $k=>$v){
				  $html.='<option value="'.$v['id'].'">'.$v['cat_name'].'</option>';
				  if(!empty($v['sonCat'])){
					    foreach($v['sonCat'] AS $key=>$value){
					       $html.='<option value="'.$value['id'].'">-----------'.$value['cat_name'].'</option>';
						}
				  }
				}
			$html.='</select>';
		$html.='</div>';							
		$html.='</div>';
		//返回组装的分类下拉
        return $html;		
	}
	
	public function formatIdArray($data)
	{
		foreach($data AS $k=>$v){
			$temp[$v['id']]=$v;
		}
		$data=$temp;
		return $data;
	}
	
	
	//数据处理
	public function data_handle($data){
		switch($this->c){
			case 'Adminuser':
			       if($this->a=='add'){
			  	        $data['ctime']=date('Y-m-d H:i:s');
			            $data['password']=md5($_POST["password"]);
				   }elseif($this->a=='update'){
						if($data['password']!=""){
							$data['password']=md5($_POST["password"]);
						}else{
							unset($data['password']);
						}					   	   
				   }
			       break;
           case 'Product':
			       if($this->a=='add'){
			  	        $data['ctime']=date('Y-m-d H:i:s');
				   }elseif($this->a=='update'){
				   	    $data['ctime']=date('Y-m-d H:i:s',time());
				   }
			       if($this->a=='add'){
			  	        $data['recommand_start']=date('Y-m-d H:i:s');
				   }elseif($this->a=='update'){
				   	    $data['recommand_start']=date('Y-m-d H:i:s');
				   }
			       if($this->a=='add'){
			  	        $data['recommand_end']=date('Y-m-d H:i:s');
				   }elseif($this->a=='update'){
				   	    $data['recommand_end']=date('Y-m-d H:i:s');
				   }
				   if(!empty($data['explist'])){
					   
					   $data['explist']=implode(',',$data['explist']);
					   $data['explist']=substr($data['explist'],0,-1);
				   }
			       break;		
           case 'Article':
			       if($this->a=='add'){
			  	        $data['ctime']=date('Y-m-d H:i:s');
				   }elseif($this->a=='update'){
				   	    $data['ctime']=date('Y-m-d H:i:s');
				   }
			       break;			
		}
		return $data;
	}
}
