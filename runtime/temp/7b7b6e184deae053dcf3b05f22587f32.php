<?php if (!defined('THINK_PATH')) exit(); /*a:2:{s:59:"D:\objects\yingjing/application/index\view\index\index.html";i:1495986791;s:59:"D:\objects\yingjing/application/index\view\public\base.html";i:1495759350;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<link rel="stylesheet" href="__PUBLIC__/bootstrap/css/bootstrap.css">
<link rel="stylesheet" href="__PUBLIC__/static/css/upload.css">
<script src="__PUBLIC__/static/js/jquery.js"></script>
<script src="__PUBLIC__/static/js/jquery.validate.min.js"></script>
<!--<script src="__PUBLIC__/static/js/jquery.form.js"></script>-->
<script src="__PUBLIC__/bootstrap/js/bootstrap.js"></script>
<script src="__PUBLIC__/layer/layer.js"></script>

<body>

<h2 class="text-center">文章管理列表</h2>
<div class="text-center col-lg-11">
    <div class="text-left col-lg-11">
        <button id="update_file" class="btn btn-success">上传新文件</button>
    </div>
    <div class="col-lg-12 text-right">
        <input type="text" value="" id="search" name="search" placeholder="输入文章标题">
        <span class="btn-default btn" id="search_btn">搜索</span>
    </div>
    <table id="content_table" class="PolicyTable table table-striped">
        <thead>
        <tr class="">
            <th>序号</th>
            <th>原文件名</th>
            <th>文章标题</th>
            <th>上传时间</th>
            <th>操作</th>
        </tr>
        </thead>
        <?php if(is_array($data) || $data instanceof \think\Collection || $data instanceof \think\Paginator): $i = 0; $__LIST__ = $data;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$va): $mod = ($i % 2 );++$i;?>
        <tr class="poor_list" id="list<?php echo $va['id']; ?>">
            <td><?php echo $i; ?></td>
            <td><?php echo $va['filename']; ?></td>
            <td><a href="/index/index/article_info/id/<?php echo $va['id']; ?>"><?php echo $va['title']; ?></a></td>
            <td><?php echo date('Y-m-d H:s',$va['upload_time']); ?></td>
            <td>
                <button class="edit_content btn-success" type="button" name="<?php echo $va['id']; ?>">编辑</button>
                <button class="delete_content btn-danger" type="button" name="<?php echo $va['id']; ?>">删除</button>
            </td>
        </tr>
        <?php endforeach; endif; else: echo "" ;endif; ?>
    </table>
</div>
<ul id="pagination" class="pagination">
   <?php echo $page; ?>
</ul>

<script>
    $(function () {
        /*文件上传*/
        $("#update_file").click(function () {
            layer.open({
                type: 2,
                title: '文件上传',
                shadeClose: true,
                shade: false,
                maxmin: true, //开启最大化最小化按钮
                area: ['893px', '600px'],
                content: '/index/index/form_test',
                end: function () {
                    /*ajax请求数据*/
                     get_data_list('',1)
                }
            });
        });
        /*修改文章*/
        $(".edit_content").click(function () {
            var id = $(this).attr('name');
            window.location.href='/index/index/update_content/id/'+id;
        });
        /*删除文章*/
        $(".delete_content").click(function () {
            var id = $(this).attr('name');
            layer.confirm('您确定要删除这篇文章吗？', {
                btn: ['确定','取消'] //按钮
            }, function(){
                $.ajax({
                    url:'delete_article',
                    type:'post',
                    data:{id:id},
                    success:function(data){
                        if(data){
                            layer.msg("文章被成功删除",{time:3000,icon:1});
                            $("list"+id).remove();
                        }else{
                            layer.msg("哦豁/(ㄒoㄒ)/~~删除失败",{time:3000,icon:5});
                        }
                    }
                });
            });
        });
        /*搜索文章标题*/
        $("#search_btn").click(function () {
            var search = $("#search").val();
            get_data_list(search,1);
        });
        /*分页查询*/
        $("#pagination>li>.my_page").click(function () {
            var search = $("#search").val();
            var index_page = $(this).attr("name");
            get_data_list(search,index_page);
        });
        /*search:搜索内容，index_pge:当前页*/
        function get_data_list(search,index_page)
        {
            $.ajax({
                url:'/index/index/ajax_index',
                type:'post',
                data:{
                    search:search,
                    page:index_page
                },
                beforeSend:function(){
                    var index = layer.load(0, {shade: false});
                },
                success: function (json) {
                    console.log(json);
                    layer.closeAll();
                    $(".poor_list").remove();
                    $("#content_table").append(json.data);
                    /*$(".pagination>li").remove();
                    $(".pagination").append(json.page);*/
                },
                error: function () {
                    layer.msg("数据获取失败",{time:3000,icon:5});
                    setInterval(function () {
                        layer.closeAll();
                    },3000);
                }
            });
        }
    });
</script>

</body>
</html>