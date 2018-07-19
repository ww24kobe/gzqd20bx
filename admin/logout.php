<?php 
//清除用户登录成功时候设置的session
session_start();
unset($_SESSION['user_id']);
unset($_SESSION['nickname']);
unset($_SESSION['avatar']);
//var_dump($_SESSION);
//跳转到登录页面
/*echo '退出中...';
header("refresh:3;url='login.php'");*/


header('location:login.php');
