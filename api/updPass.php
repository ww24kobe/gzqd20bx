<?php  
include_once "../function.php";
//1、接收参数
$old = $_POST['old'];
$password = $_POST['password'];
session_start();
$user_id = $_SESSION['user_id'];
//2、链接数据库
$link = connect();
//3、先要判断旧密码是否和数据库一致
//3-1. 编写sql语句
$sql1 = "select * from users where password = '$old' and user_id = $user_id";
//3-2.执行sql语句，获取结果
$result = query($link,$sql1);
//3-3.判断结果是否一致
if(!$result){
	//说明和旧密码不一致
	$response = ['code'=>-1,'message'=>'旧密码输入错误'];
	echo json_encode($response);exit;
}
//4、开始更新用户的密码为新密码
//4-1、编写更新密码的sql语句
$sql2 = "update users set password = '$password' where user_id=$user_id";
//4-2.执行sql语句
$res = mysqli_query($link,$sql2);
//4-3.判断是否更新成功（判断受影响的行数）
if( mysqli_affected_rows($link) ){
	//修改成功
	$response = ['code'=>200,'message'=>'密码修改成功'];
}else{
	//修改修改（或是未修改密码）
	$response = ['code'=>-2,'message'=>'密码修改失败或未修改'];
}

echo json_encode($response);
