<?php

namespace app\api\model;

use app\common\exception\ProductMissException;


class Product extends BaseModel
{
    //
    protected $hidden=[
        'pivot','delete_time','update_time','create_time','from','category_id'
    ];


    public static function getMostRecent($count){
        $products = self::with(['image'])->limit($count)->order('create_time desc')
            ->select();
        if(!$products){
            throw new ProductMissException();
        }
        return $products;
    }

    public static function getProductsByCategoryID($categoryID){
        $products=self::with(['image'])->where('category_id','=',$categoryID)->select();
        if(!$products){
            throw new ProductMissException();
        }
        return $products;
    }

    public function imgs(){
        return $this->hasMany('ProductImage','product_id','id');
    }

    public function image(){
        return $this->belongsTo('Image','img_id','id');
    }

    public function shopCategory(){
        return $this->belongsTo('ShopCategory','category_id','id');
    }

    public function properties(){
        return $this->hasMany('ProductProperty','product_id','id');
    }

    public static function getProductDetail($id){
//        $product = self::with([
//            'imgs'=>function($query){
//                $query->with(['imgUrl'])
//                    ->order('order','asc');
//            }
//        ,'image'])
//            ->with(['properties'])->find($id);
        $product = self::with(['image'])
            ->with(['properties'])->find($id);
        if(!$product){
            throw new ProductMissException();
        }
        return $product;
    }
}
