<?php 
include_once "../function.php";
//接收参数 trim();//去除变量两边连续的空格
$cat_name = trim($_POST['cat_name']);
$classname = trim($_POST['classname']);

//入库之前，判断分类名称是否重复
$link = connect();
//编写sql语句
$sql = "select * from category where cat_name = '$cat_name'";
//执行sql语句 ，获取数据
$result = query($link,$sql);
if($result){
	//说明有同名的数据
	$response = ['code'=>-1,'message'=>'分类名已存在'];
	echo json_encode($response);exit;
}

//入库操作,编写入库的sql语句
$sql2 = "insert into category(cat_name,classname) values('$cat_name ','$classname')";
//执行入库的sql语句
$res = mysqli_query($link,$sql2);
//判断是否成功
if($res){
	//入库成功，还需要返回给一个新增成功的记录id

	$response = ['code'=>200,'message'=>'添加分类成功','insert_id'=>mysqli_insert_id($link)];
}else{
	$response = ['code'=>-2,'message'=>'添加分类失败'];
}

echo json_encode($response);
