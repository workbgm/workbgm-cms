<?php
// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/application/');
//日志目录
define('LOG_PATH', __DIR__ . '/log/');
// 定义入口为admin
define('BIND_MODULE', 'api');
// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';
