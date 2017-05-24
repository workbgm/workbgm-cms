<?php
// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/application/');
define('PLUGIN_PATH', __DIR__ . '/plugins/');
// 定义入口为index
define('BIND_MODULE', 'index');
// 加载框架引导文件
require __DIR__ . '/thinkphp/start.php';
