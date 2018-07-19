<?php  
include_once "../function.php";
//接收参数
$email = $_POST['email'];
$password = $_POST['password'];
//1、链接数据库
$link = connect();
//2、编写sql语句
$sql = "select * from users where email = '$email' and password = '$password';";
//3、执行sql语句

$result = mysqli_query($link,$sql);
//判断查询结果的行数
if( mysqli_num_rows($result) ){
	//获取数据
	$userInfo = mysqli_fetch_assoc($result);
	//匹配成功
	$response = ['code'=>200,'message'=>'登录成功'];
	//设置session保存用户的相关信息
	session_start();
	//设置三个信息
	$_SESSION['user_id'] = $userInfo['user_id'];
	$_SESSION['nickname'] = $userInfo['nickname'];
	$_SESSION['avatar'] = $userInfo['avatar'];
}else{
	//匹配失败
	$response = ['code'=>-1,'message'=>'用户名或密码失败'];
}

echo json_encode($response);
