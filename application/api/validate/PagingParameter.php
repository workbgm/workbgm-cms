<?php
/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/6/3
 * Time: 下午10:35
 * For:
 */


namespace app\api\validate;


class PagingParameter extends  BaseValidate
{
    protected $rule = [
        'page' => 'isPositiveInteger',
        'size' => 'isPositiveInteger'
    ];

    protected $message = [
        'page' => '分页参数必须是正整数',
        'size' => '分页参数必须是正整数'
    ];

}