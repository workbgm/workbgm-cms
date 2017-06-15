<?php
/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/6/3
 * Time: 下午8:18
 * For:
 */

namespace app\api\controller\v1;
use app\api\validate\Count;
use app\api\model\Product as ProductModel;
use app\api\validate\IDMustBePostiveInt;
use app\common\exception\ProductMissException;


class Product
{
    /**
     * 获取最新产品
     * @param int $count
     * @return $this|false|\PDOStatement|string|\think\Collection
     */
    public  function  getRecent($count=15){
        (new Count())->goCheck();
        $products= ProductModel::getMostRecent($count);
        $collection= collection($products);
        $products=$collection->hidden(['summary']);
        return $products;
    }

    /**
     * 获取某个分类下得所有产品
     * @param $id
     * @return $this|false|\PDOStatement|string|\think\Collection
     */
    public  function getAllInCategory($id){
        (new IDMustBePostiveInt())->goCheck();
        $products= ProductModel::getProductsByCategoryID($id);
        $collection= collection($products);
        $products=$collection->hidden(['summary']);
        return $products;
    }

    public function getOne($id){
        (new IDMustBePostiveInt())->goCheck();
        $product = ProductModel::getProductDetail($id);
        return $product;
    }
}