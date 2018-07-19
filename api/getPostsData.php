<?php 
include_once "../function.php";
//1.链接数据库
$link = connect();
//因为刚加载文章数据的时候没有带page参数，给设置一个默认为1
$page  =  isset($_GET['page'])?$_GET['page']:1;
//接收筛选的参数
$cat_id = isset($_GET['cat_id'])?$_GET['cat_id']:"all";
$status = isset($_GET['status'])?$_GET['status']:"all";
//组装where查询条件
//where = '1=1'  条件永远为真，当后面没有and拼接条件的时候，sql就这样： select * from posts where 
//where后面没有条件就会报错，where后面拼接1=1，就避免这种出错,这种语句一般用在搜索中，主要防止没有查询条件而报错
$where = '1=1'; //用于拼接查询条件的
if($cat_id!='all'){
	$where .= " and  p.cat_id = $cat_id ";
}

if($status!='all'){
	$where .= " and p.status = '$status'";
}


//2.编写sql语句
$pagesize = 10; //每页显示的条数
$offset = ($page - 1)*$pagesize; // 定义limit查询的起始位置
$sql = "SELECT p.*, c.cat_name,u.nickname 
FROM posts p
LEFT JOIN category c ON p.cat_id = c.cat_id
left join users u on p.user_id = u.user_id
where $where
order by p.post_id desc
limit $offset,$pagesize";
//echo $sql;die;
//3.执行sql语句，获取数据
$data = query($link,$sql); // []

//定义sql语句，取出文章的总数,算出分页总页码数
$sql2 = "select count(*) postsCount from posts p where $where";
//执行sql语句，获取总数
$data2 = query($link,$sql2);
$postsCount = $data2[0]['postsCount'];
$pageCount = ceil($postsCount/$pagesize);
//4.响应json数据给前端页面进行渲染
if($data){
	$response = ['code'=>200,'message'=>'获取数据成功','data'=>$data,"pageCount"=>$pageCount];
}else{
	$response = ['code'=>-1,'message'=>'获取数据失败','data'=>[]];
}
echo json_encode($response);