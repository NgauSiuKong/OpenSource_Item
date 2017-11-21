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
<link href="/VRAppItem/Public/Backstage/lib/icheck/icheck.css" rel="stylesheet" type="text/css" />
<link href="/VRAppItem/Public/Backstage/lib/Hui-iconfont/1.0.7/iconfont.css" rel="stylesheet" type="text/css" />
<link href="/VRAppItem/Public/Backstage/lib/webuploader/0.1.5/webuploader.css" rel="stylesheet" type="text/css" />
<title>添加顶级分类</title>
</head>
<body>
<div class="pd-20">
	<form action="/VRAppItem/index.php/Backstage/Class/claInsLabel" method="post" class="form form-horizontal" id="form-article-add">
        <input type="hidden" value="<?php echo ($id); ?>" name="id"/>
        <div class="row cl">
            <label class="form-label col-2">链接类型：</label>
                <?php if(is_array($labelList)): foreach($labelList as $k=>$v): ?><input type="checkbox"  value="<?php echo ($k); ?>" name="lid[]"><?php echo ($v); ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php endforeach; endif; ?>
        </div>
		<div class="row cl">
			<div class="col-10 col-offset-2">
                <input type="submit" value="提交"  class="btn btn-primary radius"/>
                  &nbsp;&nbsp;&nbsp;
                <input type="reset" value="重置"  class="btn btn-primary radius"/>
			</div>
		</div>
	</form>
</div>
</div>
<script type="text/javascript" src="/VRAppItem/Public/Backstage/lib/jquery/1.9.1/jquery.min.js"></script> 
<script type="text/javascript" src="/VRAppItem/Public/Backstage/lib/layer/2.1/layer.js"></script> 
<script type="text/javascript" src="/VRAppItem/Public/Backstage/lib/My97DatePicker/WdatePicker.js"></script> 
<script type="text/javascript" src="/VRAppItem/Public/Backstage/lib/icheck/jquery.icheck.min.js"></script> 
<script type="text/javascript" src="/VRAppItem/Public/Backstage/lib/Validform/5.3.2/Validform.min.js"></script> 
<script type="text/javascript" src="/VRAppItem/Public/Backstage/lib/webuploader/0.1.5/webuploader.min.js"></script> 
<script type="text/javascript" src="/VRAppItem/Public/Backstage/js/H-ui.js"></script> 
<script type="text/javascript" src="/VRAppItem/Public/Backstage/js/H-ui.admin.js"></script> 

</body>
</html>