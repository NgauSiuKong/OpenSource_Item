<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<link href="/VRAppItem/Public/Backstage/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/VRAppItem/Public/Backstage/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<link href="/VRAppItem/Public/Backstage/css/style.css" rel="stylesheet" type="text/css" />
<link href="/VRAppItem/Public/Backstage/lib/Hui-iconfont/1.0.7/iconfont.css" rel="stylesheet" type="text/css" />
<title>;轮播图列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 轮播图管理 <span class="c-gray en">&gt;</span> 轮播图列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<span class="r">共有数据：<strong><?php echo ($vid_no_father['count']); ?></strong> 条</span> </div>
	<div class="mt-20">
		<table class="table table-border table-bordered table-bg table-hover table-sort">
			<thead>
				<tr class="text-c">
					<th width="40"><input name="" type="checkbox" value=""></th>
                    <th>视频标题</th>
                    <th>上传者</th>
                    <th>视频描述</th>
                    <th width="100">视频标签</th>
                    <th>播放量</th>
                    <th>评论数</th>
                    <th>视频限制</th>
                    <th>添加时间</th>
                    <th>视频缩略图</th>
                    <th>视频详情图</th>
                    <th>发布状态</th>
				</tr>
			</thead>
			<tbody>
                <?php if(is_array($vid_no_father['selArr'])): foreach($vid_no_father['selArr'] as $key=>$val): ?><tr class="text-c">
					<td><input name="" type="checkbox" value=""></td>
                    <td><?php echo ($val["title"]); ?></td>
                    <td><?php echo ($val["publisherid"]); ?></td>
                    <td><?php echo ($val["fid"]); ?></td>
                    <td><?php echo ($val["describe"]); ?></td>
                    <td><?php echo ($val["label"]); ?></td>
                    <td><?php echo ($val["playnum"]); ?></td>
                    <td><?php echo ($val["comment"]); ?></td>
                    <td>
                        <?php echo ($val["limit"]); ?>
                        <br/>
                        <?php echo ($val["video"]); ?>
                    </td>
                    <td><?php echo ($val["addtime"]); ?></td>
					<td>
                        <img src="/VRAppItem/Public/<?php echo ($val["firstimgurl"]); ?>" height="100"/>
                    </td>
                    <td>
                        <img src="/VRAppItem/Public/<?php echo ($val["secondimgurl"]); ?>" height="100"/>
                    </td>
					<td class="td-manage">
                        <?php if($val["status"] == 1): ?><a style="text-decoration:none" onClick='article_stop(this,"<?php echo ($val["id"]); ?>")' href="javascript:;" title="下架"><i class="Hui-iconfont">&#xe6de;</i></a>
                        <?php else: ?>
                            <a style="text-decoration:none" onClick='article_start(this,"<?php echo ($val["id"]); ?>")' href="javascript:;" title="发布"><i class="Hui-iconfont">&#xe603;</i></a><?php endif; ?>

                        <a class="ml-5" href="/VRAppItem/index.php/Backstage/Video/edit?id=<?php echo ($val["id"]); ?>" title="编辑">
                            <i class="Hui-iconfont">&#xe6df;</i>
                        </a>
                        <a style="text-decoration:none" class="ml-5" onClick='article_del(this,"<?php echo ($val["id"]); ?>")' href="javascript:;" title="删除">
                            <i class="Hui-iconfont">&#xe6e2;</i>
                        </a>
                    </td>
				</tr><?php endforeach; endif; ?>
			</tbody>
		</table>
        <div><?php echo ($vid_no_father['show']); ?></div>
	</div>
</div>
<script type="text/javascript" src="/VRAppItem/Public/Backstage/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="/VRAppItem/Public/Backstage/lib/layer/2.1/layer.js"></script> 
<script type="text/javascript" src="/VRAppItem/Public/Backstage/lib/My97DatePicker/WdatePicker.js"></script> 
<script type="text/javascript" src="/VRAppItem/Public/Backstage/lib/datatables/1.10.0/jquery.dataTables.min.js"></script> 
<script type="text/javascript" src="/VRAppItem/Public/Backstage/js/H-ui.js"></script> 
<script type="text/javascript" src="/VRAppItem/Public/Backstage/js/H-ui.admin.js"></script> 
<script type="text/javascript">
$('.table-sort').dataTable({
	"aaSorting": [[ 1, "desc" ]],//默认第几个排序
	"bStateSave": true,//状态保存
	"aoColumnDefs": [
	  //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
	  {"orderable":false,"aTargets":[0,8]}// 制定列不参与排序
	]
});
    /*轮播图-编辑*/
    function article_edit(title,url,id,w,h){
        var index = layer.open({
            type: 2,
            title: title,
            content: url
        });
        layer.full(index);
    }
    /*资讯-删除*/
    function article_del(obj,id){
        layer.confirm('确认要删除吗？',function(index){
            $.ajax({ 
                url:'/VRAppItem/index.php/Backstage/Video/delNoFatherRey',
                type:'post',
                dataType:'json',
                data:{id:id},
                success:function(msg)
                { 

                }
            })
            $(obj).parents("tr").remove();
            layer.msg('已删除!',{icon:1,time:1000});
        });
    }

    /*资讯-下架*/
    function article_stop(obj,id){
        layer.confirm('确认要下架吗？',function(index){
            $.ajax({ 
                url:'/VRAppItem/index.php/Backstage/Video/status',
                type:'post',
                dataType:'json',
                data:{id:id},
                success:function(msg){ 
                
                }
            })
            $(obj).parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" onClick="article_start(this,id)" href="javascript:;" title="发布"><i class="Hui-iconfont">&#xe603;</i></a>');
            $(obj).parents("tr").find(".td-status").html('<span class="label label-defaunt radius">已下架</span>');
            $(obj).remove();
            layer.msg('已下架!',{icon: 5,time:1000});
        });
    }

    /*资讯-发布*/
    function article_start(obj,id){
        layer.confirm('确认要发布吗？',function(index){
            $.ajax({ 
                url:'/VRAppItem/index.php/Backstage/Video/status',
                type:'post',
                dataType:'json',
                data:{id:id},
                success:function(msg){ 
                
                }
            });
            $(obj).parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" onClick="article_stop(this,id)" href="javascript:;" title="下架"><i class="Hui-iconfont">&#xe6de;</i></a>');
            $(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已发布</span>');
            $(obj).remove();
            layer.msg('已发布!',{icon: 6,time:1000});
        });
    }
    

</script>
</body>
</html>