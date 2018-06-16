$(function(){
	//初始化导航栏
	$.ajax({
		type:"get",
		url:"admin/sortnav.json",
		async:true,
		success:function(data){
//			console.log(data);
			var nav = data.nav;
			var len = nav.length;
			$(".con_nav>ul").html("");
			for (var i=0;i<len;i++) {
				var item = "<li data-id='"+nav[i].id+"'><a href='javascript:void(0);'>"+nav[i].title+"</a></li>";
				//$()将HTML字符串转成JQ的对象
				$(".con_nav>ul").append($(item));
			}
			//让页面一开头显示选中第一个，并且获取第一个的数据
			$(".con_nav>ul>li:eq(0)").find("a").addClass("on");
			var id = $(".con_nav>ul>li:eq(0)").data("id");
			getContent(id);
		}
	})
	$(".con_nav>ul").on("click","li",function(){
//		console.log($(this).find("a").attr("class") === "on")
//		var i = $(this).index();
		if($(this).find("a").attr("class") === "on"){
			return;//判断当前元素是否已经选中
		}
		//选中新的元素
		$(".con_nav>ul>li").find("a").removeClass("on");
		$(this).find("a").addClass("on");
		//获取li里面的id
		var id = $(this).data("id");//JQ用于获取data自定义属性的方法
		getContent(id);		
	});
	
	//根据ID获取页面的显示内容
	function getContent(id){
		$.ajax({
			type:"get",
			url:"admin/sort.json",
			async:true,
			data:{
				"id":id
			},
			success:function(data){
				var sort = data.sort["so"+id];
				//清空container
				$(".pro_sort>.bottom").html("");//;
				$div = $("<div></div>");
				$ul = $('<ul class="goodslist"></ul>');
				for (var i=0;i<sort.length;i++) {
					var list = sort[i];
						$li = $('<li>'+
									'<a href="pro_center.html"><img src='+list.src+' /></a>'+
								'<a href="pro_center.html">'+list.title+'</a>'+'</li>');			
					$ul.append($li);
				}
				$div.append($ul);
				$(".pro_sort>.bottom").append($div);
			}
		});
	}
	
	
});
