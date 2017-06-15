<?php
/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/6/3
 * Time: 下午8:18
 * For:
 */

namespace app\api\controller\v1;
use app\api\model\ShopCategory as ShopCategoryModel;
use app\common\exception\ShopCategoryMissException;


class ShopCategory
{

    function  getAllShopCategories(){
        $shopCategories=ShopCategoryModel::all([],'img');
        if(!$shopCategories){
            throw  new ShopCategoryMissException();
        }
        return $shopCategories;
    }

}