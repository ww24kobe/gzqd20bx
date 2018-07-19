<?php 
include_once "../function.php";
//1.接收参数
$post_id = $_GET['post_id'];
//2.连接数据库
$link = connect();
//3.编写sql语句
$sql = "select * from posts where post_id = $post_id ";
//4.执行sql语句，获取数据
$result = query($link,$sql); // [ 0=>[] ]
//5.判断是否成功，返回json数据
if($result){
	$response = ['code'=>200,'message'=>'success','data'=>$result[0]];
}else{
	$response = ['code'=>-1,'message'=>'fail','data'=>[] ];
}
echo json_encode($response);