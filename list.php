<?php 
//引入公共的函数文件function.php
include_once __DIR__."/function.php";
//echo __DIR__;die; // G:\bx.com
//1、接收参数cat_id
//$cat_id = isset($_GET['cat_id'])?$_GET['cat_id']:0;
//'abc' + 0 => 把字符串转化整形 0 +0 = 0
//$cat_id = $_GET['cat_id']+0; //强制转化为整形
//改为
$cat_id = isset($_GET['cat_id'])?(int)$_GET['cat_id']:0; 
//2、链接数据库
$link = connect();
//3、编写sql语句
$sql = " SELECT t1.*,t2.cat_name,t3.nickname,(select count(*) from comments where post_id = t1.post_id) commentCount FROM
posts t1 
LEFT JOIN category t2 ON t1.cat_id = t2.cat_id
left join users t3 on t1.user_id = t3.user_id
where t1.cat_id = $cat_id
order by t1.post_id desc
limit 3";

//echo $sql;exit;
//4、执行sql语句,获取数据
/*$res = mysqli_query($link,$sql);
$postDatas = [];
while($row = mysqli_fetch_assoc($res)){
  $postDatas[]= $row;
}*/
$postDatas = query($link,$sql);
//dump($postDatas);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>阿里百秀-发现生活，发现美!</title>
  <link rel="stylesheet" href="/static/assets/css/style.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
</head>
<body>
  <div class="wrapper">
    <div class="topnav">
      <ul>
        <li><a href="javascript:;"><i class="fa fa-glass"></i>奇趣事</a></li>
        <li><a href="javascript:;"><i class="fa fa-phone"></i>潮科技</a></li>
        <li><a href="javascript:;"><i class="fa fa-fire"></i>会生活</a></li>
        <li><a href="javascript:;"><i class="fa fa-gift"></i>美奇迹</a></li>
      </ul>
    </div>
    <!-- 引入aside.php的公共文件 -->
    <!-- G:/bx.com/aside.php -->
    <?php   include_once __DIR__.'/aside.php';?>
    <div class="content">
      <div class="panel new">
        <!-- 获取出下标为0的元素，取出下标cat_name的值 -->
        <h3><?php echo isset($postDatas[0]['cat_name'])?$postDatas[0]['cat_name']:''; ?></h3>
        <?php foreach($postDatas as $post): ?>
        <div class="entry">
          <div class="head">
            <a href="/detail.php?post_id=<?php echo $post['post_id']; ?>"><?php echo $post['title']; ?></a>
          </div>
          <div class="main">
            <p class="info"><?php echo $post['nickname']; ?> 发表于 <?php echo $post['created']; ?></p>
            <p class="brief"><?php echo $post['content']; ?></p>
            <p class="extra">
              <span class="reading">阅读(<?php echo $post['views']; ?>)</span>
              <span class="comment">评论(<?php echo $post['commentCount']; ?>)</span>
              <a href="javascript:;" class="like">
                <i class="fa fa-thumbs-up"></i>
                <span>赞(<?php echo $post['likes']; ?>)</span>
              </a>
              <a href="javascript:;" class="tags">
                分类：<span><?php echo $post['cat_name']; ?></span>
              </a>
            </p>
            <a href="javascript:;" class="thumb">
              <img src="<?php echo $post['feature']; ?>" alt="">
            </a>
          </div> 
        </div>
        <!-- 定义一个变量，记录最后一个文章的post_id -->
        <?php  $lastPostId = $post['post_id']; ?>
        <?php  endforeach;?>
        <!-- 点击加载更多功能 -->
        <div class="loadmore">
          <button class="btn" >加载更多</button>
        </div>
      </div>
    </div>
    <div class="footer">
      <p>© 2016 XIU主题演示 本站主题由 themebetter 提供</p>
    </div>
  </div>
</body>
<script type="text/javascript" src="/static/assets/vendors/jquery/jquery.min.js"></script>
<script type="text/javascript" src="/static/assets/vendors/art-template/template-web.js"></script>
<!-- 建议一个文章的模板html结构 -->
<script type="text/template" id='temp'>
  {{each data value }}
    <div class="entry">
        <div class="head">
          <a href="javascript:;">{{value.title}}</a>
        </div>
        <div class="main">
          <p class="info">{{value.nickname}} 发表于{{value.created}}</p>
          <p class="brief">{{value.content}}</p>
          <p class="extra">
            <span class="reading">阅读({{value.views}})</span>
            <span class="comment">评论({{value.commentCount}})</span>
            <a href="javascript:;" class="like">
              <i class="fa fa-thumbs-up"></i>
              <span>赞({{value.likes}})</span>
            </a>
            <a href="javascript:;" class="tags">
              分类：<span>{{value.cat_name}}</span>
            </a>
          </p>
          <a href="javascript:;" class="thumb">
            <img src="{{value.feature}}" alt="">
          </a>
        </div> 
    </div>
  {{/each}}
</script>
<script type="text/javascript">
  //获取最后一遍文章的post_id
  var lastPostId = "<?php echo isset($lastPostId)?$lastPostId:0; ?>";

  //ajax实现点击加载更多文章
  $(".loadmore").click(function(){
      //获取当前分类的cat_id
      var cat_id = "<?php  echo $_GET['cat_id']; ?>";
      var _self = $(this);
      //防止用户频繁的点击，可以让按钮禁用，且给加载的提示 
      _self.children('button').prop('disabled',true).html('loading...');
      $.get("./api/loadMorePost.php",{"cat_id":cat_id,"lastPostId":lastPostId},function(res){
        console.log(res);
        //判断是否有数据
        if(res.code == 200){
          var data = res.data;
          console.log(data);
          //动态构造数据
         // var html = ""; //用于后面拼接的
         /* $.each(data,function(){
            html += '<div class="entry">\
              <div class="head">\
                <a href="javascript:;">'+ this.title +'</a>\
              </div>\
              <div class="main">\
                <p class="info">'+ this.nickname+' 发表于 '+ this.created+'</p>\
                <p class="brief">'+ this.content+'</p>\
                <p class="extra">\
                  <span class="reading">阅读('+ this.views+')</span>\
                  <span class="comment">评论('+ this.commentCount+')</span>\
                  <a href="javascript:;" class="like">\
                    <i class="fa fa-thumbs-up"></i>\
                    <span>赞('+ this.likes+')</span>\
                  </a>\
                  <a href="javascript:;" class="tags">\
                    分类：<span>'+ this.cat_name+'</span>\
                  </a>\
                </p>\
                <a href="javascript:;" class="thumb">\
                  <img src="'+ this.feature+'" alt="">\
                </a>\
              </div> \
            </div>';
            //获取到最后一篇文章的id复制给变量lastPostId
            
          });*/
          //调用定义好的模板进行渲染数据
          var html = template('temp',{"data":data}); //[{},{},{}]
          //获取数组的最后一个元素下标
          var lastIndex = data.length - 1;
          //通过下标取出post_id
          lastPostId = res.data[lastIndex].post_id;
          console.log(lastPostId);
          //把html要动态追加在加载更多按钮的前面即可
          $(".loadmore").before(html);
        }
        //恢复按钮可以点击
        _self.children('button').prop('disabled',false).html('加载更多');
      },'json');
  });
</script>
</html>