<?php 
include_once "../function.php"  ;
//检测是否有登录
isLogin();
//session_start(); //开启session会话
//用一个变量存储当前访问的页面
$visitor = 'posts-add';
//获取分类
//1.链接数据库
$link = connect();
//2.编写sql语句
$sql = "select * from category";
//3.执行sql语句，获取数据
$catDatas = query($link,$sql);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Add new post &laquo; Admin</title>
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
        <h1>写文章</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <form class="row" id="form">
        <div class="col-md-9">
          <div class="form-group">
            <label for="title">标题</label>
            <input id="title" class="form-control input-lg" name="title" type="text" placeholder="文章标题">
          </div>
          <div class="form-group">
            <label for="content">内容</label>
            <textarea id="content"  name="content" cols="30" rows="10" placeholder="内容"></textarea>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label for="feature">特色图像</label>
            <!-- show when image chose -->
            <img class="help-block thumbnail" style="display: none">
            <input id="feature" class="form-control"  name="feature" type="file">
            <input id="img" class="form-control"  name="img" type="hidden">
          </div>
          <div class="form-group">
            <label for="category">所属分类</label>
            <select id="category" class="form-control" name="category">
              <?php  foreach($catDatas as $cat): ?>
                <option value="<?php echo $cat['cat_id']; ?>"><?php echo $cat['cat_name']; ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="form-group">
            <label for="created">发布时间</label>
            <input id="created" class="form-control" name="created" type="text">
          </div>
          <div class="form-group">
            <label for="status">状态</label>
            <select id="status" class="form-control" name="status">
              <option value="drafted">草稿</option>
              <option value="published">已发布</option>
            </select>
          </div>
          <div class="form-group">
            <span class="btn btn-primary" id='updPost' type="submit">确认修改</span>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- 引入公共的menu.php -->
  <?php  include_once "./menu.php";?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/plugins/laydate/laydate.js"></script>
  <script type="text/javascript" charset="utf-8" src="/static/plugins/ueditor/ueditor.config.js"></script>
  <script type="text/javascript" charset="utf-8" src="/static/plugins/ueditor/ueditor.all.min.js"> </script>
    <!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
    <!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
  <script type="text/javascript" charset="utf-8" src="/static/plugins/ueditor/lang/zh-cn/zh-cn.js"></script>
  <script type="text/javascript" charset="utf-8" src="/static/plugins/layer/layer.js"></script>
</body>
<script type="text/javascript">
  var post_id = "<?php echo $_GET['post_id']; ?>";
  //根据当前文章的post_id，发送ajax请求，获取数据
  $.get("../api/getOnePostData.php",{"post_id":post_id},function(res){
      console.log(res);
      if(res.code == 200){
        //把数据赋值给页面对应的input框
        var data = res.data;
        $("#title").val(data.title);
        $(".help-block").show().attr('src',data.feature);
        $("#created").val(data.created);
        //select对象.val(3); 把option value=3的默认选中
        $("#category").val(data.cat_id);
        $("#status").val(data.status);
        $("#img").val(data.feature);
        //回显富文本编辑器内容
        var ue = UE.getEditor('content'); //由于异步的原因（时间差的问题--》导致代码的执行顺序）
        $("#content").val(data.content); // 后设置内容（可以和上面的代码置换顺序也行）
      }
  },'json');
  
  //ajax上传文件，给文件域绑定change事件
  $("#feature").change(function(){
      var file = this.files[0];
      if(!file){
        return false; //没有文件上传，停止往下执行
      }
      //插件一个表单对象，追加数据
      var formObj = new FormData(); //  new FormData(form标签的DOM对象)
      formObj.append('file',file);
      //发送ajax请求
      $.ajax({
        "type":"post",
        "url":"../api/uploadImg.php",
        "data":formObj,
        "contentType":false,
        "processData":false,
        "dataType":'json',
        "success":function(res){
          console.log(res);
          if(res.code == 200){
            //设置图片预览效果
            //把上传图片的路径设置给一个隐藏域进行存储，方便与后面使用serialize收集
            $("#img").val(res.url);
            $(".help-block").show().attr('src',res.url);
          }
        }
      });
  });

  //对id=created进行时间的初始化
  //执行一个laydate实例
  laydate.render({
    elem: '#created', //指定元素
    type:'datetime' //指定日期的类型
  });

  //ajax完成确认编辑功能
  $("#updPost").on('click',function(){
      //通过表单序列化，获取含有name属性的input元素和textarea元素
      var param = $("#form").serialize();
      //需要携带编辑的post_id
      param += "&post_id="+post_id;
      console.log(param);
      //发送ajax请求
      $.get("../api/updPost.php",param,function(res){
        if(res.code == 200){
          alert(res.message);
          location.href = "./posts.php";
        }else{
          alert(res.message);
        }
      },'json');
  });



  
</script>
</html>
