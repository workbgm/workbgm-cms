<?php

namespace app\api\model;


/**
 * Class BannerItem
 * @package app\api\model
 */

class BannerItem extends BaseModel
{
    protected $hidden = ['delete_time','update_time','id','img_id','banner_id'];
    //
    public function img(){
        //1对1
        return $this->belongsTo('Image','img_id','id');
    }
}
