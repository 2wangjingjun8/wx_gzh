//定义key名称
var keyName = "shopCar"
//将商品添加到购物车
function addShopCar(product){
	//先获取本地数据
	var productData = getCar();
	//如果本地里面没有任何商品
	if(!productData){
		//创建一个JSON数据，将商品添加到这个JSON数据里面
		var proData = [
			product
		]
		//再存储到本地存储（添加到购物车）
		addCar(proData)
	}else{
		//本地已经有数据（商品）
		//将数据转换成JSON格式的数据
		var carData = JSON.parse(productData);
		var bool = true;
		//遍历这个数据
		for(var i=0;i<carData.length;i++){
			//通过id判断是否有相同的商品，如果有相同的商品，直接加数量和小计
			if(carData[i].id == product.id){
				carData[i].num = parseInt(carData[i].num) + parseInt(product.num);
				carData[i].totalPrice = (parseFloat(carData[i].totalPrice) + parseFloat(product.totalPrice)).toFixed(2);
				bool = false;
				break;
			}
		}
		//如果没有相同的商品，直接将这个商品添加到购物车
		if(bool){
			//数组添加数据的方法push()
			carData.push(product)
		}
		//再重新将所有的商品存储到购物车
		addCar(carData);
	}
}
//通过指定的key获取商品数据
function getCar(){
	return localStorage.getItem(keyName);
}
//通过指定的key添加商品到本地
function addCar(productData){
	localStorage.setItem(keyName,JSON.stringify(productData));
}
//通过指定的id删除对应的商品
function delProduct(id){
	//先获取本地数据
	var carData = JSON.parse(getCar());
	//定义一个空数组，用来存储id不相等的商品
	var arrData = [];
	for(var i=0;i<carData.length;i++){
		if(carData[i].id == id){
			//如果id等于本地数据其中一个商品的id，就直接跳出这一次循环，再进行下一次循环
			continue;
		}else{
			//把id不相等的商品添加到新数组里面
			arrData.push(carData[i])
		}
	}
	//再重新将数据添加到购物车里面
	addCar(arrData);
}
//清空购物车
function clearCar(){
	//通过指定的key删除购物车
	localStorage.removeItem(keyName);
}

function clearProAll(){
	clearCar();
	var tbody = document.getElementById("carUl");
	if(tbody){
		var li = tbody.getElementsByTagName("li");
		var length = li.length;
		for(var i=0;i<length;i++){
			tbody.remove(i)
		}
	}
}
