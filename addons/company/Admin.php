<?php
namespace addons\company;
use addons\company\model\Company as CompanyModel;
use addons\company\validate\Company;
use addons\company\model\Shop as ShopModel;
use addons\company\model\Stock as StockModel;
use addons\company\validate\Shop;
use addons\company\model\Clerk as ClerkModel;
use addons\company\validate\Clerk;
use addons\company\validate\Stock;
use app\api\validate\IDMustBePostiveInt;
use app\common\controller\AddonsBase;
use app\WUI;

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
 * @package Addons\Company
 */
class Admin extends  AddonsBase
{

    public function companyList(){
        $page = input('get.page');
        if (isset($page) && null !== $page) {
        }
        else {
            $page = 1;
        }
        $m=new CompanyModel();
        $list   = $m->where('isdelete',0)->order(['id' => 'ASC'])->paginate(15, false, ['page' => $page]);
        $this->assign('list',$list);
        return $this->fetch("company");
    }

    public function companyAdd(){
        $ul=array();
        $li1['href']=site_url('Company.companyList');
        $li1['name']='公司信息管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='增加新公司信息';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('wui',(new WUI())->addUL($ul)
            ->addFormBegin(site_url('Company.companySave'))
            ->addFormGroup('公司名称','input',"name",'','required')
            ->addFormButtons()
            ->addFormEnd()
            ->html());
        return parent::m_add('companyAdd');
    }

    public function companySave(){
        (new Company())->goCheck();
        $m =new  CompanyModel();
        $m->name = input('post.name');
        if($m->save()){
            return $this->success('保存成功');
        }else{
            return  $this->error('保存失败');
        }
    }

    public function companyEdit(){
        $v = (new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new CompanyModel();
        $result = $m->find(input('get.id'));
        if(!$result){
            return  $this->error('不存在该公司信息');
        }
        $ul=array();
        $li1['href']=site_url('Company.companyList');
        $li1['name']='公司信息管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='编辑公司信息';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('wui',(new WUI())->addUL($ul)
            ->addFormBegin(site_url('Company.companyUpdate'))
            ->addFormHiddenInfo('id',$result->id)
            ->addFormGroup('公司名称','input',"name",$result->name,'required')
            ->addFormButtons()
            ->addFormEnd()
            ->html());
        return parent::m_add('companyEdit');
    }

    public function companyUpdate(){
        $id=input('id');
        $data  = $this->request->post();
        $m = new CompanyModel();
        if($m->save($data,$id)){
            return $this->success('保存成功');
        }else{
            return  $this->error('保存失败');
        }
    }

    public function companyDel(){
        $v =(new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new CompanyModel();
        if($m->where('id',input('id'))->setField('isdelete',1)){
            return $this->success('删除成功');
        }else{
            return  $this->error('删除失败');
        }
    }

   //shop start
    public function shopList(){
        $page = input('get.page');
        if (isset($page) && null !== $page) {
        }
        else {
            $page = 1;
        }
        $m=new ShopModel();
        $list   = $m->with('company')->where('isdelete',0)->order(['id' => 'ASC'])->paginate(15, false, ['page' => $page]);
        $this->assign('list',$list);
        return $this->fetch("shop");
    }

    public function shopAdd(){
        $list=CompanyModel::all();
        $ul=array();
        $li1['href']=site_url('company.shopList');
        $li1['name']='店铺信息管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='增加新店铺信息';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('wui',(new WUI())->addUL($ul)
            ->addFormBegin(site_url('company.shopSave'))
            ->addFormGroup('店铺名称','input',"name",'','required')
            ->addFormGroup('所属公司','select',"companyid",'','required',toSelectOptions($list,'name','id'))
            ->addFormButtons()
            ->addFormEnd()
            ->html());
        return parent::m_add('shopAdd');
    }

    public function shopSave(){
        (new shop())->goCheck();
        $m =new  ShopModel();
        $m->name = input('post.name');
        $m->companyid = input('post.companyid');
        if($m->save()){
            return $this->success('保存成功');
        }else{
            return  $this->error('保存失败');
        }
    }

    public function shopEdit(){
        $list=CompanyModel::all();
        $v = (new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new ShopModel();
        $result = $m->find(input('get.id'));
        if(!$result){
            return  $this->error('不存在该店铺信息');
        }
        $ul=array();
        $li1['href']=site_url('company.shopList');
        $li1['name']='店铺信息管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='编辑店铺信息';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('wui',(new WUI())->addUL($ul)
            ->addFormBegin(site_url('company.shopUpdate'))
            ->addFormHiddenInfo('id',$result->id)
            ->addFormGroup('店铺名称','input',"name",$result->name,'required')
            ->addFormGroup('所属公司','select',"companyid",$result->companyid,'required',toSelectOptions($list,'name','id'))
            ->addFormButtons()
            ->addFormEnd()
            ->html());
        return parent::m_add('shopEdit');
    }

    public function shopUpdate(){
        $id=input('id');
        $data  = $this->request->post();
        $m = new ShopModel();
        if($m->save($data,$id)){
            return $this->success('保存成功');
        }else{
            return  $this->error('保存失败');
        }
    }

    public function shopDel(){
        $v =(new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new ShopModel();
        if($m->where('id',input('id'))->setField('isdelete',1)){
            return $this->success('删除成功');
        }else{
            return  $this->error('删除失败');
        }
    }

    //stock start
    public function stockList(){
        $page = input('get.page');
        if (isset($page) && null !== $page) {
        }
        else {
            $page = 1;
        }
        $m=new StockModel();
        $list   = $m->with('company')->where('isdelete',0)->order(['id' => 'ASC'])->paginate(15, false, ['page' => $page]);
        $this->assign('list',$list);
        return $this->fetch("stock");
    }

    public function stockAdd(){
        $list=CompanyModel::all();
        $ul=array();
        $li1['href']=site_url('company.stockList');
        $li1['name']='仓库信息管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='增加新仓库信息';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('wui',(new WUI())->addUL($ul)
            ->addFormBegin(site_url('company.stockSave'))
            ->addFormGroup('仓库名称','input',"name",'','required')
            ->addFormGroup('所属公司','select',"companyid",'','required',toSelectOptions($list,'name','id'))
            ->addFormButtons()
            ->addFormEnd()
            ->html());
        return parent::m_add('stockAdd');
    }

    public function stockSave(){
        (new stock())->goCheck();
        $m =new  StockModel();
        $m->name = input('post.name');
        $m->companyid = input('post.companyid');
        if($m->save()){
            return $this->success('保存成功');
        }else{
            return  $this->error('保存失败');
        }
    }

    public function stockEdit(){
        $list=CompanyModel::all();
        $v = (new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new StockModel();
        $result = $m->find(input('get.id'));
        if(!$result){
            return  $this->error('不存在该仓库信息');
        }
        $ul=array();
        $li1['href']=site_url('company.stockList');
        $li1['name']='仓库信息管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='编辑仓库信息';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('wui',(new WUI())->addUL($ul)
            ->addFormBegin(site_url('company.stockUpdate'))
            ->addFormHiddenInfo('id',$result->id)
            ->addFormGroup('仓库名称','input',"name",$result->name,'required')
            ->addFormGroup('所属公司','select',"companyid",$result->companyid,'required',toSelectOptions($list,'name','id'))
            ->addFormButtons()
            ->addFormEnd()
            ->html());
        return parent::m_add('stockEdit');
    }

    public function stockUpdate(){
        $id=input('id');
        $data  = $this->request->post();
        $m = new StockModel();
        if($m->save($data,$id)){
            return $this->success('保存成功');
        }else{
            return  $this->error('保存失败');
        }
    }

    public function stockDel(){
        $v =(new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new StockModel();
        if($m->where('id',input('id'))->setField('isdelete',1)){
            return $this->success('删除成功');
        }else{
            return  $this->error('删除失败');
        }
    }

    //clerk start
    public function clerkList(){
        $page = input('get.page');
        if (isset($page) && null !== $page) {
        }
        else {
            $page = 1;
        }
        $m=new ClerkModel();
        $list   = $m->with(['company','shop'])->where('isdelete',0)->order(['id' => 'ASC'])->paginate(15, false, ['page' => $page]);
        $this->assign('list',$list);
        return $this->fetch("clerk");
    }

    public function getShopsByCompanyID(){
        $v =(new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new ShopModel();
        return $m->where('isdelete',0)->where('companyid',input('id'))->select();
    }

    public function clerkAdd(){
        $list=CompanyModel::all();
        $ul=array();
        $li1['href']=site_url('company.clerkList');
        $li1['name']='人员信息管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='增加新人员信息';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('wui',(new WUI())->addUL($ul)
            ->addFormBegin(site_url('company.clerkSave'))
            ->addFormGroup('人员名称','input',"name",'','required')
            ->addFormGroup('所属公司','select',"companyid",'','required',toSelectOptions($list,'name','id'))
            ->addFormGroup('所属店铺',WUI::M_SELECT,'shopid','','required')
            ->addFormGroup('性别',WUI::M_SELECT,'sex','','required',map('sex'))
            ->addFormGroup('照片(寸照)',WUI::M_IMAGE,'photo','','')
            ->addFormGroup('生日',WUI::M_DATE,'birthday','','','','请选择出生年月')
            ->addFormGroup('手机',WUI::M_INPUT,'phone','','required')
            ->addFormGroup('住址',WUI::M_INPUT,'address')
            ->addFormGroup('身份证正面',WUI::M_IMAGE,'ids','','')
            ->addFormGroup('身份证背面',WUI::M_IMAGE,'ide','','')
            ->addFormGroup('合同电子版(只允许PDF格式)',WUI::M_IMAGE,'contract')
            ->addFormButtons()
            ->addFormEnd()
            ->html());
        return parent::m_add('clerkAdd');
    }

    public function clerkSave(){
        (new clerk())->goCheck();
        $m =new  ClerkModel();
        if($m->save($this->getFormData())){
            return $this->success('保存成功');
        }else{
            return  $this->error('保存失败');
        }
    }

    public function clerkEdit(){
        $list=CompanyModel::all();
        $v = (new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new ClerkModel();
        $result = $m->find(input('get.id'));
        if(!$result){
            return  $this->error('不存在该人员信息');
        }
        $ul=array();
        $li1['href']=site_url('company.clerkList');
        $li1['name']='人员信息管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='编辑人员信息';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('wui',(new WUI())->addUL($ul)
            ->addFormBegin(site_url('company.clerkUpdate'))
            ->addFormHiddenInfo('id',$result->id)
            ->addFormGroup('人员名称','input',"name",$result->name,'required')
            ->addFormGroup('所属公司','select',"companyid",$result->companyid,'required',toSelectOptions($list,'name','id'))
            ->addFormGroup('所属店铺',WUI::M_SELECT,'shopid',$result->shopid,'required')
            ->addFormGroup('性别',WUI::M_SELECT,'sex',$result->sex,'required',map('sex'))
            ->addFormGroup('照片(寸照)',WUI::M_IMAGE,'photo',$result->photo,'')
            ->addFormGroup('生日',WUI::M_DATE,'birthday',$result->birthday,'','','请选择出生年月')
            ->addFormGroup('手机',WUI::M_INPUT,'phone',$result->phone,'required')
            ->addFormGroup('住址',WUI::M_INPUT,'address',$result->address)
            ->addFormGroup('身份证正面',WUI::M_IMAGE,'ids',$result->ids,'')
            ->addFormGroup('身份证背面',WUI::M_IMAGE,'ide',$result->ide,'')
            ->addFormGroup('合同电子版(只允许PDF格式)',WUI::M_IMAGE,'contract',$result->contract)
            ->addFormButtons()
            ->addFormEnd()
            ->html());
        return parent::m_add('clerkEdit');
    }

    public function clerkUpdate(){
        $id=input('id');
        $m = new ClerkModel();
        if($m->save($this->getFormData(),$id)){
            return $this->success('保存成功');
        }else{
            return  $this->error('保存失败');
        }
    }

    public function clerkDel(){
        $v =(new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new ClerkModel();
        if($m->where('id',input('id'))->setField('isdelete',1)){
            return $this->success('删除成功');
        }else{
            return  $this->error('删除失败');
        }
    }

}