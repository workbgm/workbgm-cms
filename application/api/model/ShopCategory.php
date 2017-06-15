<?php
/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/6/5
 * Time: 下午2:48
 * For:
 */


namespace app\api\model;

class ShopCategory extends BaseModel
{
    protected $hidden = ['delete_time','update_time'];

    public function img(){
        return $this->belongsTo('Image','topic_img_id','id');
    }

}