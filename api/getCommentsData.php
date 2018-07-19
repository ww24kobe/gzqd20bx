<?php 
include_once "../function.php";
//1.接收参数page
$page = $_GET['page'];
//2.链接数据库
$link = connect();
//3.编写sql语句 limit $offset,$pagesize
$pageSize = 10; //每页显示的条数
$offset = ($page-1)*$pageSize; //查询的起始位置（偏移量）
$sql="select c.*,p.title  from comments c left join posts p on p.post_id = c.post_id limit $offset,$pageSize";
// 3.1 执行上面的sql语句，获取结果
$result = query($link,$sql);

//4.执行查询总数的sql，获取出评论的总数，进而算出页码数
$sql2 = "select count(*) as count from comments ";
$result2 = query($link,$sql2);// [ 0=>['count']]
$pageCount = ceil($result2[0]['count']/$pageSize); //得出总的分页页码数
//5.返回json数据给前端
if($result){
	$response = ["code"=>200,"message"=>'success','data'=>$result,"pageCount"=>$pageCount];
}else{
	$response = ["code"=>-1,"message"=>'fail'];
}
echo json_encode($response);