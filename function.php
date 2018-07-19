<?php 
//封装链接数据库的语句
function connect(){
	$link = mysqli_connect('127.0.0.1','root','root','bx');
	return $link;
}

//封装执行select的查询语句
function query($link,$sql){
	//执行sql语句
	$res = mysqli_query($link,$sql);
	//获取数据
	$data = [];
	while($row = mysqli_fetch_assoc($res)){
		$data[] = $row;
	}
	//返回构造好的数据
	return $data;
}

//封装一个调试函数
/*echo "<pre />";
var_dump($data);*/
function dump($data){
	echo "<pre />";
	var_dump($data);die;
}

//判断用户是否登录
function isLogin(){
	session_start();
	if(!isset($_SESSION['user_id'])){
		//说明没有session信息，跳转到登录页
		echo '请先登录';
		header("refresh:2;url='login.php'");
	}
}