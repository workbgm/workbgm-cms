<?php
/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/6/3
 * Time: 下午10:35
 * For:
 */


namespace app\api\validate;


class IDMustBePostiveInt extends  BaseValidate
{
    protected $rule=[
      'id'=>'require|isPositiveInteger'
    ];

    protected $message=[
        'id'=>'id必须是正整数'
    ];

}