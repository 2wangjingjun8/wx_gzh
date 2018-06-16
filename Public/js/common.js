$(function(){

	//自适应

	var winW = $(window).width();

	var constant = winW/7.5;

	$('body,html').css({"font-size":constant});

	$(window).resize(function(){

		var winW = $(window).width();

		var constant = winW/7.5;

		$('body,html').css({"font-size":constant});

	})

	//移动窗口就刷新页面

	$(window).resize(function(){

		location.reload();//刷新页面

	})

	/*banner图片*/

	var liLen=$(".banList ul li").length;//获取li个数

	var winW=$(window).width();//获取屏幕的宽度

	var index=0;//给索引值为0

	var bool = true;//定义开关

	for(var i=0;i<liLen;i++){

		$(".banList ul li").eq(i).css("left",i*winW+"px");

	}//给每一个li分配位置

	



	/*点击圆点切换轮播图*/

	$(".circle li").click(function(){

		if(bool){

			var onIndex=$(".circle li.on").index();//获取当前有.on的索引值

		index=$(this).index();//获取到点击到得索引值

		$(this).addClass("on").siblings().removeClass("on");//siblings()查找同胞元素

		if(index>onIndex){//点击得到的索引值 与 当前索引值 比较

			for(var i=0;i<(index-onIndex);i++){

				//第一张移动

				$(".banList ul li").eq(i).animate({"left":-winW+"px"},function(){

					$(this).css("left",winW+"px").appendTo(".banList ul");

					})//appendTo 同父级元素下 当前加到所有子元素后面 使用的回调函数

				}

				//被点击滑动到当前窗口

			$(".banList ul li").eq(index-onIndex).animate({"left":0})

			for(var i=index-onIndex+1;i<liLen;i++){

				//index-onIndex+1其他滑动

				$(".banList ul li").eq(i).animate({"left":(i-(index-onIndex))*winW+"px"})

				}

			}

		//点击索引值小于当前索引值时

		if(index<onIndex){

			//点击滑动当前窗口

			$(".banList ul li").eq(0).animate({"left":winW+"px"})

			//其他跟随滑动

			for(var i=liLen-1;i>liLen-1-(onIndex-index);i--){

				$(".banList ul li").eq(i).css("left",(i-liLen)*winW+"px").animate({"left":(i-liLen+(onIndex-index))*winW+"px"},function(){

					$(this).prependTo(".banList ul");	

					})

				}

				

			}

		}

	})

	

	/*手势滑动*/

	$(".banList").swipe({

		swipeLeft: next,

		swipeRight:	prve

	})

	

	function next(){

		if(bool){

			bool=false;

			index=$(".circle li.on").index();

			index++;

			if(index==liLen){

				index=0;	

			}//siblings()同类元素

			$(".circle li").eq(index).addClass("on").siblings().removeClass("on");

			for(var i=1;i<liLen;i++){

				$(".banList ul li").eq(i).animate({"left":(i-1)*winW+"px"})

			}	

			

			$(".banList ul li").eq(0).animate({"left":-winW+"px"},function(){

				$(this).appendTo(".banList ul").css("left",(liLen-1)*winW+"px");	

				bool=true;//回调函数

			})	

		}

	}

	function prve(){

		if(bool){

			bool=false;

			index=$(".circle li.on").index();

			index--;

		

		if(index<0){

			index=liLen-1;

			}

			//siblings()同类元素

			$(".circle li").eq(index).addClass("on").siblings().removeClass("on");

			for(var i=0;i<liLen-1;i++){

				$(".banList ul li").eq(i).animate({"left":(i+1)*winW+"px"})	

			}	

			$(".banList ul li").eq(-1).css("left",-winW+"px").animate({"left":0},function(){

				$(this).prependTo(".banList ul")	

				bool=true;//回调函数

			})	

		}

	}

	

	/*自动轮播*/

	lunbo=setInterval(next,3000)



	//导航

	$(".header>b").click(function(){

		$(".header>ul").fadeToggle();

	});

});

function goSearch(){

	location.href="pro_search.html";

}

