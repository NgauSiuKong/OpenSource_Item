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
<title>新增资源</title>
<style type="text/css">
#preview_small{width:200px;height:150px;border:1px solid #000;overflow:hidden;}
#preview_big{width:200px;height:150px;border:1px solid #000;overflow:hidden;}
#imghead_small {filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=image);}
#imghead_big {filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=image);}
</style>
</head>
<body>
<div class="pd-20">
	<form action="/VRAppItem/index.php/Backstage/Video/videoAdd" method="post" class="form form-horizontal" id="form-article-add" enctype="multipart/form-data">
		<div class="row cl">
			<label class="form-label col-2">资源标题</label>
			<div class="formControls col-10">
				<input type="text" class="input-text" placeholder="请输入资源的标题" id="imageatitle" name="v_title">
			</div>
		</div>
		<div class="row cl">
			<label class="form-label col-2">资源描述</label>
			<div class="formControls col-10">
				<input type="text" class="input-text" placeholder="请输入资源的详细描述" id="imageadescribe" name="v_describe"> 
			</div>
		</div>
        <div class="row cl">
            <label class="form-label col-2">资源限制</label>
           
                <input type="radio"  value="0"  name="limit">普通资源
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio"  value="1"  name="limit">会员资源

        </div>
        <div class="row cl">
            <label class="form-label col-2">来源类型</label>
                <input type="radio"  value="0" id="sellinkvideo" name="v_type" checked>标清链接
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                <input type="radio"  value="1" id="sellinkurl" name="v_type" >上传视频
        </div>
        <div id="video">
            <div class="row cl">
            <label class="form-label col-2">点击上传视频：</label>
            <div class="formControls col-10">
            <div class="uploader-thum-container">
                <input type="file" id="upload" name="v_data">
            </div>
            </div>
            </div>
        </div>
        <div id="link">
            <div class="row cl">
            <label class="form-label col-2">标清视频链接</label>
            <div class="formControls col-10">
                <input type="text" class="input-text" placeholder="请输入视频连接的地址" id="linkurl" name="v_data1">
            </div>
            </div>
        </div>
        <div id="link">
            <div class="row cl">
            <label class="form-label col-2">高清视频链接</label>
            <div class="formControls col-10">
                <input type="text" class="input-text" placeholder="请输入视频连接的地址" id="linkurl" name="v_data2">
            </div>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-2">点击上传视频缩略图</label>
            <div class="formControls col-10">
            <div class="uploader-thum-container">
                <div id="preview_small">
                    <img id="imghead_small" width=100% height=auto border=0 src='/VRAppItem/Public/Backstage/images/no_upload_small.jpg'>
                </div>
                <input type="file" id="upload_small" name="file_small" onchange="previewImage_small(this)" >
            </div>
            </div>
        </div>
        <div class="row cl">
           <label class="form-label col-2">点击上传视频详情图</label>
            <div class="formControls col-10">
            <div class="uploader-thum-container">
                <div id="preview_big">
                    <img id="imghead_big" width=100% height=auto border=0 src='/VRAppItem/Public/Backstage/images/no_upload_big.jpg'>
                </div>
                <input type="file" id="upload_big" name="file_big" onchange="previewImage_big(this)" >
            </div>
            </div>
        </div>
        <div class="row cl">
            <label class="form-label col-2">选择分类</label>
            <div class="formControls col-5"> 
                <select size="1" name="v_cid">
                        <option value="0">--请选择分类--</option>
                    <?php if(is_array($classlist)): foreach($classlist as $key=>$val): ?><option disabled="disabled">--<?php echo ($val["c_title"]); ?>--</option>
                        <?php if(is_array($val['son'])): foreach($val['son'] as $key=>$v): ?><option value="<?php echo ($v["c_id"]); ?>">--|--<?php echo ($v["c_title"]); ?>--</option><?php endforeach; endif; endforeach; endif; ?>
                </select>
            </div>
        </div>
	    <div class="row cl">
            <label class="form-label col-2">选择标签</label>
                <div style="width:900px;margin:0 auto">
                <?php if(is_array($labelKV)): foreach($labelKV as $k=>$val): ?><input type="checkbox" value="<?php echo ($k); ?>" name="lid[]"><?php echo ($val); ?>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php endforeach; endif; ?>
                </div>
        </div>
		
        <!--==================加距离======================-->
       
        <!--=========================================-->
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
    $(function(){ 
        $('#video').hide();
        $('#sellinkurl').click(function(){ 
            $("#video").show();
            $("#link").hide();
        });
        $('#sellinkvideo').click(function(){ 
            $('#video').hide();
            $('#link').show();
        });
    });

     //图片上传预览    IE是用了滤镜。
        function previewImage_small(file)
        {
          var MAXWIDTH  = 260; 
          var MAXHEIGHT = 180;
          var div = document.getElementById('preview_small');
          if (file.files && file.files[0])
          {
              div.innerHTML ='<img id=imghead_small>';
              var img = document.getElementById('imghead_small');
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
            div.innerHTML = '<img id=imghead_small>';
            var img = document.getElementById('imghead_small');
            img.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src = src;
            var rect = clacImgZoomParam(MAXWIDTH, MAXHEIGHT, img.offsetWidth, img.offsetHeight);
            status =('rect:'+rect.top+','+rect.left+','+rect.width+','+rect.height);
            div.innerHTML = "<div id=divhead style='width:"+rect.width+"px;height:"+rect.height+"px;margin-top:"+rect.top+"px;"+sFilter+src+"\"'></div>";
          }
        }

         function previewImage_big(file)
        {
          var MAXWIDTH  = 260; 
          var MAXHEIGHT = 180;
          var div = document.getElementById('preview_big');
          if (file.files && file.files[0])
          {
              div.innerHTML ='<img id=imghead_big>';
              var img = document.getElementById('imghead_big');
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
            div.innerHTML = '<img id=imghead_big>';
            var img = document.getElementById('imghead_big');
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