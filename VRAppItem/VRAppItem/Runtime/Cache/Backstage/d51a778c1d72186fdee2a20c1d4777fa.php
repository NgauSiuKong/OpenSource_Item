<?php if (!defined('THINK_PATH')) exit();?>﻿<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Bookmark" href="/Public/Backstage//favicon.ico" >
<LINK rel="Shortcut Icon" href="/Public/Backstage//favicon.ico" />
<link href="/Public/Backstage/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/Public/Backstage/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<link href="/Public/Backstage/css/style.css" rel="stylesheet" type="text/css" />
<link href="/Public/Backstage/lib/Hui-iconfont/1.0.7/iconfont.css" rel="stylesheet" type="text/css" />
<title>分类列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 标签管理 <span class="c-gray en">&gt;</span> 标签列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="cl pd-5 bg-1 bk-gray mt-20"> <span class="l"><a href="/index.php/Backstage/Label/labelAdd"class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加标签</a></span> <span class="r">共有数据：<strong><?php echo ($count); ?></strong> 条</span> </div>
    
	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="7">标签列表</th>
			</tr>
			<tr class="text-c">
				
				<th>标签名</th>
                <th>链接分类</th>
				<th>加入时间</th>
				<th>排序</th>
				<th>状态</th>
				<th>操作</th>
			</tr>
		</thead>
		<tbody>
            <?php if(is_array($label_list)): $i = 0; $__LIST__ = $label_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr class="text-c">
				<td><?php echo ($val["l_title"]); ?></td>
				<td><?php echo ($val["classname"]); ?></td>
                <td><?php echo ($val["l_addtime"]); ?></td>
				<td>
                    <i class="Hui-iconfont"><a href="/index.php/Backstage/Label/labelSort?handle=pre&id=<?php echo ($val["l_id"]); ?>">&#xe679;</a></i>
                    <i class="Hui-iconfont"><a href="/index.php/Backstage/Label/labelSort?handle=next&id=<?php echo ($val["l_id"]); ?>">&#xe674;</a></i>
                </td>
                <td class="td-status">
                        <?php if($val["l_status"] == 1): ?><span class="label label-success radius">已启用</span>
                        <?php else: ?>
                            <span class="label radius">已停用</span><?php endif; ?>
                </td>
				<td class="td-manage">
                <?php if($val["l_status"] == 1): ?><a style="text-decoration:none" onClick='admin_stop(this,"<?php echo ($val["l_id"]); ?>")' href="javascript:;" title="停用"><i class="Hui-iconfont">&#xe631;</i></a> 
                        <?php else: ?>
                            <a onClick='admin_start(this,<?php echo ($val["l_id"]); ?>)' href="javascript:;" title="启用" style="text-decoration:none"><i class="Hui-iconfont">&#xe615;</i></a><?php endif; ?>
                <a title="编辑" href="/index.php/Backstage/Label/edit?id=<?php echo ($val["id"]); ?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a>

                <a title="删除" href="javascript:;" onclick='admin_del(this,"<?php echo ($val["l_id"]); ?>")' class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a></td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
	</table>
</div>
<script type="text/javascript" src="/Public/Backstage/lib/jquery/1.9.1/jquery.min.js"></script>  
<script type="text/javascript" src="/Public/Backstage/lib/layer/2.1/layer.js"></script> 
<script type="text/javascript" src="/Public/Backstage/lib/laypage/1.2/laypage.js"></script> 
<script type="text/javascript" src="/Public/Backstage/lib/My97DatePicker/WdatePicker.js"></script> 
<script type="text/javascript" src="/Public/Backstage/js/H-ui.js"></script> 
<script type="text/javascript" src="/Public/Backstage/js/H-ui.admin.js"></script> 
<script type="text/javascript">

/*管理员-删除*/
function admin_del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
		//此处请求后台程序，下方是成功后的前台处理……
		 $.ajax({ 
                url:'/index.php/Backstage/Label/labelDel',
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
/*管理员-编辑*/
function admin_edit(title,url,id,w,h){
	layer_show(title,url,w,h);
}
/*管理员-停用*/
function admin_stop(obj,id){
	layer.confirm('确认要停用吗？',function(index){
		//此处请求后台程序，下方是成功后的前台处理……
		$.ajax({ 
                url:'/index.php/Backstage/Label/statusLab',
                type:'post',
                dataType:'json',
                data:{id:id},
                success:function(msg){ 
                
                }
            })
		$(obj).parents("tr").find(".td-manage").prepend('<a onClick="admin_start(this,id)" href="javascript:;" title="启用" style="text-decoration:none"><i class="Hui-iconfont">&#xe615;</i></a>');
		$(obj).parents("tr").find(".td-status").html('<span class="label label-default radius">已禁用</span>');
		$(obj).remove();
		layer.msg('已停用!',{icon: 5,time:1000});
	});
}

/*管理员-启用*/
function admin_start(obj,id){
	layer.confirm('确认要启用吗？',function(index){
		//此处请求后台程序，下方是成功后的前台处理……
		$.ajax({ 
                url:'/index.php/Backstage/Label/statusLab',
                type:'post',
                dataType:'json',
                data:{id:id},
                success:function(msg){ 
                
                }
            })
		
		$(obj).parents("tr").find(".td-manage").prepend('<a onClick="admin_stop(this,id)" href="javascript:;" title="停用" style="text-decoration:none"><i class="Hui-iconfont">&#xe631;</i></a>');
		$(obj).parents("tr").find(".td-status").html('<span class="label label-success radius">已启用</span>');
		$(obj).remove();
		layer.msg('已启用!', {icon: 6,time:1000});
	});
}
</script>
</body>
</html>