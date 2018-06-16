$(function(){
	//my_order选项卡切换
	$(".order_nav>ul>li").click(function(){
		$(".order_nav>ul>li .on").removeClass("on")
		$(this).find("a").addClass("on");
	})
	//商品删除
	$(".my_order>ul>li>p>span").click(function(){
		if(confirm("是否删除商品")){
			$(this).parent().parent().remove();
		}
	})
	//pay支付方式切换
	$(".way>ul>li").click(function(){
		$(this).find("em").addClass("on");
		$(".way>ul>li").find("em").not($(this).find("em")).removeClass("on");
		// $(".way>div>ul").slideUp();
		$(".way>div>ul>li").find("em").removeClass("on");
	})
	$(".way>div>ul>li").click(function(){
		$(this).find("em").addClass("on");
		$(".way>div>ul>li").find("em").not($(this).find("em")).removeClass("on");
		// $(".way>div>ul").slideUp();
		$(".way>ul>li").find("em").removeClass("on");
	})
	//pay下拉选项
	$(".way>div>span").click(function(){
		$(".way>div>ul").slideToggle();
	})
	//是否立即支付
	$(".now").click(function(){
		if(confirm("是否立即支付")){
			location.href="pay_success.html";
		}
	})
	//pro_center选择颜色
	$(".pro_top>.pro_color>ul>li").click(function(){
		$(this).find("span").addClass("on");
		$(".pro_top>.pro_color>ul>li").find("span").not($(this).find("span")).removeClass("on");
	})
	//pro_center选择内存
	$(".pro_top>.pro_memory>ul>li").click(function(){
		$(this).find("span").addClass("on");
		$(".pro_top>.pro_memory>ul>li").find("span").not($(this).find("span")).removeClass("on");
	})
	//领取优惠券和产品分类
	$(".pro_center>ul>li>h2").click(function(){
		if($(this).parent().find("ol").css("display") == "none"){
			$(this).parent().find("ol").slideDown();
			$(".pro_center>ul>li>ol").not($(this).parent().find("ol")).slideUp();
			$(this).find("span").addClass("on");
			$(".pro_center>ul>li>h2").find("span").not($(this).find("span")).removeClass("on")
		}else{
			$(this).next("ol").slideUp();
			$(this).find("span").removeClass("on");
		}
	});
	$(".pro_center>ul>li:nth-child(1)>ol>li").click(function(){
		$(this).text()
		$(this).parent().parent().find("h2").html($(this).text()+"<span></span>");
		$(this).parent().slideUp();
	});
	$(".pro_center>ul>li:nth-child(2)>ol>li").click(function(){
		$(this).text()
		$(this).parent().parent().find("h2").html($(this).text()+"<span></span>");
		$(this).parent().slideUp();
	});
	//查看全部评价
	var len = $(".eva_list>div>ul>li").length;
	var bool = true;
	$(".eva_list>a").click(function(){
		for(var i=1;i<len;i++){
				$(".eva_list>div>ul>li").eq(i).css("display","block");
			}
		$(".eva_list>a").css("display","none");
	});
	$(".evaluation>ul>li").click(function(){
		var j = $(this).index();
		for(var i=1;i<len;i++){
				$(".eva_list>div>ul>li").eq(i).css("display","block");
			}
		$(".eva_list>a").css("display","none");
		$(".eva_list>div").hide().eq(j).show();
	})
	//pro_search清除足迹
	$(".footprint>div>span").click(function(){
		$(".footprint>ul>li").remove();
	})
	//pro_list选项卡切换
	$(".content>.pro_top>ul>li").click(function(){
		$(this).find("a").addClass("on");
		$(".content>.pro_top>ul>li").find("a").not($(this).find("a")).removeClass("on");
	})
});
function focus(){
	$("#focus").focus();
}