<?php
/**
 * Created by PhpStorm.
 * User: wuweiming
 * Date: 2017/9/11
 * Time: 14:21
 */
namespace app\api\controller\v3;
use app\api\ssapmodel\SsapAndroidMetadata;
use app\api\ssapmodel\SsapCityTable;
use app\api\ssapmodel\SsapImgMap;
use app\api\ssapmodel\SsapLocalDicVersion;
use app\api\ssapmodel\SsapOfflinemapCityTable;
use app\api\ssapmodel\SsapSepcParameter;
use app\api\ssapmodel\SsapSepcTestProject;
use app\api\ssapmodel\SsapSpecCategoryParameter;
use app\api\ssapmodel\SsapSpecCirculationCenter;
use app\api\ssapmodel\SsapSpecClassify;
use app\api\ssapmodel\SsapSpecEnterprise;
use app\api\ssapmodel\SsapSpecImgInfo;
use app\api\ssapmodel\SsapSpecLab;
use app\api\ssapmodel\SsapSpecMakeCenter;
use app\api\ssapmodel\SsapSpecNodeInfo;
use app\api\ssapmodel\SsapSpecPollutantCategory;
use app\api\ssapmodel\SsapSpecSpecimenCategory;
use app\api\ssapmodel\SsapSpecSpecimenInfo;
use app\api\ssapmodel\SsapSpecSpecimenLab;
use app\api\ssapmodel\SsapSpecSpecimenParameter;
use app\api\ssapmodel\SsapSpecSubCategory;
use app\api\ssapmodel\SsapSpecSubParameter;
use app\api\ssapmodel\SsapSysRefList;
use app\api\ssapmodel\SsapTblLocalarea;
use app\api\ssapmodel\SsapUserInfos;
use app\api\ssapmodel\SsapHeartbeat;
use app\api\ssapmodel\SsapWorkgroupTrajectoryTable;
use app\api\ssapmodel\SsapSpecNodeInfo as SsapSpecNodeInfoModel;
use app\api\ssapmodel\SsapSpecSpecimenInfo as SsapSpecSpecimenInfoModel;
use think\Controller;
use think\Exception;
use think\Log;

class Ssap extends Controller
{

    private function ssap_table_add($tableName,$tableJson){
        $objModel=null;
        switch (strtolower($tableName))
        {
            case 'android_metadata':
                $objModel = new SsapAndroidMetadata();
                break;
            case 'city_table':
                $objModel = new SsapCityTable();
                break;
            case 'local_dic_version':
                $objModel = new SsapLocalDicVersion();
                break;
            case 'offlinemap_city_table':
                $objModel = new SsapOfflinemapCityTable();
                break;
            case 'sepc_parameter':
                $objModel = new  SsapSepcParameter();
                break;
            case 'sepc_test_project':
                $objModel = new SsapSepcTestProject();
                break;
            case 'spec_category_parameter':
                $objModel = new SsapSpecCategoryParameter();
                break;
            case 'spec_circulation_center':
                $objModel = new SsapSpecCirculationCenter();
                break;
            case 'spec_classify':
                $objModel = new  SsapSpecClassify();
                break;
            case 'spec_enterprise':
                $objModel = new SsapSpecEnterprise();
                break;
            case 'spec_img_info':
                $objModel = new SsapSpecImgInfo();
                break;
            case 'spec_lab':
                $objModel = new SsapSpecLab();
                break;
            case 'spec_make_center':
                $objModel = new SsapSpecMakeCenter();
                break;
            case 'spec_node_info':
                $objModel = new SsapSpecNodeInfo();
                break;
            case 'spec_pollutant_category':
                $objModel = new SsapSpecPollutantCategory();
                break;
            case 'spec_specimen_category':
                $objModel = new SsapSpecSpecimenCategory();
                break;
            case 'spec_specimen_info':
                $objModel = new SsapSpecSpecimenInfo();
                break;
            case 'spec_specimen_lab':
                $objModel = new SsapSpecSpecimenLab();
                break;
            case 'spec_specimen_parameter':
                $objModel = new SsapSpecSpecimenParameter();
                break;
            case 'spec_sub_category':
                $objModel = new SsapSpecSubCategory();
                break;
            case 'spec_sub_parameter':
                $objModel = new SsapSpecSubParameter();
                break;
            case 'sys_ref_list':
                $objModel = new SsapSysRefList();
                break;
            case 'tbl_localarea':
                $objModel = new SsapTblLocalarea();
                break;
            case 'user_infos':
                $objModel = new SsapUserInfos();
                break;
            case 'workgroup_trajectory_table':
                $objModel = new SsapWorkgroupTrajectoryTable();
                break;
        }
        try{
            //$objModel->saveAll($tableJson,false);
            $this->updateAndCreateTableByPK($objModel,$tableJson);
        }catch (Exception $e){
            Log::error($e->getMessage());
        }

    }

    /**
     * 对象 转 数组
     *
     * @param object $obj 对象
     * @return array
     */
    function object_to_array($obj) {
        $obj = (array)$obj;
        foreach ($obj as $k => $v) {
            if (gettype($v) == 'resource') {
                return;
            }
            if (gettype($v) == 'object' || gettype($v) == 'array') {
                $obj[$k] = (array)object_to_array($v);
            }
        }

        return $obj;
    }

    /**
     * 自动更新有主键的表
     * @param $m
     * @param $tableJson
     */
    private  function updateAndCreateTableByPK($m,$dataSet){
        $m->startTrans();
        try {
            $pk = $m->getPk();//主键
            if (is_string($pk)) { //有主键
                foreach ($dataSet as $key => $data) {
                    $data = $this->object_to_array($data);
                    $pk_value = $data[$pk];
                    if (isset($pk_value)) { //是否有主键值
                        $mv = $m->where($pk, $pk_value)->find();
                        if ($mv) {//已经存在
                            $m->update($data);
                        } else {
                            $m->data($data)->save();
                        }
                    }
                }
            } else {//没有主键
                foreach ($dataSet as $data) {
                    $mv = $m->where($data)->find();
                    if ($mv) {//已经存在

                    } else {
                        $m->data($data)->save();
                    }
                }
            }
            $m->commit();
        }catch (\Exception $e) {
            $m->rollback();
            throw $e;
        }

    }

    public function ssap_table(){
        $tableName = input('post.tableName');
        $tableContent=input('post.tableContent');
        $json = json_decode(htmlspecialchars_decode($tableContent));
        $this->ssap_table_add($tableName,$json);
        return 'success';
    }

    public function post_ssap_heartbeat(){
        $IMEI = input('post.IMEI');
        if(!empty($IMEI)){
            $ssapHeartbeat = new SsapHeartbeat();
            $mv = $ssapHeartbeat->where(['imei'=>$IMEI])->find();
            if ($mv) {//已经存在
                $mv->where(['imei'=>$IMEI])->update(['update_time'=>time()]);
            } else {
                $ssapHeartbeat->imei=$IMEI;
                $ssapHeartbeat->save();
            }
        }
        return 'success';
    }

    public function get_ssap_version(){
        $files = glob(APP_UPDATE_PATH."*.apk");
        $version=1;
        foreach ($files as $file){
            $num = (int)str_replace('androidnet.','',basename($file,'.apk'));
            if($num>$version){
                $version=$num;
            }
        }
        return $version;
    }

    public function get_ssap_file(){
        $list=SsapSpecNodeInfoModel::all();
        $files='';
        foreach ($list as $l){
            //获取本体图片
            $img1=$l['IMG_PATH1'];
            $img2=$l['IMG_PATH2'];
            $img3=$l['IMG_PATH3'];
            $img4=$l['IMG_PATH4'];
            $img5=$l['IMG_PATH5'];
            if(!empty($img1)){
                if($this->isAdd($img1)){
                    $files.=$img1.',';
                }
            }
            if(!empty($img2)){
                if($this->isAdd($img2)) {
                    $files .= $img2 . ',';
                }
            }
            if(!empty($img3)){
                if($this->isAdd($img3)) {
                    $files .= $img3 . ',';
                }
            }
            if(!empty($img4)){
                if($this->isAdd($img4)) {
                    $files .= $img4 . ',';
                }
            }
            if(!empty($img5)){
                if($this->isAdd($img5)) {
                    $files .= $img5 . ',';
                }
            }
            //获取分项图片
            $node_id=$l['NODE_ID'];
            $m = new SsapSpecSpecimenInfoModel();
            $datas = $m->where("NODE_ID",$node_id)->select();
            foreach ($datas as $data){
                $img1_=$data['IMG_PATH1'];
                $img2_=$data['IMG_PATH2'];
                $img3_=$data['IMG_PATH3'];
                $img4_=$data['IMG_PATH4'];
                if(!empty($img1_)){
                    if($this->isAdd($img1_)) {
                        $files .= $img1_ . ',';
                    }
                }
                if(!empty($img2_)){
                    if($this->isAdd($img2_)) {
                        $files .= $img2_ . ',';
                    }
                }
                if(!empty($img3_)){
                    if($this->isAdd($img3_)) {
                        $files .= $img3_ . ',';
                    }
                }
                if(!empty($img4_)){
                    if($this->isAdd($img4_)) {
                        $files .= $img4_ . ',';
                    }
                }
                if(!empty($img5_)){
                    if($this->isAdd($img5_)) {
                        $files .= $img5_ . ',';
                    }
                }
            }
        }
        if(!empty($files)){
            $files = substr($files,0,-1);
        }
        return $files;
    }

    private function isAdd($imgPath){
        $m= new SsapImgMap();
        $obj=$m->where("filename",$imgPath)->find();
        if($obj){
            return false;
        }else{
            return true;
        }
    }

}