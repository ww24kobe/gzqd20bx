<?php 
require_once "../function.php";
//1、获取user_id，从session中来
session_start();
$user_id = $_SESSION['user_id'];
//2、链接数据库
$link = connect();
//3、编写sql语句
$sql = "select * from users where user_id = $user_id";
//4、执行sql语句，获取数据
$result = query($link,$sql); 
//因为query方法返回的结束是一个二维数组，通过下标0可以取出对应的数据
$data = $result[0];  
//5、返回json数据
$response = ['code'=>200,'message'=>'获取用户信息成功','data'=>$data];
echo json_encode($response);