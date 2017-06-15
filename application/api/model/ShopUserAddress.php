<?php
/**
 * WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/6/5
 * Time: 下午2:48
 * For:
 */


namespace app\api\model;
use app\common\exception\ShopCategoryMissException;
use app\common\exception\ShopUserMissException;

class ShopUserAddress extends BaseModel
{
    protected $hidden = ['delete_time','user_id','id'];

}