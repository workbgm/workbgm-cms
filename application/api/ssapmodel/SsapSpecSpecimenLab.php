<?php
/**
 * Created by PhpStorm.
 * User: wuweiming
 * Date: 2017/9/12
 * Time: 13:27
 */

namespace app\api\ssapmodel;


use think\Model;

class SsapSpecSpecimenLab extends Model
{
    public function package(){
        return $this->belongsTo("SsapSpecPackage","PACKAGE_ID","PACKAGE_ID");
    }
}