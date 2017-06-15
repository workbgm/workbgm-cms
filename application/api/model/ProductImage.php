<?php
/**
 *  WORKBGM 开发平台
 * User: 吴渭明
 * Date: 2017/6/14
 * Time: 下午9:52
 * For:
 */


namespace app\api\model;


class ProductImage extends BaseModel
{
    protected $hidden=[
        'img_id','delete_time','product_id'
    ];

    public function imgUrl(){
        return $this->belongsTo("Image","img_id","id");
    }

}