<?php 
include_once "../function.php";
//检测是否有登录
isLogin();
  //获取分类的数据
  //1、链接数据库
  $link = connect();
  //2、编写sql语句
  $sql = "select * from category";
  //3、执行sql语句、获取数据
  $catsData = query($link,$sql);
  //用一个变量存储当前访问的页面
  $visitor = 'categories';
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title>Categories &laquo; Admin</title>
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
        <h1>分类目录</h1>
      </div>
      <!-- 有错误信息时展示 -->
      <div class="alert alert-danger" style="display: none">
        <strong>错误！</strong>发生XXX错误
      </div>

      <!-- 成功时展示 -->
      <div class="alert alert-success" style="display: none">
        <strong>成功！</strong>发生XXX错误
      </div>
      <div class="row">
        <div class="col-md-4">
          <form>
            <h2>添加新分类目录</h2>
            <div class="form-group">
              <label for="name">分类名称</label>
              <input id="name" class="form-control" name="cat_name" type="text" placeholder="分类名称">
            </div>
            <div class="form-group">
              <label for="slug">类名</label>
              <input id="slug" class="form-control" name="classname" type="text" placeholder="classname">
            </div>
            <div class="form-group">
              <span class="btn btn-primary" id="addCat" type="submit">添加</span>
              <span class="btn btn-primary" id="upd" type="submit" style="display: none">确认编辑</span>
              <span class="btn btn-primary" id="cancelUpd" type="submit" style="display: none">取消编辑</span>
            </div>
          </form>
        </div>
        <div class="col-md-8">
          <div class="page-action">
            <!-- show when multiple checked -->
            <a class="btn btn-danger btn-sm" href="javascript:;" id="batchDelButton" style="display: none">批量删除</a>
          </div>
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-center" width="40"><input id="batchDel" type="checkbox"></th>
                <th>名称</th>
                <th>类名</th>
                <th class="text-center" width="100">操作</th>
              </tr>
            </thead>
            <tbody>
              <?php  foreach($catsData as $cat):?>
              <tr>
                <td class="text-center"><input type="checkbox" value="<?php echo $cat['cat_id']; ?>"></td>
                <td><?php echo $cat['cat_name']; ?></td>
                <td><?php echo $cat['classname']; ?></td>
                <td class="text-center">
                  <a href="javascript:;" class="updCat btn btn-info btn-xs">编辑</a>
                  <a href="javascript:;" class="delCat btn btn-danger btn-xs">删除</a>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <!-- 引入公共的menu.php文件 -->
  <?php include_once "./menu.php"; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script src="/static/assets/vendors/art-template/template-web.js"></script>
</body>
<!-- 定义一个tr模板 -->
<script id="temp" type="text/template">
  <tr>
    <td class="text-center"><input type="checkbox" value="{{insert_id}}"></td>
    <td>{{cat_name}}</td>
    <td>{{classname}}</td>
    <td class="text-center">
      <a href="javascript:;" class="updCat btn btn-info btn-xs">编辑</a>
      <a href="javascript:;" class="delCat btn btn-danger btn-xs">删除</a>
    </td>
  </tr>
</script>
<script type="text/javascript">
  //ajax实现批量删除操作
  $("#batchDelButton").on('click',function(){
      //获取到选中的复选框
      var checked = $("tbody input:checked"); //[obj,obj,...]
      //获取选中的复选框中的值
      var cat_ids = []; //存储选中的分类的id
      $.each(checked,function(k,v){
        //console.log(v.value); // dom方式 v.value
        //console.log($(v).val()); // dom方式 $(v).val()
        cat_ids.push($(v).val());
      });
      cat_ids = cat_ids.join(); // '1,3'
      //发送ajax请求，进行一个批量删除
      $.get('../api/batchDel.php',{'cat_ids':cat_ids},function(res){
        console.log(res);
        if(res.code == 200){
          //删除成功，需要移除每个tr行
          checked.parents('tr').remove();
          //提示成功的信息
          $(".alert-success").show().html("<strong>"+res.message+"</strong>");
        }else{
          //提示失败的信息
          $(".alert-danger").show().html("<strong>"+res.message+"</strong>");
        }
      },'json');
  });
  //给tbody下面的input绑定单击事件
  $("tbody").on('click','input',function(){
    //获取到选中的复选框
    var checked = $("tbody input:checked");
    if(checked.length >0){
        //显示批量删除按钮
        $("#batchDelButton").show();
    }else{
        //隐藏批量删除按钮
        $("#batchDelButton").hide();
    }
    //上面代码等价于下面
    //$("#batchDelButton").toggle( checked.length > 0 ? true:false);
  });

  //完成全选和全不选操作
  $("#batchDel").on('click',function(){
    //获取到tbody下面的 所有复选框
    //this当前对象的DOM对象表示
    //$(this)当前对象的jquery对象表示
    //this.checked
    $("tbody input").prop('checked',this.checked); // DOM方式
    //$("tbody input").prop('checked',$(this).prop('checked')); // jquery方式
    //显示批量删除的按钮
    //toggle(true) 显示状态
    //toggle(false) 隐藏状态
    $("#batchDelButton").toggle(this.checked);
  });
  
  var cat_id;// 记录当前所编辑分类的cat_id
  var tr; //存储当前所编辑的tr行
  //绑定单击事件 给tbody下面的class=updCat采用委托的方式绑定单击事件
  $("tbody").on('click','.updCat',function(){
      //获取到当前分类的类名和分类名称
      tr = $(this).parents('tr');
      //获取tr中的分类的cat_id
      cat_id = tr.find('input').val();
      //获取到分类名称
      var cat_name = tr.children('td').eq(1).html();
      //获取到分类类名
      var classname = tr.children('td').eq(2).html();
      //把获取到的数据回显到表单中
      $("input[name='cat_name']").val(cat_name);
      $("input[name='classname']").val(classname);
      //让确定编辑和取消编辑的按钮显示
      $("#upd").show()
      $("#cancelUpd").show()
      //让添加按钮隐藏
      $("#addCat").hide();
      
  });
  //取消编辑单击事件
  $("#cancelUpd").on("click",function(){
    //显示添加按钮
    $("#addCat").show();
    //隐藏确定编辑按钮
    $("#upd").hide();
    //隐藏取消编辑按钮
    $("#cancelUpd").hide();
    //清空表单内的值
    $("input[name='cat_name']").val('');
    $("input[name='classname']").val('');
  })

  //发送ajax完成编辑数据的入库
  $("#upd").on('click',function(){
    //获取到分类的名称和分类的类名分类的cat_id
    var cat_name = $("input[name='cat_name']").val();
    var classname = $("input[name='classname']").val();

    //发送ajax之前，需要做数据的验证
    cat_name = $.trim(cat_name);
    classname = $.trim(classname);
    if(cat_name == '' || classname == ''){
      //提示错误信息
      $(".alert-danger").show().html("<strong>分类名称或类名不能为空</strong>");
      return false;
    }
    //成功，隐藏错误的提示信息
    $(".alert-danger").hide();

    //发送ajax请求
    $.post("../api/updCat.php",{"cat_id":cat_id,"cat_name":cat_name,"classname":classname},function(res){
      console.log(res);
      if(res.code == 200){
          //让编辑的数据回显在之前的tr的td中
          tr.children('td').eq(1).html(cat_name);
          tr.children('td').eq(2).html(classname);
          //让确定和取消的编辑按钮隐藏，添加按钮显示、清空表单中的数据（和取消编辑的逻辑是一样的）
          $("#cancelUpd").click(); //自执行单击事件（前提是已经绑定了单击事件才会触发）
          //提示成功的信息
          $(".alert-success").show().html("<strong>" + res.message + "</strong>");
          setTimeout(function(){
            $(".alert-success").hide();
          },2000);
      }else{
          //提示失败的信息
          $(".alert-danger").show().html("<strong>" + res.message + "</strong>");
          setTimeout(function(){
            $(".alert-danger").hide();
          },2000);
      }
    },'json');

  });


  //ajax完成分类的添加
  $("#addCat").on('click',function(){
    //获取到分类的名称和分类的类名
    var cat_name = $("input[name='cat_name']").val();
    var classname = $("input[name='classname']").val();
    //先验证数据
    if($.trim(cat_name) == '' || $.trim(classname) == ''){
      //把错误提示信息展示出来
      //$(".alert-danger")[0].style.display = 'block';
      $(".alert-danger").show().html(" <strong>错误！</strong>分类名称或类名不能为空");
      return false;
    }
    //数据验证成功，要隐藏错误的提示信息
    $(".alert-danger").hide();

    $.post("../api/addCat.php",{"cat_name":cat_name,"classname":classname},function(res){
        console.log(res);
        if(res.code == 200){
            //提示成功信息、清空输入的数据，把新增的记录追加在tbody下面
            $(".alert-success").show().html("<strong>"+res.message+"</strong>");
            $("input[name='cat_name']").val('');
            $("input[name='classname']").val('');
            //延迟2s,让成功的提示信息进行动画隐藏
            setTimeout(function(){
              $(".alert-success").fadeToggle();
            }, 2000);
            //拼接tr到tbody的后面去
           /* var tr = '<tr>\
                <td class="text-center"><input type="checkbox" value="'+res.insert_id+'"></td>\
                <td>'+cat_name+'</td>\
                <td>'+classname+'</td>\
                <td class="text-center">\
                  <a href="javascript:;" class="updCat btn btn-info btn-xs">编辑</a>\
                  <a href="javascript:;" class="delCat btn btn-danger btn-xs">删除</a>\
                </td>\
              </tr>';*/
            //调用模板进行渲染数据
            var tr = template('temp',{"insert_id":res.insert_id,"cat_name":cat_name,"classname":classname});
            //在tbody后面追加一个tr
            $("tbody").append(tr);
        }else{
            //失败提示
            $(".alert-danger").show().html("<strong>"+res.message+"</strong>");
        }
    },'json');

  });


  //ajax完成分类的删除(采用委托方式)
  $("tbody").on('click','.delCat',function(){
    var _self = $(this);
    //获取当前分类的cat_id
    var cat_id = _self.parents("tr").find('input').val();
    //判断用户是否要删除，防止误删除
    if(confirm('确认删除?')){
      //发送ajax请求进行删除
      $.get("../api/delCat.php",{"cat_id":cat_id},function(res){
        console.log(res);
        if(res.code == 200){
          //删除当前所在的tr行
          _self.parents("tr").remove();
          $(".alert-success").show().html("<strong>"+res.message+"</strong>");
        }else{
          //失败提示错误信息
          $(".alert-danger").show().html("<strong>"+res.message+"</strong>");
        }
      },'json')
    }
    
  });
</script>
</html>
