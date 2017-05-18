<?php
namespace app\admin\validate;

use think\Validate;

class Module extends Validate
{
    protected $rule = [
        'title'  => 'require|unique:module',
        'name' => 'require',
        'actions' => 'require'
    ];

    protected $message = [
        'title.require' => '请输入模块名称',
        'name.require' => '请输入模块标识',
        'actions.require' => '请输入模块动作',
        'title.unique'=>'模块名称重复'
    ];
}