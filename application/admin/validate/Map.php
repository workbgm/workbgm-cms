<?php
namespace app\admin\validate;

use think\Validate;

class Map extends Validate
{
    protected $rule = [
        'group'  => 'require',
        'name' => 'require',
        'value' => 'require'
    ];

    protected $message = [
        'name.require' => '请输入字典名称',
        'value.require' => '请输入字典值',
        'group.require' => '请输入所属组'
    ];
}