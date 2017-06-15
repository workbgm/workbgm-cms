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

class ShopUser extends BaseModel
{
    protected $hidden = ['delete_time','update_time'];

    public static function getByOpenID($openid){
        $user = self::where('openid','=',$openid)->find();
//        if(!$user){
//            throw new ShopUserMissException();
//        }
        return $user;
    }

    public function address(){
        return $this->hasOne('ShopUserAddress','user_id','id');
    }

}