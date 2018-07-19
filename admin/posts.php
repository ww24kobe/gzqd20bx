<?php 
include_once "../function.php"  ;
//检测是否有登录
isLogin();
//session_start(); //开启session会话
//用一个变量存储当前访问的页面
$visitor = 'posts';
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
  <title>Posts &laquo; Admin</title>
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
        <h1>所有文章</h1>
        <a href="post-add.php" class="btn btn-primary btn-xs">写文章</a>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <a class="btn btn-danger btn-sm" href="javascript:;" style="display: none">批量删除</a>
        <form class="form-inline">
          <select name="cat_id" class="form-control input-sm">
            <option value="all">所有分类</option>
            <?php  foreach($catDatas as $cat):?>
              <option value="<?php echo $cat['cat_id']; ?>"><?php echo $cat['cat_name']; ?></option>
            <?php endforeach; ?>
          </select>
          <select name="status" class="form-control input-sm">
            <option value="all">所有状态</option>
            <option value="drafted">草稿</option>
            <option value="published">已发布</option>
            <option value="trashed">已作废</option>
          </select>
          <span id="search"  class="btn btn-default btn-sm">筛选</span>
        </form>
        <ul class="pagination pagination-sm pull-right">
         <!--  <li><a href="#">上一页</a></li>
         <li><a href="#">1</a></li>
         <li><a href="#">2</a></li>
         <li><a href="#">3</a></li>
         <li><a href="#">下一页</a></li> -->
        </ul>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <th class="text-center" width="40"><input type="checkbox"></th>
            <th>标题</th>
            <th>作者</th>
            <th>分类</th>
            <th class="text-center">发表时间</th>
            <th class="text-center">状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          
        </tbody>
      </table>
    </div>
  </div>

  <!-- 引入公共的menu.php文件 -->
  <?php include_once "./menu.php";?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/art-template/template-web.js"></script>
  <script src="/static/plugins/jquery.twbsPagination.js"></script>
  <script type="text/javascript" charset="utf-8" src="/static/plugins/layer/layer.js"></script>
</body>
<script type="text/template" id='temp'>
  {{ each  data value}}
  <tr>
      <td class="text-center"><input type="checkbox" value="{{value.post_id}}"></td>
      <td>{{value.title}}</td>
      <td>{{value.nickname}}</td>
      <td>{{value.cat_name}}</td>
      <td class="text-center">{{value.created}}</td>
      <td class="text-center">
        {{if value.status == 'drafted'}}
          草稿
        {{else if value.status == 'published'}}
          已发布
        {{ else }}
          已作废
        {{/if}}
      </td>
      <td class="text-center">
        <a href="./post-upd.php?post_id={{value.post_id}}" class="btn btn-default btn-xs">编辑</a>
        <a href="javascript:;" class="delPost btn btn-danger btn-xs">删除</a>
      </td>
    </tr>
  {{/each}}
</script>
<script type="text/javascript">
  var pageCount = 0; // 定义页码总数
  //ajax加载文章数据
  $.get("../api/getPostsData.php",'',function(res){
    console.log(res);
    if(res.code == 200){
      var data = res.data;
      //把分页的总数赋值给pageCount变量
      pageCount = res.pageCount;
      //调用模板引擎进行渲染页面结构
      var tbody = template('temp',{"data":data});
      //把渲染好的内容赋值给tbody元素
      $("tbody").html(tbody);
      //绘制分页页码
      pageList();
    }
  },'json');
  
  function pageList(){
    //把class=pagination渲染出分页页码的html结构
    //重置分页页码,要重写渲染筛选条件后的分页页码,对page进行解绑事件
    $(".pagination").empty();
    //删除此插件自带设置的一个值
    $(".pagination").removeData('twbs-pagination');
    //解绑page的事件
    $(".pagination").unbind('page');
    $('.pagination').twbsPagination({
        totalPages: pageCount, // 分页页码的总页数
        visiblePages: 7, // 展示的页码数
        initiateStartPageClick:false, // 取消默认初始点击
        onPageClick: function (event, page) {
            // page 当前所点击的页码数
            //获取筛选条件
            var cat_id = $("select[name='cat_id']").val();
            var status = $("select[name='status']").val();
           //发送ajax请求，获取页码对应的数据
           $.get("../api/getPostsData.php",{"page":page,"cat_id":cat_id,"status":status},function(res){
              var data = res.data;
              //使用模板引擎进行渲染数据
              var tbody =  template('temp',{"data":data});
              $("tbody").html(tbody);
           },'json')

        }
    });
  }

  //ajax筛选文章数据
  $("#search").on('click',function(){
    //获取分类的id和状态
    var cat_id = $("select[name='cat_id']").val();
    var status = $("select[name='status']").val();
    //发送ajax请求，获取指定条件的筛选数据
    $.get("../api/getPostsData.php",{"cat_id":cat_id,"status":status},function(res){
        if(res.code == 200){
          //赋值给pageCount分页总数
          pageCount = res.pageCount;
          var data = res.data;
          //调用模板引擎渲染数据
          var tbody = template('temp',{"data":data});
          //把渲染好的数据写在tbody标签中
          $("tbody").html(tbody);
          //重置绘制筛选条件后的分页页码结构
          pageList();
        }else{
          //清空tbody内容和页码
          $("tbody").empty(); // $("tbody").html('');
          $(".pagination").empty(); // $("pagination").html('');
          $(".pagination").removeData('twbs-pagination');
          $(".pagination").unbind('page');
        }
    },'json')
  })

  //使用委托的方式给class=delPost绑定单击事件，发送ajax请求，删除文章
  $("tbody").on('click','.delPost',function(){
      var _self = $(this);
      layer.confirm('确认删除？', {
        btn: ['重要','取消'] 
      }, function(){
        //确定删除的逻辑
        layer.closeAll();//关闭询问框
        var post_id = _self.parents('tr').find("input").val();
        //发送ajax请求进行删除
        //发送之前，loading提示
        layer.load(1, {shade:[0.5,'#ccc'],shadeClose:false}); //0代表加载的风格，支持0-2
        $.post("../api/delPost.php",{"post_id":post_id},function(res){
            console.log(res);
            //主要关闭load层
            layer.closeAll();
            if(res.code == 200){
                
                //移除当前文章的所在tr行
                _self.parents('tr').remove();
            }else{
                 //提示错误信息
                 layer.msg(res.message);
            }
        },'json');
      }, function(){
        //取消删除的逻辑
      });
  })
  

</script>
</html>
