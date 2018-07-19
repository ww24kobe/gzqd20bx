<?php 
include_once "../function.php"  ;
//检测是否有登录
isLogin();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Password reset &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
</head>
<body>

  <div class="main">
    <nav class="navbar">
      <button class="btn btn-default navbar-btn fa fa-bars"></button>
      <ul class="nav navbar-nav navbar-right">
        <li><a href="profile.php"><i class="fa fa-user"></i>个人中心</a></li>
        <li><a href="login.php"><i class="fa fa-sign-out"></i>退出</a></li>
      </ul>
    </nav>
    <div class="container-fluid">
      <div class="page-title">
        <h1>修改密码</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger" style="display: none">
        <strong>错误！</strong>发生XXX错误
      </div>
      <div class="alert alert-success" style="display: none">
        <strong>错误！</strong>发生XXX错误
      </div>
      <form class="form-horizontal">
        <div class="form-group">
          <label for="old" class="col-sm-3 control-label">旧密码</label>
          <div class="col-sm-7">
            <input id="old" class="form-control" type="password" placeholder="旧密码">
          </div>
        </div>
        <div class="form-group">
          <label for="password" class="col-sm-3 control-label">新密码</label>
          <div class="col-sm-7">
            <input id="password" class="form-control" type="password" placeholder="新密码">
          </div>
        </div>
        <div class="form-group">
          <label for="confirm" class="col-sm-3 control-label">确认新密码</label>
          <div class="col-sm-7">
            <input id="confirm" class="form-control" type="password" placeholder="确认新密码">
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-7">
            <span type="submit" class="btn btn-primary" id="updPass">修改密码</span>
          </div>
        </div>
      </form>
    </div>
  </div>

   <?php include_once "./menu.php";?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
</body>
<script type="text/javascript">
  //ajax修改密码
  $("#updPass").on("click",function(){
      //获取旧密码和新密码和确认新密码
      var old = $.trim( $("#old").val() );
      var password = $.trim( $("#password").val() );
      var confirm = $.trim( $("#confirm").val() );
      //数据验证，
      //1、不能为空
      if(old=='' || password=='' || confirm==''){
         $(".alert-danger").show().html("<strong>旧密码或密码或确认密码不能为空</strong>");
         return false;
      }
      //2、必须是字母开头，密码长度最少6位
      var reg = /^[a-zA-Z]\w{5,}$/;
      if(reg.test(password) == false){
        $(".alert-danger").show().html("<strong>必须是字母开头，密码长度最少6位</strong>");
         return false;
      }
      
      //3、判断两次密码是否一致
      if(password!= confirm){
         $(".alert-danger").show().html("<strong>两次密码不一致</strong>");
         return false;
      }
      //隐藏提示错误的信息
      $(".alert-danger").hide();
      //发送ajax进行修改密码
      var param = {"old":old,"password":password};
      $.post("../api/updPass.php",param,function(res){
        console.log(res);
        //判断是否成功
        if(res.code == 200){
            $(".alert-success").show().html("<strong>"+res.message+"</strong>");
        }else{
            $(".alert-danger").show().html("<strong>"+res.message+"</strong>");
        }
      },'json');
  });
</script>
</html>


