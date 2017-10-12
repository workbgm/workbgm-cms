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

class Scheduling extends BaseModel
{
    public function company()
    {
        return $this->belongsTo("Company","companyid","id");
    }

    public function shop()
    {
        return $this->belongsTo("Shop","shopid","id");
    }

    public function schedulingClerk()
    {
        return $this->hasMany("SchedulingClerk","schedulingid");
    }

    public function setStimeAttr($value){
        return strtotime($value);
    }

    public function setEtimeAttr($value){
        return strtotime($value);
    }


    public function getStimeAttr($value){
        return date('Y-m-d H:i:s', $value);
    }

    public function getEtimeAttr($value){
        return date('Y-m-d H:i:s', $value);
    }
}