<?php 
include_once __DIR__.'/function.php';
//1、获取参数
$post_id = isset($_GET['post_id']) ? (int)$_GET['post_id'] : 0; 
//2、链接数据库
$link = connect();
//3、编写sql语句，查询数据
$sql = "select p.*,c.cat_name,u.nickname,(select count(*) from comments where post_id = p.post_id) as commentCount from posts  p 
left join category c on p.cat_id = c.cat_id
left join users u on p.user_id = u.user_id 
where p.post_id = $post_id";
$postDetail = query($link,$sql);
//$postDetail[0] = array();
error_reporting(0);
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
  <!-- 引入公共的aside.php文件 -->
  <?php include_once __DIR__."/aside.php"; ?>
     <div class="content">
      <div class="article">
          <div class="breadcrumb">
            <dl>
              <dt>当前位置：</dt>
              <dd><a href="javascript:;"><?php echo isset($postDetail[0]['cat_name'])?$postDetail[0]['cat_name']:''; ?></a></dd>
              <dd><?php echo $postDetail[0]['title']; ?></dd>
            </dl>
          </div>
          <h2 class="title">
            <a href="javascript:;"><?php echo $postDetail[0]['title']; ?></a>
          </h2>
          <div class="meta">
            <span><?php echo $postDetail[0]['nickname']; ?> 发布于 <?php echo $postDetail[0]['created']; ?></span>
            <div class="content-detail"><?php echo $postDetail[0]['content']; ?></div>
            
            <span>分类: <a href="javascript:;"><?php echo $postDetail[0]['cat_name']; ?></a></span>
            <span>阅读: (<?php echo $postDetail[0]['views']; ?>)</span>
            <span>评论: (<?php echo $postDetail[0]['commentCount']; ?>)</span>
  		      <a href="javascript:;"  class="like">
                  <i class="fa fa-thumbs-up"></i>
                  <span>赞(<?php echo $postDetail[0]['likes']; ?>)</span>
            </a>
          </div>
      </div>


      <div class="panel hots">
        <h3>热门推荐</h3>
        <ul>
          <li>
            <a href="javascript:;">
              <img src="uploads/hots_2.jpg" alt="">
              <span>星球大战:原力觉醒视频演示 电影票68</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="uploads/hots_3.jpg" alt="">
              <span>你敢骑吗？全球第一辆全功能3D打印摩托车亮相</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="uploads/hots_4.jpg" alt="">
              <span>又现酒窝夹笔盖新技能 城里人是不让人活了！</span>
            </a>
          </li>
          <li>
            <a href="javascript:;">
              <img src="uploads/hots_5.jpg" alt="">
              <span>实在太邪恶！照亮妹纸绝对领域与私处</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
    <div class="footer">
      <p>© 2016 XIU主题演示 本站主题由 themebetter 提供</p>
    </div>
  </div>
</body>
<script type="text/javascript" src="/static/assets/vendors/jquery/jquery.min.js"></script>
<script type="text/javascript">
  //ajax实现文章点赞的功能
  $(".like").on('click',function(){
      //用一个变量存储当前的对象，为了在内部函数可以引用
      var _self = $(this);
      //获取到文章的post_id
      var post_id = "<?php echo $_GET['post_id']; ?>";
      //发起一个ajax请求
      $.post("./api/clickLikes.php",{"post_id":post_id},function(res){
        //res是服务端响应回来的数据
        console.log(res);
        if(res.code == 200){
          //把新的点赞数量，直接赋值给对应的位置
          _self.children('span').html("赞("+res.newLikes+")");
        }
      },'json');
  });   
</script>
</html>
