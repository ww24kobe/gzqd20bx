<?php 
include_once "../function.php";
//从session获取当前登录用户的user_id
session_start(); //开启session
$user_id = $_SESSION['user_id'];
//1、接收参数
$nickname = $_POST['nickname'];
$bio = $_POST['bio'];
$avatar = $_POST['avatar'];
//2、链接数据库
$link = connect();
//3、编写sql
$sql = "update users set nickname= '$nickname',bio = '$bio',avatar='$avatar' where user_id = $user_id";
//4、执行sql语句
$res = mysqli_query($link,$sql);
//5、判断结果，返回json数据到前端
if( mysqli_affected_rows($link) ){
	$_SESSION['avatar'] = $avatar;
	$response = ['code'=>200,'message'=>'修改信息成功'];
}else{
	$response = ['code'=>-1,'message'=>'修改信息失败,未修改过'];
}
echo json_encode($response);