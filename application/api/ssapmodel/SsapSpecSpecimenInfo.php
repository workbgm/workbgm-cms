<?php
/**
 * Created by PhpStorm.
 * User: wuweiming
 * Date: 2017/9/12
 * Time: 13:27
 */

namespace app\api\ssapmodel;


use think\Model;

class SsapSpecSpecimenInfo extends Model
{
//FARM_SAMPLE_TYPE
    public function farmSampleType(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","FARM_SAMPLE_TYPE","DICTIONARY_OPTION_ID");
    }
    //FARM_PRODUCE_NAME
    public function farmProduceName(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","FARM_PRODUCE_NAME","DICTIONARY_OPTION_ID");
    }
    //SAMPLING_SITE
    public function samplingSite(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","SAMPLING_SITE","DICTIONARY_OPTION_ID");
    }

    //IS_QUALITY_CONTROL
    public function isQualityControl(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","IS_QUALITY_CONTROL","DICTIONARY_OPTION_ID");
    }

    //CROP_SEASON
    public function cropSeason(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","CROP_SEASON","DICTIONARY_OPTION_ID");
    }

    //FERTILIZATION_CONDITION
    public function fertilizationCondition(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","FERTILIZATION_CONDITION","DICTIONARY_OPTION_ID");
    }

    //PESTICIDE_CONDITION
    public function pesticideCondition(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","PESTICIDE_CONDITION","DICTIONARY_OPTION_ID");
    }

    //SOIL_TYPE_CODE
    public function soilTypeCode(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","SOIL_TYPE_CODE","DICTIONARY_OPTION_ID");
    }

    //SOIL_COLOUR
    public function soilColour(){
        //SsapSysRefList2
        return $this->belongsTo("SsapSysRefList2","SOIL_COLOUR","DICTIONARY_OPTION_ID");
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
}