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

class Shop extends BaseModel
{
    public function company(){
        return $this->belongsTo("Company","companyid","id");
    }
}