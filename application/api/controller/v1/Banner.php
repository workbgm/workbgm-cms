<?php
/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/6/3
 * Time: 下午8:18
 * For:
 */

namespace app\api\controller\v1;
use app\api\validate\IDMustBePostiveInt;
use app\api\model\Banner as BannerModel;


class Banner
{
    /**
     * 获取Banner
     * @url:/banner/:id
     * @http:GET
     * @param $id
     * @return null|static
     * @throws BannerMissException
     */
    public function getBanner($id){
        (new IDMustBePostiveInt())->goCheck();
        $banner= BannerModel::getBannerByID($id);
        return $banner;
    }
}