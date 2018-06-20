<?php
header("Content-type:text/html;charset=UTF-8");
// 定义应用目录
define('APP_PATH','./Apps/');
// 定义运行时目录
//define('RUNTIME_PATH','./Runtime/');
// 开启调试模式
define('APP_DEBUG',True);
define('BIND_MODULE','Home'); //绑定模块
define('BUILD_DIR_SECURE', false);  //不生成目录安全文件
// 更名框架目录名称，并载入框架入口文件
require './ThinkPHP/ThinkPHP.php';