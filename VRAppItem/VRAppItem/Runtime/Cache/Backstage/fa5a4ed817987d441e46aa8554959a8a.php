<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<link href="/Public/Backstage/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/Backstage/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<link href="/Public/Backstage/lib/Hui-iconfont/1.0.7/iconfont.css" rel="stylesheet" type="text/css" />
<title>用户管理</title>
</head>
<body>
<nav class="breadcrumb">
    <i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 用户中心 <span class="c-gray en">&gt;</span> 用户管理 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a>
</nav>
<div class="pd-20">
    <form action="/index.php/Backstage/Comment/index" method="post">
    <div class="text-c">搜索方式
        <select name = "searchtype">
            <option >---请选择---</option>
            <option value = "1">按内容</option>
            <option value = "2">按评论者</option>
            <option value = "3">按被评论资源</option>
        </select>
        <input type="text" class="input-text" style="width:250px" placeholder="选择搜索方式后,输入搜索内容" id="" name="search">
        <input type="submit" class="btn btn-success radius" value="搜  索">
    </div>
    </form>
	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="javascript:;" onclick="member_add('添加用户','member-add.html','','510')" class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加用户</a></span> <span class="r">共有数据：<strong><?php echo ($list['count']); ?></strong> 条</span> </div>
	<div class="mt-20">
	<table class="table table-border table-bordered table-hover table-bg">
		<thead>
			<tr class="text-c">
				<th>ID</th>
				<th>评论资源</th>
				<th>评论者</th>
				<th>评论时间</th>
				<th width="500">评论内容</th>
				<th>点赞数</th>
				<th>状态</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
            <?php if(is_array($list['selArr'])): foreach($list['selArr'] as $key=>$val): ?><tr class="text-c">
				<td><?php echo ($val["commentid"]); ?></td>
                <td>
                    <?php echo ($val["videotitle"]); ?>
                </td>
				<td>
                    <?php echo ($val["username"]); ?>              
                </td>
				<td><?php echo ($val["time"]); ?></td>
				<td><?php echo ($val["content"]); ?></td>
				<td>
                    <?php echo ($val["like"]); ?>           
                </td>
				<td class="td-status">
                    <?php if($val["comstatus"] == 1): ?><span class="label label-success radius">已启用</span>
                    <?php else: ?>
                        <span class="label label-defaunt radius">已禁用</span><?php endif; ?>
                </td>
				<td class="td-manage">
                    <?php if($val["comstatus"] == 1): ?><a style="text-decoration:none" onClick="member_stop(this,<?php echo ($val["commentid"]); ?>)" href="javascript:;" title="停用">
                        <i class="Hui-iconfont">&#xe631;</i>
                    </a> 
                    <?php else: ?>
                        <a style="text-decoration:none" onClick="member_start(this,<?php echo ($val["commentid"]); ?>)" href="javascript:;" title="启用">
                        <i class="Hui-iconfont">&#xe6e1;</i>
                    </a><?php endif; ?>
                    <a title="删除" href="javascript:;" onclick="member_del(this,'<?php echo ($val["commentid"]); ?>')" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i>
                    </a>
                    
                </td>
			</tr><?php endforeach; endif; ?>
		</tbody> 
	</table>
    <?php echo ($list['show']); ?>
	</div>
</div>
<script type="text/javascript" src="/Public/Backstage/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="/Public/Backstage/lib/layer/2.1/layer.js"></script>
<script type="text/javascript" src="/Public/Backstage/lib/laypage/1.2/laypage.js"></script> 
<script type="text/javascript" src="/Public/Backstage/lib/My97DatePicker/WdatePicker.js"></script> 
<script type="text/javascript" src="/Public/Backstage/lib/datatables/1.10.0/jquery.dataTables.min.js"></script> 
<script type="text/javascript" src="/Public/Backstage/js/H-ui.js"></script> 
<script type="text/javascript" src="/Public/Backstage/js/H-ui.admin.js"></script> 
<script type="text/javascript">
$(function(){
	$('.table-sort').dataTable({
		"aaSorting": [[ 1, "desc" ]],//默认第几个排序
		"bStateSave": true,//状态保存
		"aoColumnDefs": [
		  //{"bVisible": false, "aTargets": [ 3 ]} //控制列的隐藏显示
		  {"orderable":false,"aTargets":[0,8,9]}// 制定列不参与排序
		]
	});
	$('.table-sort tbody').on( 'click', 'tr', function () {
		if ( $(this).hasClass('selected') ) {
			$(this).removeClass('selected');
		}
		else {
			table.$('tr.selected').removeClass('selected');
			$(this).addClass('selected');
		}
	});
});
/*用户-添加*/
function member_add(title,url,w,h){
	layer_show(title,url,w,h);
}
/*用户-查看*/
function member_show(title,url,id,w,h){
	layer_show(title,url,w,h);
}
/*用户-停用*/
function member_stop(obj,id){
	layer.confirm('确认要停用吗？',function(index){
        $.ajax({ 
                url:'/index.php/Backstage/Comment/status',
                type:'post',
                dataType:'json',
                data:{id:id},
                success:function(msg){ 
                
                }
            })

		$(obj).parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" onClick="member_start(this,<?php echo ($val["commentid"]); ?>)" href="javascript:;" title="启用"><i class="Hui-iconfont">&#xe6e1;</i></a>');
		$(obj).parents("tr").find(".td-status").html('<span class="label label-defaunt radius">已停用</span>');
		$(obj).remove();
		layer.msg('已停用!',{icon: 5,time:1000});
	});
}

/*用户-启用*/
function member_start(obj,id){
	layer.confirm('确认要启用吗？',function(index){
        $.ajax({ 
                url:'/index.php/Backstage/Comment/status',
                type:'post',
                dataType:'json',
                data:{id:id},
                success:function(msg){ 
                
                }
            })
		$(obj).parents("tr").find(".td-manage").prepend('<a style="text-decoration:none" onClick="member_stop(this,<?php echo ($val["commentid"]); ?>)" href="javascript:;" title="停用"><i class="Hui-iconfont">&#xe631;</i></a>');
		$(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已启用</span>');
		$(obj).remove();
		layer.msg('已启用!',{icon: 6,time:1000});
	});
}
/*用户-编辑*/
function member_edit(title,url,id,w,h){
	layer_show(title,url,w,h);
}
/*密码-修改*/
function change_password(title,url,id,w,h){
	layer_show(title,url,w,h);	
}
/*用户-删除*/
function member_del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
            $.ajax({ 
                url:'/index.php/Backstage/Comment/delete',
                type:'post',
                dataType:'json',
                data:{id:id},
                success:function(msg)
                { 

                }
            });
		$(obj).parents("tr").remove();
		layer.msg('已删除!',{icon:1,time:1000});
	});
}
</script> 
</body>
</html>