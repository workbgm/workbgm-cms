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

class ClerkDay extends BaseValidate
{
    protected  $rule=[
        'clerkid'=>'require',
        'schedulingid'=>'require',
        'daysales'=>'require',
        'actualdaysales'=>'require',

        'dayreception'=>'require',
        'daytry'=>'require',

        'daytra'=>'require',
        'unitprice'=>'require',
        'daycare'=>'require',

        'dayvip'=>'require',
        'daywechat'=>'require',
        'actualdaywechat'=>'require',
    ];
}