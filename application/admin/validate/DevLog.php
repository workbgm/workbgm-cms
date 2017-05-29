<?php
namespace app\admin\validate;

use think\Validate;

class DevLog extends Validate
{
    protected $rule = [
        "title|主题" => "require",
    ];
}
