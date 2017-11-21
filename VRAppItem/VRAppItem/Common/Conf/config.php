<?php
return array(
    'DB_TYPE'               =>  'mysql',     // 数据库类型
    'DB_HOST'               =>  'localhost', // 服务器地址
    'DB_NAME'               =>  'vr_app',          // 数据库名
    'DB_USER'               =>  'admin',      // 用户名
    'DB_PWD'                =>  'admin',          // 密码
    'DB_PREFIX'             =>  'vr_',    // 数据库表前缀
    'DEFAULT_CHARSET'            =>  'utf8',
    //删除图片的时候,图片的绝对路径前缀
     DEFINE('PATH_CARFIG_PREFIX',"D:/Install/wamp/www/VRAppItem/Public/"),
     DEFINE('PATH_VIDEO_RELATIVE',"Uploads/Video/"),
     DEFINE('PATH_IMG','http://139.199.181.78/VRAppItem/Public/')
);
