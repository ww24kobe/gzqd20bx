<?php 
include_once "../function.php";
//接收参数
$cat_id = $_POST['cat_id'];
$cat_name = $_POST['cat_name'];
$classname = $_POST['classname'];
//判断是否类名有重名
//1、链接数据库
$link = connect();
//2、编写sql语句（排除当前分类）
$sql = "select * from category where cat_name='$cat_name' and cat_id!=$cat_id";
//3、执行sql语句，获取数据
$result = query($link,$sql);
if($result){
	//说明有重名
	$response = ['code'=>-1,'message'=>'分类名称重复'];
	echo json_encode($response);exit;
}
//编辑数据库对应的数据
$sql2 = "update category set cat_name = '$cat_name',classname = '$classname' where cat_id = $cat_id";
$res = mysqli_query($link,$sql2);
//判断编辑受影响的行数
if(mysqli_affected_rows($link)){
	//编辑成功
	$response = ['code'=>200,'message'=>'编辑分类成功'];
}else{
	//编辑失败（或是没有改数据，因为考虑受影响行数为0）
	$response = ['code'=>-2,'message'=>'编辑分类失败或没修改过'];
}
//响应json数据
echo json_encode($response);