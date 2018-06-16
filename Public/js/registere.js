$(function(){
	function clearForm(){
		var user = $("#user").val("");
		var pasw = $("#pasw").val("");
		var auth = $("#authCode").val("");
	};
	//点击判断
	$("#btn_reg").click(function(){
		//获取对应的value值
		var user = $("#user").val();
		var pasw = $("#pasw").val();
		var auth = $("#authCode").val();
		var user_tip = $(".user_tip");
		var pasw_tip = $(".pasw_tip");
		var auth_tip = $(".auth_tip");
		var check = $("#checkbox");
//		//处理默认事件
//		event.preventDefault();
		if(user.trim()===""){
			user_tip.show();
			$("#user").focus();
			return false;
		}else if(pasw.trim()===""){
			pasw_tip.show();
			$("#pasw").focus();
			return false;
		}else if(auth.trim()===""){
			auth_tip.show();
			$("#authCode").focus();
			return false;
		}else if(!(check.prop("checked"))){
			$("#agreement_tip").show();
			return false;
		}else if(auth.trim()!==""){
			$("#agreement_tip").hide();
			//验证码矫正
			var Code = $("#authCode").val();
			if(Code.length <=0){
				$(".auth_tip").show().html("验证码为空");
			}else if(Code !=coDe && Code.length >0){
				$(".auth_tip").show().html("验证码有误");
				createCode()//如果输入的验证码有误就刷新验证码	
				return false;
			}else{
				//设置点击之后的内容，同时限制多次点击
				$("#btn_reg").val("注册中...").prop("disabled",true);
				//进行注册接口的调用
				$.ajax({
					type:"post",
					url:"admin/reg.php",
					async:true,
					data:{
						//数据获取
						"user":$("#user").val(),
						"pass":$("#pasw").val()
					},
					success:function(data){
						console.log(data);
						$("#btn_reg").val("注册").prop("disabled",false);
						//将后端返回的字符串数据转成json
						var json = JSON.parse(data);
						//通过判断json数据的不同，进行不同的操作
						if(json.type === "error"){
							switch(json.code){
								case "1":
									alert("该用户已经被注册");
									break;
								default:
									alert("发生未知错误，code：" + json.code);
							}
							return;
							createCode();
						}else{
							alert("注册成功");
							clearForm();
							location.href = "login.html";
						}
					},
					error:function(){
						alert("请完善注册信息再点击");
						$("#btn_reg").val("注册").prop("disabled",false);
					}
				});
			}
		}
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
	})
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
	})
	//验证码验证
	$("#authCode").change(function(){
		var auth = $("#authCode").val();
		var reg_auth =/^[a-zA-Z\d]{4}$/;
		if(reg_auth.test(auth)){
			$(".auth_tip").hide();
		}else{
			$(".auth_tip").show();
			return false;
		}
	});
	//单选框
	var bool = true;
	$(".registere #checkbox").change(function(){
		if(bool){
			$("#img").addClass("on");
			bool = false;
		}else{
			$("#img").removeClass("on");
			bool = true;
		}
	});
});
$(function(){
	$("#huan").on("click",createCode)
	$(".kan").on("click",createCode)	
	createCode()

});
var coDe;//定义一个全局验证码
var flag =true;
function createCode(){
	var selectChar = new Array('a','b','f','q','w','f','g','b','u','e','v','9','7',"1","5","8","0","4","3","2");
	coDe = '';
	var codeLength=4;
	var codeContent = $(".code");
	for(var i=0;i<codeLength;i++){
		var charIndex =Math.floor(Math.random()*selectChar.length);//随机数
		//alert(charIndex)
		coDe +=selectChar[charIndex];
		codeContent.html(coDe);
	}
};
