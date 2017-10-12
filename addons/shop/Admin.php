<?php
namespace addons\shop;
use addons\shop\model\Scheduling as SchedulingModel;
use addons\shop\model\SchedulingClerk;
use addons\shop\validate\ClerkDay;
use addons\shop\validate\Scheduling;
use addons\shop\model\Company as CompanyModel;
use addons\shop\model\Shop as ShopModel;
use addons\shop\model\Clerk as ClerkModel;
use addons\shop\model\SchedulingClerk as SchedulingClerkModel;
use addons\shop\model\ClerkDay as ClerkDayModel;
use app\api\validate\IDMustBePostiveInt;
use app\common\controller\AddonsBase;
use app\WUI;
use app\_Data;
use think\Db;
use think\Exception;

/**
 * WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/4/1
 * Time: 16:48
 * For:
 */

/**
 * 后台访问控制类
 * Class Admin
 * @package Addons\Scheduling
 */
class Admin extends  AddonsBase
{

    private function getSchedulingSerach($stime='',$etime=''){
        $companyList=CompanyModel::all();
        return (new WUI())->addPanelBegin('',false,'','with-padding')->addFormBegin(site_url('shop.schedulingSearch'),'form-inline')
            ->addFormGroup('公司',WUI::M_SELECT,"companyid",'','required',
                WUI::$DATA->select($companyList,'name','id'))
            ->addFormGroup('店铺',WUI::M_SELECT,'shopid','','required')
            ->addFormGroup('开始日期',WUI::M_DATETIME,'stime',$stime,'required')
            ->addFormGroup('结束日期',WUI::M_DATETIME,'etime',$etime,'required')
            ->addFormButtons('搜索')
            ->addFormEnd()
            ->addPanelEnd(false)
            ->html();
    }

    public function schedulingSearch(){
        return $this->scheduling(input('companyid'),input('shopid'),input('stime'),input('etime'));
    }

    public function scheduling($companyid='',$shopid='',$stime='',$etime=''){
        $page = input('get.page');
        if (isset($page) && null !== $page) {
        }
        else {
            $page = 1;
        }

        $wMap['isdelete']=0;
        if($companyid!==''){
            $wMap['companyid'] = $companyid;
            $wMap['shopid'] = $shopid;
            $this->assign('companyid',$companyid);
            $this->assign('shopid',$shopid);
        }else{
            $this->assign('companyid',-1);
            $this->assign('shopid',-1);
        }

        if($stime!==''){
            $wMap['stime'] = array('EGT',strtotime($stime));
            $this->assign('stime',$stime);
        }

        if($etime!==''){
            $wMap['etime'] = array('ELT',strtotime($etime));
            $this->assign('etime',$etime);
        }else{
            $wMap['etime'] = array('ELT',intval(time()));
        }

        $m=new SchedulingModel();
        $list   = $m->with(['company','shop','schedulingClerk'=>['clerk']])->where($wMap)->order(['id' => 'ASC'])->paginate(15, false, ['page' => $page]);
        $this->assign('list',$list);
        $this->assign('search',$this->getSchedulingSerach($stime,$etime));
        return $this->fetch("scheduling");
    }

    public function getShopsByCompanyID(){
        $v =(new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new ShopModel();
        return $m->where('isdelete',0)->where('companyid',input('id'))->select();
    }


    public function getClerksByShopID(){
        $v =(new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new ClerkModel();
        return $m->where('isdelete',0)->where('shopid',input('id'))->select();
    }

    public function schedulingAdd(){
        $companyList=CompanyModel::all();
        $ul=array();
        $li1['href']=site_url('shop.scheduling');
        $li1['name']='排班管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='增加新排班';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('wui',(new WUI())->addUL($ul)
            ->addFormBegin(site_url('shop.schedulingSave'))
            ->addFormGroup('公司',WUI::M_SELECT,"companyid",'','required',
                WUI::$DATA->select($companyList,'name','id'))
            ->addFormGroup('店铺',WUI::M_SELECT,'shopid','','required')
            ->addFormGroup('值班人员',WUI::M_MSELECT,'clerkid[]','','required')
            ->addFormGroup("开始时间",WUI::M_DATETIME,'stime','','required')
            ->addFormGroup("介绍时间",WUI::M_DATETIME,'etime','','required')
            ->addFormButtons()
            ->addFormEnd()
            ->html());
        return parent::m_add('schedulingAdd');
    }

    public function schedulingSave(){
        (new Scheduling())->goCheck();
        $data = $this->getFormData();
        $clerkIDs = $data['clerkid'];
        if(count($clerkIDs)==0)  return  $this->error('保存失败');
        unset($data['clerkid']);
        $sm =new  SchedulingModel();
        Db::startTrans();
        try{
            $sm->save($data);
            for($i=0;$i<count($clerkIDs);$i++){
                $d['schedulingid']=$sm->id;
                $d['clerkid']=$clerkIDs[$i];
                $sc = new SchedulingClerk();
                $sc->save($d);
            }
            Db::commit();
        }catch(Exception $e){
            Db::rollback();
            return  $this->error('保存失败'.$e->getMessage());
        }

        return $this->success('保存成功');

    }

    public function schedulingEdit(){
        $v = (new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new SchedulingModel();
        $result = $m->with(['schedulingClerk'=>['clerk']])->find(input('get.id'));
        if(!$result){
            return  $this->error('不存在该排班');
        }
        $clerkids='';
        foreach ($result->schedulingClerk as $sc){
            $clerkids.=$sc->clerk->id.',';
        }
        $clerkids = rtrim($clerkids,',');
        $cm = new ClerkModel();
        $clerkList = $cm->where('isdelete',0)->where('shopid',$result->shopid)->select();
        $cm =new CompanyModel();
        $cmWMap['isdelete']=0;
        $companyList = $cm->where($cmWMap)->select();
        $sm =new ShopModel();
        $smWMap['isdelete']=0;
        $smWMap['companyid']=$result->companyid;
        $shopList = $sm->where($smWMap)->select();
        $ul=array();
        $li1['href']=site_url('shop.scheduling');
        $li1['name']='排班管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='编辑排班';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('wui',(new WUI())->addUL($ul)
            ->addFormBegin(site_url('shop.schedulingUpdate'))
            ->addFormHiddenInfo('id',$result->id)
            ->addFormGroup('公司',WUI::M_SELECT,"companyid",$result->companyid,'required',
                WUI::$DATA->select($companyList,'name','id'))
            ->addFormGroup('店铺',WUI::M_SELECT,'shopid',$result->shopid,'required',
                WUI::$DATA->select($shopList,'name','id'))
            ->addFormGroup('值班人员',WUI::M_MSELECT,'clerkid[]',$clerkids,'required',WUI::$DATA->select($clerkList,'name','id'))
            ->addFormGroup("开始时间",WUI::M_DATETIME,'stime',$result->stime,'required')
            ->addFormGroup("介绍时间",WUI::M_DATETIME,'etime',$result->etime,'required')
            ->addFormButtons()
            ->addFormEnd()
            ->html());
        return parent::m_add('schedulingEdit');
    }

    public function schedulingUpdate(){
        $id=input('id');
        (new Scheduling())->goCheck();
        $data = $this->getFormData();
        $clerkIDs = $data['clerkid'];
        if(count($clerkIDs)==0)  return  $this->error('保存失败');
        unset($data['clerkid']);
        $sm =new  SchedulingModel();
        Db::startTrans();
        try{
            $sm->save($data,$id);
            SchedulingClerkModel::destroy(['schedulingid'=>$id]);
            for($i=0;$i<count($clerkIDs);$i++){
                $d['schedulingid']=$sm->id;
                $d['clerkid']=$clerkIDs[$i];
                $sc = new SchedulingClerk();
                $sc->save($d);
            }
            Db::commit();
        }catch(Exception $e){
            Db::rollback();
            return  $this->error('保存失败'.$e->getMessage());
        }
        return $this->success('保存成功');
    }

    public function schedulingDel(){
        $v =(new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new SchedulingModel();
        Db::startTrans();
        try{
            $id= input('id');
            $m->where('id',$id)->setField('isdelete',1);
            SchedulingClerkModel::destroy(['schedulingid'=>$id]);
            Db::commit();
        }catch(Exception $e){
            Db::rollback();
            return  $this->error('删除失败'.$e->getMessage());
        }
        return $this->success('删除成功');
    }
    
    
    //ClerkDay
    private function getclerkDaySerach($stime='',$etime=''){
        $companyList=CompanyModel::all();
        return (new WUI())->addPanelBegin('',false,'','with-padding')->addFormBegin(site_url('shop.clerkDaySearch'),'form-inline')
            ->addFormGroup('公司',WUI::M_SELECT,"companyid",'','required',
                WUI::$DATA->select($companyList,'name','id'))
            ->addFormGroup('店铺',WUI::M_SELECT,'shopid','','required')
            ->addFormGroup('开始日期',WUI::M_DATETIME,'stime',$stime,'required')
            ->addFormGroup('结束日期',WUI::M_DATETIME,'etime',$etime,'required')
            ->addFormButtons('搜索')
            ->addFormEnd()
            ->addPanelEnd(false)
            ->html();
    }

    public function clerkDaySearch(){
        return $this->scheduling(input('companyid'),input('shopid'),input('stime'),input('etime'));
    }


    public function clerkDayList(){
        $page = input('get.page');
        if (isset($page) && null !== $page) {
        }
        else {
            $page = 1;
        }
        $m=new ClerkDayModel();
        $list   = $m->with(['clerk','scheduling'=>['company','shop']])->where('isdelete',0)->order(['id' => 'ASC'])->paginate(15, false, ['page' => $page]);
        $this->assign('list',$list);
        return $this->fetch("clerkDay");
    }

    public function getClerkByScheduling(){
        $v =(new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $sclm = new SchedulingModel();
        $sch = $sclm->find(input('id'));
        $m = new ClerkModel();
        return $m->where('isdelete',0)->where('shopid',$sch->shopid)->select();
    }

    public function getClerkByScheduling_($id=''){
        $v =(new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $sclm = new SchedulingModel();
        $sch = $sclm->find($id);
        $m = new ClerkModel();
        return $m->where('isdelete',0)->where('shopid',$sch->shopid)->select();
    }

    public function clerkDayAdd(){
        $scm = new SchedulingModel();
        $scList= $scm->with(['company','shop'])->select();
        $ul=array();
        $li1['href']=site_url('shop.clerkDayList');
        $li1['name']='销售日报管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='增加新销售日报';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('wui',(new WUI())->addUL($ul)
            ->addFormBegin(site_url('shop.clerkDaySave'))
            ->addFormGroup('关联排班',WUI::M_SELECT,"schedulingid",'','required',WUI::$DATA->select($scList,'company.name->shop.name->stime->etime','id'))
            ->addFormGroup('上报人',WUI::M_SELECT,'clerkid','','required')
            ->addFormGroup('当日销售目标',WUI::M_NUMBER,'daysales','','required')
            ->addFormGroup('当日实际销售',WUI::M_NUMBER,'actualdaysales','','required')
            ->addFormGroup('当日接待人数',WUI::M_NUMBER,'dayreception','','required')
            ->addFormGroup('当日试穿人数',WUI::M_NUMBER,'daytry','','required')
            ->addFormGroup('当日成交人数',WUI::M_NUMBER,'daytra','','required')
            ->addFormGroup('客单价',WUI::M_NUMBER,'unitprice','','required')
            ->addFormGroup('当日洗护人数',WUI::M_NUMBER,'daycare','','required')
            ->addFormGroup('当日VIP办卡数',WUI::M_NUMBER,'dayvip','','required')
            ->addFormGroup('当日微信关注',WUI::M_NUMBER,'daywechat','','required')
            ->addFormGroup('当日实际微信关注',WUI::M_NUMBER,'actualdaywechat','','required')
            ->addFormGroup('工作总结',WUI::M_TEXTAREA,'daysummary')
            ->addFormGroup('未成交原因',WUI::M_TEXTAREA,'dayrnc')
            ->addFormGroup('建议',WUI::M_TEXTAREA,'daypps')
            ->addFormButtons()
            ->addFormEnd()
            ->html());
        return parent::m_add('clerkDayAdd');
    }

    public function clerkDaySave(){
        (new ClerkDay())->goCheck();
        $m =new  ClerkDayModel();
        $data = $this->getFormData();
        if($m->save($data)){
            return $this->success('保存成功');
        }else{
            return  $this->error('保存失败');
        }
    }

    public function clerkDayEdit(){
        $v = (new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new ClerkDayModel();
        $result = $m->find(input('get.id'));
        if(!$result){
            return  $this->error('不存在该销售日报');
        }
        $scm = new SchedulingModel();
        $scList= $scm->with(['company','shop'])->select();
        $ul=array();
        $li1['href']=site_url('shop.clerkDayList');
        $li1['name']='销售日报管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='编辑销售日报';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('wui',(new WUI())->addUL($ul)
            ->addFormBegin(site_url('shop.clerkDayUpdate'))
            ->addFormHiddenInfo('id',$result->id)
            ->addFormGroup('关联排班',WUI::M_SELECT,"schedulingid",$result->schedulingid,'required',WUI::$DATA->select($scList,'company.name->shop.name->stime->etime','id'))
            ->addFormGroup('上报人',WUI::M_SELECT,'clerkid',$result->clerkid,'required',WUI::$DATA->select($this->getClerkByScheduling_($result->schedulingid),'name','id'))
            ->addFormGroup('当日销售目标',WUI::M_NUMBER,'daysales',$result->daysales,'required')
            ->addFormGroup('当日实际销售',WUI::M_NUMBER,'actualdaysales',$result->actualdaysales,'required')
            ->addFormGroup('当日接待人数',WUI::M_NUMBER,'dayreception',$result->dayreception,'required')
            ->addFormGroup('当日试穿人数',WUI::M_NUMBER,'daytry',$result->daytry,'required')
            ->addFormGroup('当日成交人数',WUI::M_NUMBER,'daytra',$result->daytra,'required')
            ->addFormGroup('客单价',WUI::M_NUMBER,'unitprice',$result->unitprice,'required')
            ->addFormGroup('当日洗护人数',WUI::M_NUMBER,'daycare',$result->daycare,'required')
            ->addFormGroup('当日VIP办卡数',WUI::M_NUMBER,'dayvip',$result->dayvip,'required')
            ->addFormGroup('当日微信关注',WUI::M_NUMBER,'daywechat',$result->daywechat,'required')
            ->addFormGroup('当日实际微信关注',WUI::M_NUMBER,'actualdaywechat',$result->actualdaywechat,'required')
            ->addFormGroup('工作总结',WUI::M_TEXTAREA,'daysummary',$result->daysummary)
            ->addFormGroup('未成交原因',WUI::M_TEXTAREA,'dayrnc',$result->dayrnc)
            ->addFormGroup('建议',WUI::M_TEXTAREA,'daypps',$result->daypps)
            ->addFormButtons()
            ->addFormEnd()
            ->html());
        return parent::m_add('clerkDayEdit');
    }

    public function clerkDayUpdate(){
        $v =(new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $id=input('id');
        $data  = $this->getFormData();
        $m = new ClerkDayModel();
        if($m->save($data,$id)){
            return $this->success('保存成功');
        }else{
            return  $this->error('保存失败');
        }
    }

    public function clerkDayDel(){
        $v =(new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new ClerkDayModel();
        if($m->where('id',input('id'))->setField('isdelete',1)){
            return $this->success('删除成功');
        }else{
            return  $this->error('删除失败');
        }
    }

}