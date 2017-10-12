<?php
return [
    // +----------------------------------------------------------------------
    // | 模板设置
    // +----------------------------------------------------------------------
    // 应用调试模式
    'app_debug'              => true,
    // 应用Trace
    'app_trace'              => true,

    'template' => [
        // 模板路径
        'view_path' => APP_PATH.'ybd/view/'
    ],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => APP_PATH . 'ybd/template/tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => APP_PATH . 'ybd/template/tpl' . DS . 'dispatch_jump.tpl',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件
    'exception_tmpl'         => APP_PATH . 'ybd/template/tpl' . DS . 'think_exception.tpl',
    'resources_path' => 'ybd'
];