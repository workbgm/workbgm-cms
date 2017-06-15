<?php
/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/6/5
 * Time: 下午2:48
 * For:
 */


namespace app\api\model;
use app\common\exception\ShopCategoryMissException;

class Banner extends BaseModel
{
    protected $hidden = ['delete_time','update_time'];
    /**
     * 通过ID获取banner
     * @param $id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws ShopCategoryMissException
     */
    public static  function  getBannerByID($id){
        $banner= self::with(['bannerItems','bannerItems.img'])->find($id);
        if(!$banner){
            throw new ShopCategoryMissException();
        }
        return $banner;
    }

    /**
     *
     * @return \think\model\relation\HasMany
     */
    public function bannerItems(){
        return $this->hasMany('BannerItem','banner_id','id');
    }
}