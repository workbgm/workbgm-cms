<?php
/**
 *  M-PHP开发框架
 * User: 吴渭明
 * Date: 2017/6/3
 * Time: 下午8:18
 * For:
 */


namespace app\api\controller\v1;
use app\api\validate\IDMustBePostiveInt;
use app\api\model\Banner as BannerModel;
use app\common\exception\BannerMissException;
use think\Exception;

class Banner
{
    /**
     * 获取Banner
     * @param $id
     * url:/banner/:id
     * http:GET
     */
    public function getBanner($id){
        (new IDMustBePostiveInt())->goCheck();
        $banner = BannerModel::getBannerByID($id);
        if(!$banner){
            throw new Exception('内部错误');
            throw new BannerMissException();
        }
        return $banner;
    }
}