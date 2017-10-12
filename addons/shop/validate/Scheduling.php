<?php
/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/6/3
 * Time: 下午9:23
 * For:
 */


namespace addons\shop\validate;


use app\api\validate\BaseValidate;

class Scheduling extends BaseValidate
{
    protected  $rule=[
        'stime'=>'require',
        'etime'=>'require',
        'companyid'=>'require',
        'shopid'=>'require',
    ];
}