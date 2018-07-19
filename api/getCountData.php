<?php 
include_once "../function.php";
//1.链接数据库
$link = connect();
//2.编写查询总数的sql语句
//2.1 获取文章总数的sql语句
$sql1 = "select count(*) postsCount from posts";
$result1= query($link,$sql1);
$postsCount = $result1[0]['postsCount'];
//2.2 获取文章草稿(drafted)总数的sql语句
$sql2 = "select count(*) draftedCount from posts where status='drafted'";
$result2= query($link,$sql2);
$draftedCount = $result2[0]['draftedCount'];
//2.3 获取分类总数的sql语句
$sql3 = "select count(*) catsCount from category";
$result3= query($link,$sql3);
$catsCount = $result3[0]['catsCount'];
//2.4 获取所有评论总数的sql语句
$sql4 = "select count(*) commentsCount from comments";
$result4= query($link,$sql4);
$commentsCount = $result4[0]['commentsCount'];
//2.5 获取所有待审核（held）评论总数的sql语句
$sql5 = "select count(*) heldCount from comments where status ='held'";
$result5= query($link,$sql5);
$heldCount = $result5[0]['heldCount'];
//3.返回所有总数的json数据
$response = [
	"postsCount" =>$postsCount,
	"draftedCount" =>$draftedCount,
	"catsCount" =>$catsCount,
	"commentsCount" =>$commentsCount, 
	"heldCount" =>$heldCount
];
echo json_encode($response);