$(function(){
	//全选
	$("#all").change(function(){
		if($("#all").prop("checked")){
			$(".shop_cart [type=checkbox]").prop("checked",$("#all").prop("checked"));
			$(this).next("label").addClass("on");
			//全选按钮点击时，勾选所有的checkbox选项对应label的添加样式on
			$(".shop_cart [type=checkbox]").each(function(){
				$(this).parent().addClass("on");
			})
		}else{
			$(".shop_cart [type=checkbox]").prop("checked",$("#all").prop("checked"));
			$(this).next("label").removeClass("on");
			//全选按钮点击时，勾选所有的checkbox选项对应label的添加样式on
			$(".shop_cart [type=checkbox]").each(function(){
				$(this).parent().removeClass("on");
			})
		}
		//计算总价
		proSum();
	});
	//单选
	$(".shop_cart>ul").on("change","[type=checkbox]",function(){
		if($(this).prop("checked")){
			$(this).parent().addClass("on");
		}else{
			$(this).parent().removeClass("on");
		}
		//将复选框的事件委托给父级
		var bool = ($(".shop_cart>ul>li [type=checkbox]").length == $(".shop_cart>ul>li [type=checkbox]:checked").length);
		$("#all").prop("checked",bool);		
		if(bool){
			$("#all").next("label").addClass("on");
		}else{
			$("#all").next("label").removeClass("on");
		}
		//计算总价
		proSum();
	});
	
	
	//判断是否登陆 在进去提交订单
	// $(".calcu").click(function(){
	// 	console.log(isLogin())
	// 	if(isLogin()){
	// 		//跳转到支付页面
	// 	}else{
	// 		//没有登录 跳转到登录  登录之后将本地存储的数据上传到服务器
	// 		location.href = "login.html";
	// 	}
	// });

	//初始化购物车全选框
	// if($(".shop_cart>ul [type=checkbox]").length > 0){
	// 	var bool = ($(".shop_cart>ul [type=checkbox]").length == $(".shop_cart>ul [type=checkbox]:checked").length);
	// 	$("#all").prop("checked",bool);	
	// };
	
});

//自动选选
function AllCheck(){
	$("#all").prop("checked",true)
	$("#all").next("label").addClass("on");
	$(".shop_cart [type=checkbox]").prop("checked",true);
	//全选按钮点击时，勾选所有的checkbox选项对应label的添加样式on
	$(".shop_cart [type=checkbox]").each(function(){
		$(this).parent().addClass("on");
	})
}
//往购物车里动态添加商品
function loadCar(){
	var carData = JSON.parse(getCar());
	if(carData){
//		if(carData.length){
//			var checkedAll=document.getElementById("all");
//			checkedAll.checked=true;
//			checkedAll.nextSibling.classList.add("on");
//		}
		var tbody = document.getElementById("carUl");
		tbody.innerHTML="";
		var html="";
		for(var i=0;i<carData.length;i++){
				html+='<li data-id="'+carData[i].id+'" class="number">'+
					'<label for="data_'+(i+1)+'"><input type="checkbox" id="data_'+(i+1)+'" class="checked"></label>'+
					'<a href="pro_details.html"><img src="'+carData[i].imgSrc+'" alt="img"></a>'+
					'<div>'+
						'<h2><a href="pro_details.html">H1便携耳放</a></h2>'+
						'<p><span>输入灵敏度：</span><em>520mV/47KΩ(低增益)</em></p>'+
						'<a href="javascript:void(0);" onclick="reduce(this)">-</a>'+
						'<input type="text" value="'+carData[i].num+'" onchange="numChange(this)" class="num">'+
						'<a href="javascript:void(0);" onclick="add(this)">+</a>'+
					'</div>'+
					'<div><b onclick="deletePro(this)"></b><span>原价:¥86</span><i>原价:</i><em>¥</em><em class="price">'+carData[i].price+'</em></div>'+
				'</li>';
			
			//将html添加到tbody
			tbody.innerHTML=html;
		}
		//自动全选
		AllCheck();
		//计算总价
		proSum();
	};
};
//删除商品
function deletePro(obj){
	//获取当前对象的li父元素
	var li = obj.parentNode.parentNode;
	console.log(li);
	//获取li父元素的id
	var id = li.getAttribute("data-id");
	if(confirm("是否删除")){
		//li父元素的id对应删除li
		delProduct(id);
		li.remove();
	}else{
		return;
	}
	//计算总价
	proSum();
}
//商品数量加操作
function add(obj){
	//获取当前对象的上上机兄弟元素
	var prd_num = obj.previousSibling;
	//获取对应id
	var id = obj.parentNode.parentNode.getAttribute("data-id");
	//获取对应值
	var num = prd_num.value;
	if(isNaN(num)){
		num = 1;
	}else{
		num = parseInt(num);
	}
	num+=1;
	//导入
	prd_num.value = num;
	//传入改变本地数据的数量
	changeCarNum(id,num);
	//计算总价
	proSum();
};
//商品数量改变的操作
function numChange(obj){
	//获取当前value值
	var num = obj.value;
	//获取当前元素父父父父id
	var id = obj.parentNode.parentNode.getAttribute("data-id");
	if(isNaN(num)){
		num = 1;
	}else if(num<1){
		num = 1;
	}
	//取整
	num = parseInt(num);
	obj.value = num;
	//传入改变本地数据的数量
	changeCarNum(id,num);
	//计算总价
	proSum();
};
//商品数量减操作
function reduce(obj){
	//获取当前元素上一级
	var prd_num = obj.nextSibling;
	//获取当前元素父父父父id
	var id = obj.parentNode.parentNode.getAttribute("data-id");
	//当前元素值
	var num = prd_num.value;
	if(isNaN(num)){
		num = 1;
	}else{
		num = parseInt(num);
	}
	num-=1;
	if(num<1){
		num=1;
	}
	//赋值
	prd_num.value = num;
	//传入改变本地数据的数量
	changeCarNum(id,num);
	//计算总价
	proSum();
};
//商品价值的总计
function proSum(){
	var tr = document.getElementsByClassName("number");
	var sum=0;
	var totalSum=document.getElementById("sum");
	for(var i=0;i<tr.length;i++){
		var checked=tr[i].firstChild.firstChild;
		var price=tr[i].getElementsByClassName("price")[0].innerHTML;
		var piece=tr[i].getElementsByClassName("num")[0].value;
		if(checked.checked==true){
		  sum+=price*piece;
		}
	}
	sum=sum.toFixed(2);
	totalSum.innerHTML=sum;

}
//改变本地数据的数量
function changeCarNum(id,num){
	//将json字符串转化json对象
	var carData = JSON.parse(getCar());
	for(var i=0;i<carData.length;i++){
		if(carData[i].id == id){
			carData[i].num = num;
			carData[i].totalPrice = (carData[i].price*num).toFixed(2);
			break;
		}
	}
	addCar(carData);
};
//判断是否登陆 在进去提交订单
function goLoad(){
	var data=localStorage.getItem("user");
	var input = document.getElementsByClassName("checked");
	console.log(input)
	if(input.length==0){
		alert("请先添加商品。")
		return;
	}
	var index=0;
	for(var i=0;i<input.length;i++){	
		if(input[i].checked==false){
			index+=1;
		}
		if(index==input.length){
			alert("请先勾选商品再提交订单。")
			return;
		}
		if(input[i].checked==true){
			if(data==null){
				var con=confirm("请先登录再进行提交订单。")
				if(con){
					location.href="login.html";
				}else{
					return false;
				}
				break;
			}else{
				location.href="order.html";
			}
		}
	}
	
};