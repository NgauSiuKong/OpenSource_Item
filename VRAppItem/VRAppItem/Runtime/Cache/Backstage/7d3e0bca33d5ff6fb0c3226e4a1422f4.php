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
<link href="/VRAppItem/Public/Backstage/lib/Hui-iconfont/1.0.7/iconfont.css" rel="stylesheet" type="text/css" />
<title>用户详细信息查看</title>
</head>
<body>
<div class="cl pd-20" style=" background-color:#5bacb6">
  <img class="avatar size-XL l" src="images/user.png">
  <dl style="margin-left:80px; color:#fff">
    <dt><span class="f-18"><?php echo ($user_info['username']); ?></span> <span class="pl-10 f-12">余额：40</span></dt>
    <dd class="pt-10 f-12" style="margin-left:0">这家伙很懒，什么也没有留下</dd>
    <dd style="float:right"><a href="/VRAppItem/index.php/Backstage/User/index"><button class="btn btn-danger radius">点击返回</button></a></dd>
  </dl>
</div>
<div class="pd-20">
  <table class="table">
    <tbody>
      <tr>
        <th class="text-r" width="80">性别：</th>
        <td><?php echo ($user_info['sex']); ?></td>
      </tr>
      <tr>
        <th class="text-r">手机：</th>
        <td><?php echo ($user_info['tel']); ?></td>
      </tr>
      <tr>
        <th class="text-r">邮箱：</th>
        <td><?php echo ($user_info['email']); ?></td>
      </tr>
      <tr>
        <th class="text-r">生日</th>
        <td><?php echo ($user_info['birthday']); ?></td>
      </tr>
      <tr>
        <th class="text-r">注册时间：</th>
        <td><?php echo ($user_info['addtime']); ?></td>
      </tr>
      <tr>
        <th class="text-r">感情状态：</th>
        <td><?php echo ($user_info['emotionalstatus']); ?></td>
      </tr>
    </tbody>
  </table>
</div>
<script type="text/javascript" src="/VRAppItem/Public/Backstage/js/jquery.min.js"></script> 
<script type="text/javascript" src="/VRAppItem/Public/Backstage/js/H-ui.js"></script>
<script type="text/javascript" src="/VRAppItem/Public/Backstage/js/H-ui.admin.js"></script>
</body>
</html>