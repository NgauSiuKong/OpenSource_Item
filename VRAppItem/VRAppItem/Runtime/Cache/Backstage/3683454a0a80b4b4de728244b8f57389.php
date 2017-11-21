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
<style type="text/css">
#preview{width:260px;height:190px;border:1px solid #000;overflow:hidden;}
#imghead {filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=image);}
</style>
<title>新增图片</title>
</head>
<body>
<div class="pd-20">
	<form action="/VRAppItem/index.php/Backstage/Panorama/panoramaEdit" method="post" class="form form-horizontal" id="form-article-add" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo ($panoramaInfo['id']); ?>"/>
        <input type="hidden" name="imageurl" value="<?php echo ($panoramaInfo['imageurl']); ?>"/>
		<div class="row cl">
			<label class="form-label col-2">图片标题：</label>
			<div class="formControls col-10">
				<input type="text" class="input-text" value="<?php echo ($panoramaInfo['name']); ?>" id="imageatitle" name="name">
			</div>
		</div>
        <div class="row cl">
            <label class="form-label col-2">图片副标题：</label>
            <div class="formControls col-10">
                <input type="text" class="input-text" value="<?php echo ($panoramaInfo['subname']); ?>" id="imageatitle" name="subname">
            </div>
        </div>
		<div class="row cl">
			<label class="form-label col-2">图片描述：</label>
			<div class="formControls col-10">
				<input type="text" class="input-text" value="<?php echo ($panoramaInfo['describe']); ?>" id="imageadescribe" name="describe">
			</div>
		</div>
        <div class="row cl">
            <label class="form-label col-2">图片浏览量(<b>谨慎</b>)</label>
            <div class="formControls col-10">
                <input type="text" class="input-text" value="<?php echo ($panoramaInfo['click']); ?>" id="imageadescribe" name="click">
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-2">图片类型：</label>
                <input type="radio"  value="0" id="sellinkurl" name="limit" <?php if($panoramaInfo['limit'] == 0): ?>checked<?php endif; ?>>普通图片
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio"  value="1" id="sellinkvideo" name="limit" <?php if($panoramaInfo['limit'] == 1): ?>checked<?php endif; ?>>会员图片
        </div>
         <div class="row cl">
           <label class="form-label col-2">点击上传视频详情图</label>
            <div class="formControls col-10">
            <div class="uploader-thum-container">
                <div id="preview">
                     <img id="imghead" width=100% height=auto border=0 src="/VRAppItem/Public/<?php echo ($panoramaInfo['imageurl']); ?>">
                </div>
                <input name="panorama" type="file" onchange="previewImage(this)" />
            </div>
            </div>
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
<script type="text/javascript">
      //图片上传预览    IE是用了滤镜。
        function previewImage(file)
        {
          var MAXWIDTH  = 260; 
          var MAXHEIGHT = 180;
          var div = document.getElementById('preview');
          if (file.files && file.files[0])
          {
              div.innerHTML ='<img id=imghead>';
              var img = document.getElementById('imghead');
              img.onload = function(){
                var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
                img.width  =  rect.width;
                img.height =  rect.height;
//                 img.style.marginLeft = rect.left+'px';
                img.style.marginTop = rect.top+'px';
              }
              var reader = new FileReader();
              reader.onload = function(evt){img.src = evt.target.result;}
              reader.readAsDataURL(file.files[0]);
          }
          else //兼容IE
          {
            var sFilter='filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale,src="';
            file.select();
            var src = document.selection.createRange().text;
            div.innerHTML = '<img id=imghead>';
            var img = document.getElementById('imghead');
            img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
            var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
            status =('rect:'+rect.top+','+rect.left+','+rect.width+','+rect.height);
            div.innerHTML = "<div id=divhead style='width:"+rect.width+"px;height:"+rect.height+"px;margin-top:"+rect.top+"px;"+sFilter+src+"\"'></div>";
          }
        }
        function clacImgZoomParam( maxWidth, maxHeight, width, height ){
            var param = {top:0, left:0, width:width, height:height};
            if( width>maxWidth || height>maxHeight )
            {
                rateWidth = width / maxWidth;
                rateHeight = height / maxHeight;
                 
                if( rateWidth > rateHeight )
                {
                    param.width =  maxWidth;
                    param.height = Math.round(height / rateWidth);
                }else
                {
                    param.width = Math.round(width / rateHeight);
                    param.height = maxHeight;
                }
            }
            param.left = Math.round((maxWidth - param.width) / 2);
            param.top = Math.round((maxHeight - param.height) / 2);
            return param;
        }
</script>     
</body>
</html>