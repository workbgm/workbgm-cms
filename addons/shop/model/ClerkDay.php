<?php
/**
 *  WORKBGM 开发平台
 * User: 吴渭明
 * Date: 2017/7/11
 * Time: 9:50
 * For:
 */


namespace addons\shop\model;


use app\api\model\BaseModel;

class ClerkDay extends BaseModel
{
    public function company(){
        return $this->belongsTo("Company","companyid","id");
    }

    public function shop(){
        return $this->belongsTo("Shop","shopid","id");
    }

    public function clerk(){
        return $this->belongsTo("Clerk","clerkid","id");
    }

    public function scheduling(){
        return $this->belongsTo("Scheduling","schedulingid","id");
    }
}