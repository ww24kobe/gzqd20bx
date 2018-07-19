<?php 
include_once "../function.php"  ;
//检测是否有登录
isLogin();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Dashboard &laquo; Admin</title>
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
        <h1>我的个人资料</h1>
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
          <label class="col-sm-3 control-label">头像</label>
          <!-- 定义一个隐藏域,存储图片路径 -->
          <input type="hidden" id='avatar_path' >
          <div class="col-sm-6">
            <label class="form-image">
              <input id="avatar" type="file">
              <img id="headImg" src="/static/assets/img/default.png">
              <i class="mask fa fa-upload"></i>
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="email" class="col-sm-3 control-label">邮箱</label>
          <div class="col-sm-6">
            <input id="email" class="form-control" name="email" type="type" value="w@zce.me" placeholder="邮箱" readonly>
            <p class="help-block">登录邮箱不允许修改</p>
          </div>
        </div>
        <div class="form-group">
          <label for="nickname" class="col-sm-3 control-label">昵称</label>
          <div class="col-sm-6">
            <input id="nickname" class="form-control" name="nickname" type="type" value="汪磊" placeholder="昵称">
            <p class="help-block">限制在 2-16 个字符</p>
          </div>
        </div>
        <div class="form-group">
          <label for="bio" class="col-sm-3 control-label">简介</label>
          <div class="col-sm-6">
            <textarea id="bio" class="form-control" placeholder="Bio" cols="30" rows="6">MAKE IT BETTER!</textarea>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-6">
            <span type="submit" id="updUser" class="btn btn-primary">更新</span>
            <a class="btn btn-link" href="password-reset.php">修改密码</a>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- 引入menu.php公共文件 -->
  <?php include_once "./menu.php"; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
</body>
<script type="text/javascript">

  //给id=avatar(给文件上传域绑定)，绑定change事件
  $("#avatar").change(function(){
      //需要获取到文件的上传信息
      var file = this.files[0];
      //html5的一个特性，利用formData表单对象，可以用来传递二进制数据（文件流）和字符串数据
      var formdata = new FormData();
      //append('键','值')
      formdata.append('file',file);
      if(file){
        //有文件上传，发送ajax请求，通过php帮助我们处理上传文件
        $.ajax({
          "url":"../api/uploadImg.php",
          "type":"post", // 上传文件只能是post
          "data":formdata,
          "contentType":false, //上传文件不可以指定数据类型
          "processData":false, // 对数据不进行数据的序列化
          "dataType":'json',
          "success":function(res){
              console.log(res);
              if(res.code == 200){
                //把图片的路径设置img的src属性中用于预览
                $("#headImg").attr('src',res.url);
                //把上传成功的图片路径赋值给一个隐藏域，用于后面带到后台去
                $("#avatar_path").val(res.url);
              }
          }
        });
      }
  });

  //ajax更新用户的信息
  $("#updUser").on('click',function(){
    //获取到数据
    var nickname = $.trim( $("#nickname").val() );
    var bio = $("#bio").val();
    var avatar = $("#avatar_path").val();
    //验证数据
    if(nickname == ''){
      //提示错误信息
      $(".alert-danger").show().html("<strong>昵称不能为空</strong>");
      return false;
    }
    $(".alert-danger").hide(); //隐藏错误信息
    //通过ajax发送到后台进行更新
    var param = {"nickname":nickname,"bio":bio,"avatar":avatar};
    $.post("../api/updUser.php",param,function(res){
        console.log(res);
        if(res.code == 200){
            //提示更新成功
            $(".alert-success").show().html("<strong>"+ res.message +"</strong>");
        }else{
            $(".alert-danger").show().html("<strong>"+ res.message +"</strong>");
        }
    },'json');
  });


  //页面加载完毕获取当前用户的个人信息展示在表单中
  $.get("../api/getUser.php",'',function(res){
     //给表单赋值
     $("#headImg").attr('src',res.data.avatar);
     $("#nickname").val(res.data.nickname);
     $("#bio").val(res.data.bio);
     $("#email").val(res.data.email);
  },'json');


</script>
</html>
