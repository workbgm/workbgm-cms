<?php

namespace app\api\model;


use app\common\exception\ThemeMissException;
use app\common\exception\ProductMissException;

class Theme extends BaseModel
{
    protected $hidden = ['delete_time','update_time','head_img_id','topic_img_id'];

    //
    public function topicImg(){
        return $this->belongsTo('Image','topic_img_id','id');
    }

    public function headImg(){
        return $this->belongsTo("Image",'head_img_id','id');
    }

    public  function products(){
        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }

    /**
     * 通过theme id获取相关产品信息
     * @param $id themeid
     * @return array|false|\PDOStatement|string|\think\Model
     * @throws ThemeMissException
     */
    public static function  getThemeWidthProducts($id){
        $theme = self::with('products,headImg,topicImg')->find($id);
        if(!$theme){
            throw  new ProductMissException();
        }
        return $theme;
    }

    /**
     * 通过theme id数组获取themes信息
     * @param $ids
     * @return false|\PDOStatement|string|\think\Collection
     * @throws ThemeMissException
     */
    public  static  function  getThemeList($ids){
        $themes = self::with('headImg,topicImg')->select($ids);
        if(!$themes){
            throw  new ThemeMissException();
        }
        return $themes;
    }
}
