$(function(){
	$("#btn_login").click(function(event){
		//清除默认事件
		event.preventDefault();
		var user = $("#user").val();
		var pasw = $("#pasw").val();
		if(user.trim()===""){
			$(".user_tip").show();
			$("#user").focus();
			return false;
		}else if(pasw.trim()===""){
			$(".pasw_tip").show();
			$("#pasw").focus();
			return false;
		}
		//限制按钮
		$("#btn_login").val("登录中...(^_^)").prop("disabled",true);
		//ajax提交登录请求
		$.ajax({
			type:"post",
			url:"admin/login.php",
			async:true,
			data:{
				"user":$("#user").val(),
				"pass":$("#pasw").val() //加密
			},
			success:function(msg){
				//恢复按钮
				$("#btn_login").val("登录").prop("disabled",false);
//				console.log(msg);
				if(msg === "login success"){
					localStorage.setItem("user",user);//跨页面验证设置本地存储
					location.href = "homepage.html";
				}else if(msg === "password error"){
					$(".pasw_tip").text("密码错误");
					$(".pasw_tip").show();
				}else if(msg === ""){
					$(".user_tip").text("用户名错误");
					$(".user_tip").show();
				}
			},
			error:function(){
				$("#btn_login").val("登录").prop("disabled",false);
				alert("服务器连接失败，请检查网络");
			}
		});
	});
	//用户验证
	$("#user").change(function(){
		var user = $("#user").val();
		var reg_user = /^[a-zA-Z]\w{7,15}$/;
		if(reg_user.test(user)){
			$(".user_tip").hide();
		}else{
			$(".user_tip").show();
			return false;
		}
	});
	//密码验证
	$("#pasw").change(function(){
		var pasw = $("#pasw").val();
		var reg_pasw = /^[\w!@#$%^&*,.<>]{6,18}$/;
		if(reg_pasw.test(pasw)){
			$(".pasw_tip").hide();
		}else{
			$(".pasw_tip").show();
			return false;
		}
	});
});
