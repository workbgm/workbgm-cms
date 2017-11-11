<?php
namespace app\admin\validate;

use think\Validate;

class GenApp extends Validate
{
    protected $rule = [
        "name|应用名称" => "require",
        "domain|二级域名" => "require",
    ];
}
