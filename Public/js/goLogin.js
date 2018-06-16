//判断是否登陆 在进去提交订单
function goLogin(){
	var data=localStorage.getItem("user");
	if(data==null){
		if(confirm("请先登录再进行抢购。")){
			location.href="login.html";
		}else{
			return false;
		}
	}else{
			location.href="order.html";
		}
}
	