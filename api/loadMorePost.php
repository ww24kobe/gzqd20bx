<?php 
//相对路径
include_once "../function.php";
//绝对路径 dirname();获取目录的名称
//include_once dirname(__DIR__)."/function.php";
//1、接收参数
$cat_id = $_GET['cat_id'];
$lastPostId = $_GET['lastPostId'];
//2、链接数据库
$link = connect();
//3、编写sql语句
sleep(1);
$sql = "select p.*,c.cat_name,u.nickname ,  
(select count(*) from comments where post_id = p.post_id) commentCount
from posts  p 
left join category c on p.cat_id = c.cat_id
left join users u on p.user_id = u.user_id 
where c.cat_id = $cat_id and p.post_id < $lastPostId
order by p.post_id desc
limit 5 ";

//4、执行sql语句，获取数据
$morePostsData = query($link,$sql);
//5、返回前端json数据 一般前后台数据的返回格式有个约定
//数据格式：{code状态码,message状态码对应的信息,data数据}
if($morePostsData){
	//成功
	$res = ['code'=>200,'message'=>'加载数据成功','data'=>$morePostsData];
}else{
	//失败
	$res = ['code'=>-1,'message'=>'无数据','data'=>[] ];
}
//输出json格式的数据
echo json_encode($res);
