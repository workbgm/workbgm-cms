<?php
/**
 *  WORKBGM 开发平台
 * User: 吴渭明
 * Date: 2017/6/9
 * Time: 下午2:28
 * For:
 */


namespace app\api\validate;


class Count extends  BaseValidate
{
    protected $rule=[
        'count' => 'isPositiveInteger|between:1,15'
    ];

    protected $message=[
        'count'=>'count必须是1-15之间的正整数'
    ];

}