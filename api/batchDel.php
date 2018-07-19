<?php 
include_once "../function.php";
//接收参数
$cat_ids = $_GET['cat_ids'];
//链接数据库
$link = connect();
//编写sql语句
$sql="delete from category where cat_id  in ($cat_ids);";
//执行sql语句
$res = mysqli_query($link,$sql);
//判断是否删除成功
if( mysqli_affected_rows($link) ){
	//删除成功
	$response = ['code'=>200,'message'=>'批量删除成功'];
}else{
	//删除失败
	$response = ['code'=>-1,'message'=>'批量删除失败'];
}
//响应json数据给前端
echo json_encode($response);