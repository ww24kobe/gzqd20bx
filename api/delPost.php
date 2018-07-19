<?php 
include_once "../function.php";
sleep(1);
//1.接收参数
$post_id = $_POST['post_id'];
//2.连接数据库
$link = connect();
//3.编写sql语句
$sql = "delete from posts where post_id = $post_id ";
//4.执行sql语句，返回json数据
$res = mysqli_query($link,$sql);
if( mysqli_affected_rows($link) ){
	$response = ['code'=>200,'message'=>'删除成功'];
}else{
	$response = ['code'=>-1,'message'=>'删除失败'];
}
echo json_encode($response);