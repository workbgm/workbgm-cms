<?php

return [
    // +----------------------------------------------------------------------
    // | 后台模板设置
    // +----------------------------------------------------------------------

    'template' => [
        // 模板路径
        'view_path' => APP_PATH.'admin/view/'
    ],

    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl'  => APP_PATH . 'admin/template/tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl'    => APP_PATH . 'admin/template/tpl' . DS . 'dispatch_jump.tpl',

    // +----------------------------------------------------------------------
    // | 异常及错误设置
    // +----------------------------------------------------------------------

    // 异常页面的模板文件
    'exception_tmpl'         => APP_PATH . 'admin/template/tpl' . DS . 'think_exception.tpl',

    //分页配置
    'paginate'               => [
        'type' => '\\paginator\\driver\\zui',
        'var_page'  => 'page',
        'list_rows' => 15
    ],
];