$(function(){
	$(".user>ul>li>h2").click(function(){
		var i = $(this).index();
		if($(this).parent().find("ol").css("display") == "none"){
			$(this).parent().find("ol").slideDown();
			$(".user>ul>li>ol").not($(this).parent().find("ol")).slideUp();
			$(this).find("span").addClass("on");
			$(".user>ul>li>h2").find("span").not($(this).find("span")).removeClass("on")
		}else{
			$(this).next("ol").slideUp();
			$(this).find("span").removeClass("on");
		}
	});
})



