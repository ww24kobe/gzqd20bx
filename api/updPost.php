<?php 
include_once "../function.php";
//1.接收参数
$title = $_GET['title'];
$content = $_GET['content'];
$feature = $_GET['img'];
$created = $_GET['created'];
$cat_id = $_GET['category'];
$status = $_GET['status'];
$post_id = $_GET['post_id'];
//2.连接数据库
$link = connect();
//3.编写sql语句，
$sql = "update posts set title='$title',content='$content',feature='$feature',cat_id='$cat_id',created='$created',status='$status' where post_id = $post_id";
//4.执行sql语句
$res = mysqli_query($link,$sql);
//5.判断是否成功，返回json数据
if( mysqli_affected_rows($link) ){
	$response = ['code'=>200,'message'=>'编辑成功'];
}else{
	$response = ['code'=>-1,'message'=>'编辑失败'];
}
echo json_encode($response);