<?php
/**
 *  WORKBGM 开发平台
 * User: 吴渭明
 * Date: 2017/6/9
 * Time: 下午2:28
 * For:
 */


namespace app\api\validate;


class TokenGet extends  BaseValidate
{
    protected $rule=[
        'code' => 'require|isNotEmpty'
    ];

    protected $message=[
        'code'=>'code不能为空'
    ];

}