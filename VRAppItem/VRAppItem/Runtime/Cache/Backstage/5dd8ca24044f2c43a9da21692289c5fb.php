<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-comp|ie-stand">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
<meta http-equiv="Cache-Control" content="no-siteapp" />
<LINK rel="Bookmark" href="/VRAppItem/Public/Backstage//favicon.ico" >
<LINK rel="Shortcut Icon" href="/VRAppItem/Public/Backstage//favicon.ico" />
<link href="/VRAppItem/Public/Backstage/css/H-ui.min.css" rel="stylesheet" type="text/css" />
<link href="/VRAppItem/Public/Backstage/css/H-ui.admin.css" rel="stylesheet" type="text/css" />
<link href="/VRAppItem/Public/Backstage/css/style.css" rel="stylesheet" type="text/css" />
<link href="/VRAppItem/Public/Backstage/lib/Hui-iconfont/1.0.7/iconfont.css" rel="stylesheet" type="text/css" />
<title>分类列表</title>
</head>
<body>
<nav class="breadcrumb"><i class="Hui-iconfont">&#xe67f;</i> 首页 <span class="c-gray en">&gt;</span> 分类管理 <span class="c-gray en">&gt;</span> 分类列表 <a class="btn btn-success radius r mr-20" style="line-height:1.6em;margin-top:3px" href="javascript:location.replace(location.href);" title="刷新" ><i class="Hui-iconfont">&#xe68f;</i></a></nav>
<div class="pd-20">
	<div class="cl pd-5 bg-1 bk-gray mt-20"> 
    <span class="l"><a href="/VRAppItem/index.php/Backstage/Class/classAdd"class="btn btn-primary radius"><i class="Hui-iconfont">&#xe600;</i> 添加顶级分类</a></span>
    </div>
    
	<table class="table table-border table-bordered table-bg">
		<thead>
			<tr>
				<th scope="col" colspan="9">分类列表</th>
			</tr>
			<tr class="text-c">
                <th>分类级别</th>
				<th>类名</th>
                <th>描述</th>
                <th>添加时间</th>
                <th>状态</th>
                <th>添加子分类</th>
                <th>排序</th>
                <th>操作</th>
			</tr>
		</thead>
		<tbody>
            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$val): $mod = ($i % 2 );++$i;?><tr <?php if($val["c_fid"] == 0): ?>style="background-color:#D1D1D1;"<?php endif; ?> class="text-c">
                <td><b><?php echo ($val["classgrade"]); ?></b>级分类</td>
				<td><b><?php echo ($val["c_title"]); ?></b></td>
                <td><?php echo ($val["c_describe"]); ?></td>
                <td><?php echo ($val["c_addtime"]); ?></td>
                
                <td>
                    <a class="btn btn-default radius" href="/VRAppItem/index.php/Backstage/Class/classSonAdd?c_id=<?php echo ($val["c_id"]); ?>"><i class="Hui-iconfont">&#xe600;</i>&nbsp;添加子分类</a>
                </td>
                <td class="td-status">
                        <?php if($val["c_status"] == 1): ?><span class="label label-success radius">已启用</span>
                        <?php else: ?>
                            <span class="label radius">已停用</span><?php endif; ?>
                </td>
				<td>
                <a  href="/VRAppItem/index.php/Backstage/Class/classSort?handle=pre&c_id=<?php echo ($val["c_id"]); ?>" class="ml-5"><i class="Hui-iconfont">&#xe679;</i></a> 
                <a  href="/VRAppItem/index.php/Backstage/Class/classSort?handle=next&c_id=<?php echo ($val["c_id"]); ?>" class="ml-5"><i class="Hui-iconfont">&#xe674;</i></a> 
                &nbsp;&nbsp;&nbsp;
                </td>
                <td class="td-manage">
                <?php if($val["c_status"] == 1): ?><a style="text-decoration:none" onClick='admin_stop(this,"<?php echo ($val["c_id"]); ?>")' href="javascript:;" title="停用"><i class="Hui-iconfont">&#xe631;</i></a> 
                        <?php else: ?>
                            <a onClick='admin_start(this,<?php echo ($val["c_id"]); ?>)' href="javascript:;" title="启用" style="text-decoration:none"><i class="Hui-iconfont">&#xe615;</i></a><?php endif; ?>
                <a title="编辑" href="/VRAppItem/index.php/Backstage/Class/classEdit?id=<?php echo ($val["c_id"]); ?>" class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6df;</i></a> 

                <a title="删除" href="javascript:;" onclick='admin_del(this,"<?php echo ($val["c_id"]); ?>")' class="ml-5" style="text-decoration:none"><i class="Hui-iconfont">&#xe6e2;</i></a>
                </td>
			</tr><?php endforeach; endif; else: echo "" ;endif; ?>
        </tbody>
	</table>

    
    <div><?php echo ($list['show']); ?></div>
</div>
<script type="text/javascript" src="/VRAppItem/Public/Backstage/lib/jquery/1.9.1/jquery.min.js"></script>  
<script type="text/javascript" src="/VRAppItem/Public/Backstage/lib/layer/2.1/layer.js"></script> 
<script type="text/javascript" src="/VRAppItem/Public/Backstage/lib/laypage/1.2/laypage.js"></script> 
<script type="text/javascript" src="/VRAppItem/Public/Backstage/lib/My97DatePicker/WdatePicker.js"></script> 
<script type="text/javascript" src="/VRAppItem/Public/Backstage/js/H-ui.js"></script> 
<script type="text/javascript" src="/VRAppItem/Public/Backstage/js/H-ui.admin.js"></script> 
<script type="text/javascript">

/*管理员-删除*/
function admin_del(obj,id){
	layer.confirm('确认要删除吗？',function(index){
		//此处请求后台程序，下方是成功后的前台处理……
		 $.ajax({ 
                url:'/VRAppItem/index.php/Backstage/Class/classDel',
                type:'post',
                dataType:'json',
                data:{id:id},
                success:function(msg)
                { 
                    alert(msg);
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
                url:'/VRAppItem/index.php/Backstage/Class/statusEdit',
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
                url:'/VRAppItem/index.php/Backstage/Class/statusEdit',
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