<?php  
include_once "../function.php";
//接收参数cat_id
$cat_id = $_GET['cat_id'];

//1、链接数据库
$link = connect();
//2、编写sql语句
$sql = "delete from category where cat_id = '$cat_id'";  
//3、执行sql语句
$result = mysqli_query($link,$sql);
//4、判断是否删除成功(判断受影响的函数)
if(mysqli_affected_rows($link)){
	//删除成功
	$response = ['code'=>200,'message'=>'删除成功'];
}else{
	//删除失败
	$response = ['code'=>-1,'message'=>'删除失败'];
}

//响应给客户端json格式的数据
echo json_encode($response);
