<?php 
include_once "../function.php";
//检测是否有登录
isLogin();
//用一个变量存储当前访问的页面
$visitor = 'comments';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Comments &laquo; Admin</title>
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
        <h1>所有评论</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <!-- <div class="alert alert-danger">
        <strong>错误！</strong>发生XXX错误
      </div> -->
      <div class="page-action">
        <!-- show when multiple checked -->
        <div class="btn-batch" style="display: none">
          <button class="btn btn-info btn-sm">批量批准</button>
          <button class="btn btn-warning btn-sm">批量拒绝</button>
          <button class="btn btn-danger btn-sm">批量删除</button>
        </div>
        <ul class="pagination pagination-sm pull-right">
          <!-- <li><a href="#">上一页</a></li>
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
            <th>作者</th>
            <th>评论</th>
            <th>评论在</th>
            <th>提交于</th>
            <th>状态</th>
            <th class="text-center" width="100">操作</th>
          </tr>
        </thead>
        <tbody>
          
        </tbody>
      </table>
    </div>
  </div>

  <!-- 引入menu.php -->
  <?php  include_once "./menu.php"; ?>

  <script src="/static/assets/vendors/jquery/jquery.js?v=1"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/art-template/template-web.js"></script>
  <script src="/static/plugins/jquery.twbsPagination.js"></script>
</body>
<!-- 定义模板引擎 -->
<script type="text/template" id="tmpl">
    {{ each data  val }}
    <tr>
            <td class="text-center"><input type="checkbox" value="{{val.comment_id}}"></td>
            <td>{{val.author}}</td>
            <td>楼主好人，顶一个</td>
            <td>{{val.title}}</td>
            <td>{{val.created}}</td>
            <td>
                {{if val.status == 'held'}}
                  待审核
                {{else if val.status == 'approved'}}
                  准许
                {{else if val.status == 'rejected'}}
                  拒绝
                {{else}}
                  回收站
                {{/if}}
            </td>
            
            <td class="text-center">
              <a href="post-add.php" class="btn btn-info btn-xs">批准</a>
              <a href="javascript:;" class="btn btn-danger btn-xs">删除</a>
            </td>
    </tr>
   {{/each }}
</script>
<script type="text/javascript">
  function getCommentsData(page){
    //ajax获取评论数据
    $.get("../api/getCommentsData.php",{page:page},function(res){
      console.log(res);
      if(res.code == 200){
        //渲染页面
         var tbody = template("tmpl",{"data":res.data});
         $("tbody").html(tbody);
        //绘制分页页码
        $('.pagination').twbsPagination({
          totalPages: res.pageCount, // 总的页码数(若此总页码要重置分页页码（执行3行代码）)
          visiblePages: 5, //显示的页码数
          initiateStartPageClick: false,  //取消默认点击
          first:"首页",
          prev:"上一页",
          next:"下一页",
          last:"最后一页",
          onPageClick: function (event, page) {
               getCommentsData(page);
          }
        });
      }
    },'json')
  }
  //调用上面的函数
  getCommentsData(1);

</script>
</html>
