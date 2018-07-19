<?php  
include_once "../function.php";
// dump($_FILES); 
//move_uploaded_file('虚拟路径','真实路径')
$tmp_name = $_FILES['file']['tmp_name'];
//获取文件的后缀
$ext = strrchr($_FILES['file']['name'],'.');
//拼接文件的名称
$filename = time().$ext;
//上传的文件兖
$uploadPath = "../uploads/".$filename;
//文件的上传
if( move_uploaded_file($tmp_name,$uploadPath) ){
	//上传成功，需要返回文件的完整路径
	$response = ['code'=>200,'message'=>'上传文件成功','url'=>'/uploads/'.$filename];
}else{
	//上传文件失败
	$response = ['code'=>-1,'message'=>'上传文件失败','url'=>''];
}

echo json_encode($response);
