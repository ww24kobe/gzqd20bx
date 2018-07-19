<?php 
include_once "../function.php";
//接收文章的post_id参数
$post_id = $_POST['post_id'];
//先获取到原来的点赞数量
//1、链接数据库
$link = connect();
//2、编写sql语句
$sql1 = "select likes from posts where post_id = $post_id";
//3、执行sql语句，获取数据
$result = query($link,$sql1);
$oldLikes = $result[0]['likes'];


//把以前的数据库数量进行更新，加1 
//更新数量的sql语句
$sql2 = "update posts set likes = likes +1 where post_id = $post_id";
//执行sql语句
$res = mysqli_query($link,$sql2);
if($res){
	//成功，返回点赞的总数量
	$response = ['code'=>200,'message'=>'成功','newLikes'=>$oldLikes+1];
}else{
	//失败
	$response = ['code'=>-1,'message'=>'失败'];
}
//响应json格式的数据
echo json_encode($response);