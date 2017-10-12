<?php
/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/6/3
 * Time: 下午9:23
 * For:
 */


namespace addons\apidoc\validate;


use app\api\validate\BaseValidate;

class Apidoc extends BaseValidate
{
    protected  $rule=[
        'title'=>'require',
        'action'=>'require',
    ];
}