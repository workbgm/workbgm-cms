<?php
/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/6/3
 * Time: 下午9:23
 * For:
 */


namespace app\api\validate;
use think\Validate;


class TestValidate extends Validate
{
    protected  $rule=[
        'name'=>'require|max:10',
            'email'=>'email'
    ];
}