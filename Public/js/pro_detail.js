//点击加入购物车上传数据到本地存储
function setProduct(){
	var imgSrc = ["images/img3.png","images/img5.png","images/img6.png","images/img7.png","images/img1.png","images/img2.png"];
	var id = parseInt(Math.random()*6);
	var img = imgSrc[id];
	var num = 1;
	var price = 86.00;
	var product = {
		imgSrc:img,
		num:num,
		price:price,
		id:id,
		totalPrice:(price*num).toFixed(2)
	}
	addShopCar(product);
//	proNum();
};
function goLogin(){
	var data=localStorage.getItem("user");
	if(data==null){
		if(confirm("请先登录再进行购买。")){
			location.href="login.html";
		}else{
			return false;
		}
	}else{
			location.href="order.html";
		}
}