<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Sign in &laquo; Admin</title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
</head>
<body>
  <div class="login">
    <form class="login-wrap">
      <img class="avatar" src="/static/assets/img/default.png">
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger" style="display:none">
        <strong>错误！</strong> 用户名或密码错误！
      </div>
      <div class="form-group">
        <label for="email" class="sr-only">邮箱</label>
        <input id="email" type="email" class="form-control" placeholder="邮箱" autofocus>
      </div>
      <div class="form-group">
        <label for="password" class="sr-only">密码</label>
        <input id="password" type="password" class="form-control" placeholder="密码">
      </div>
      <a class="btn btn-primary btn-block" href="javascript:;" id='login'>登 录</a>
    </form>
  </div>
</body>
<script type="text/javascript" src="/static/assets/vendors/jquery/jquery.min.js"></script>
<script type="text/javascript">
  //ajax登录
  $("#login").on('click',function(){
      //获取到邮箱和密码的值
      var email = $("#email").val();
      var password = $("#password").val();
      //做数据的验证
      var  reg = /^\w+@(?:[0-9a-zA-Z]+\.)+[a-zA-Z]{2,5}$/;
      if(email =='' || password==''){
        $(".alert-danger").show().html("<strong>邮箱或密码不能为空</strong>");
        return false;
      }
      if(reg.test(email) == false){
        //邮箱正则不满足，提示错误信息
        $(".alert-danger").show().html("<strong>邮箱格式不合法</strong>");
        return false;
      }
      //发送ajax请求，匹配邮箱和密码是否匹配
      //清除错误提示信息
      $(".alert-danger").hide();
      $.post('../api/checkUser.php',{"email":email,"password":password},function(res){
          console.log(res);
          if(res.code == 200){
            //登录成功，跳转到后台首页
            location.href = "./index.php";
          }else{
            //登录失败，提示错误的信息
            $(".alert-danger").show().html("<strong>"+res.message+"</strong>");
          }
      },'json');
  });
</script>
</html>
