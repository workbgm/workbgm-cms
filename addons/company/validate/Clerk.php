<?php
/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/6/3
 * Time: 下午9:23
 * For:
 */


namespace addons\company\validate;


use app\api\validate\BaseValidate;

class Clerk extends BaseValidate
{
    protected  $rule=[
        'name'=>'require',
        'sex'=>'require',
        'birthday'=>'require',
        'companyid'=>'require',
        'shopid'=>'require',
        'phone'=>'require',
    ];
}