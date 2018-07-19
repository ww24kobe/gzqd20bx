<?php  
include_once "../function.php";
sleep(2);
//1. 获取参数
$title = $_GET['title'];
$content = $_GET['content'];
$created = $_GET['created'];
$feature = $_GET['url'];
$status = $_GET['status'];
$cat_id = $_GET['category'];
session_start();
$user_id = $_SESSION['user_id'];
//2.连接数据库
$link = connect();
//3.编写sql语句，执行sql
$sql = "insert into posts(title,content,created,feature,status,cat_id,user_id) values('$title','$content','$created','$feature','$status','$cat_id','$user_id')";
$res = mysqli_query($link,$sql);
//4.判断数据是否成功，返回json数据
if( mysqli_affected_rows($link) ){
	$response = ['code'=>200,'message'=>'添加数据成功'];
}else{
	$response = ['code'=>-1,'message'=>'添加数据失败'];
}
echo json_encode($response);