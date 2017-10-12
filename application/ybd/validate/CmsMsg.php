<?php
namespace app\ybd\validate;

use think\Validate;

class CmsMsg extends Validate
{
    protected $rule = [
        'name'   => 'require',
        'phone' => 'require',
        'solutions'  => 'require',
        'need'  => 'require'
    ];

    protected $message = [
        'name.require'   => '请填写您的姓名',
        'phone.require' => '请填写您的电话',
        'solutions.require'  => '请选择您感兴趣的方案',
        'need.require'   => '请填写您的需求'
    ];
}
