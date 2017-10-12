<?php
/**
 * Created by PhpStorm.
 * User: wuweiming
 * Date: 2017/9/12
 * Time: 13:27
 */

namespace app\api\ssapmodel;


use think\Model;

class SsapSpecNodeInfo extends Model
{

    public function group()
    {
        return $this->belongsTo("SsapUserInfos","GROUP_ID","GROUP_ID");
    }

    public function gridSize(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","STATIONING_GRID_SIZE","DICTIONARY_OPTION_ID");
    }

    public function siteRegion(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","SITE_REGION","DICTIONARY_OPTION_ID");
    }

    public function specType(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","SPEC_TYPE","DICTIONARY_OPTION_ID");
    }

    public function soilTypeCode(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","SOIL_TYPE_CODE","DICTIONARY_OPTION_ID");
    }

    public function soilTypeCode2(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","SOIL_TYPE_CODE2","DICTIONARY_OPTION_ID");
    }

    public function soilSysCode(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","SOIL_SYS_CODE","DICTIONARY_OPTION_ID");
    }

    public function soilSysCode2(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","SOIL_SYS_CODE2","DICTIONARY_OPTION_ID");
    }

    public function soilSysCode3(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","SOIL_SYS_CODE3","DICTIONARY_OPTION_ID");
    }

    public function soilUseType(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","SOIL_USE_TYPE","DICTIONARY_OPTION_ID");
    }

    public function tillageFashion(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","TILLAGE_FASHION","DICTIONARY_OPTION_ID");
    }

    public function irrigateFashion(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","IRRIGATE_FASHION","DICTIONARY_OPTION_ID");
    }
    //PARENT_ROCK_TYPE
    public function parentRockType(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","PARENT_ROCK_TYPE","DICTIONARY_OPTION_ID");
    }

    //LANDFORM_TYPE
    public function landformType(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","LANDFORM_TYPE","DICTIONARY_OPTION_ID");
    }

    //WEATHER_TYPE_CODE
    public function weatherTypeCode(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","WEATHER_TYPE_CODE","DICTIONARY_OPTION_ID");
    }

    public function imgPath1(){
        return $this->belongsTo("SsapImgMap","IMG_PATH1","filename");
    }

    public function imgPath2(){
        return $this->belongsTo("SsapImgMap","IMG_PATH2","filename");
    }

    public function imgPath3(){
        return $this->belongsTo("SsapImgMap","IMG_PATH3","filename");
    }

    public function imgPath4(){
        return $this->belongsTo("SsapImgMap","IMG_PATH4","filename");
    }

    public function imgPath5(){
        return $this->belongsTo("SsapImgMap","IMG_PATH5","filename");
    }

}