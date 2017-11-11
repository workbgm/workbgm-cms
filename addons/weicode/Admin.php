<?php
namespace addons\weicode;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\Image;
use app\api\validate\Product;
use app\api\validate\ProductProperty;
use app\api\validate\ShopCategory;
use app\api\validate\Theme;
use app\common\controller\AddonsBase;
use app\api\model\ShopCategory as ShopCategoryModel;
use app\api\model\Theme  as ThemeModel;
use app\api\model\Image as ImageModel;
use app\api\model\Product as ProductModel;
use app\api\model\ProductProperty as ProductPropertyModel;
use app\api\model\Order  as OrderModel;
use app\api\service\Order as OrderService;
use app\WUI;
use think\Db;
use think\Exception;

/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/4/1
 * Time: 16:48
 * For:
 */

/**
 * 后台访问控制类
 * Class Admin
 * @package Addons\weicode
 */
class Admin extends  AddonsBase
{

    //分类管理  s
    public function category(){
        $page = input('get.page');
        if (isset($page) && null !== $page) {
        }
        else {
            $page = 1;
        }
        $m=new ShopCategoryModel();
        $list   = $m->order(['id' => 'ASC'])->paginate(15, false, ['page' => $page]);
        $this->assign('list',$list);
        return $this->fetch("category");
    }

    public function categoryAdd(){
        $ul=array();
        $li1['href']=site_url('weicode.category');
        $li1['name']='分类管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='增加新分类';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('wui',(new WUI())->addUL($ul)
            ->addFormBegin(site_url('weicode.categorySave'))
            ->addFormGroup('分类名','input',"name",'','required')
            ->addFormGroup('备注','input',"description",'','')
            ->addFormGroup('缩略图','imageSelect','topic_img_id')
            ->addFormButtons()
            ->addFormEnd()
            ->html());
        return parent::m_add('categoryAdd');
    }

    public function categorySave(){
        (new ShopCategory())->goCheck();
        $m =new  ShopCategoryModel();
        $m->name = input('post.name');
        $m->description = input('post.description');
        $m->topic_img_id = input('post.topic_img_id');
        if($m->save()){
            return $this->success('保存成功');
        }else{
           return  $this->error('保存失败');
        }
    }

    public function categoryEdit(){
        $v = (new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new ShopCategoryModel();
        $result = $m->find(input('get.id'));
        if(!$result){
            return  $this->error('不存在该分类');
        }
        $ul=array();
        $li1['href']=site_url('weicode.category');
        $li1['name']='分类管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='编辑分类';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('wui',(new WUI())->addUL($ul)
            ->addFormBegin(site_url('weicode.categoryUpdate'))
            ->addFormHiddenInfo('id',$result->id)
            ->addFormGroup('分类名','input',"name",$result->name,'required')
            ->addFormGroup('备注','input',"description",$result->description,'')
            ->addFormGroup('缩略图','imageSelect','topic_img_id',$result->topic_img_id)
            ->addFormButtons()
            ->addFormEnd()
            ->html());
        return parent::m_add('categoryEdit');
    }

    public function categoryUpdate(){
        $id=input('id');
        $data  = $this->request->post();
        $m = new ShopCategoryModel();
        if($m->save($data,$id)){
            return $this->success('保存成功');
        }else{
            return  $this->error('保存失败');
        }
    }

    public function categoryDel(){
        $v =(new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        if(ShopCategoryModel::destroy(input('id'))){
            return $this->success('删除成功');
        }else{
            return  $this->error('删除失败');
        }
    }

    //主题管理  s
    public function theme(){
        $page = input('get.page');
        if (isset($page) && null !== $page) {
        }
        else {
            $page = 1;
        }
        $m=new ThemeModel();
        $list   = $m->order(['id' => 'ASC'])->paginate(15, false, ['page' => $page]);
        $this->assign('list',$list);
        return $this->fetch("theme");
    }

    public function themeAdd(){
        $ul=array();
        $li1['href']=site_url('weicode.theme');
        $li1['name']='主题管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='增加新主题';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('wui',(new WUI())->addUL($ul)
            ->addFormBegin(site_url('weicode.themeSave'))
            ->addFormGroup('主题名','input',"name",'','required')
            ->addFormGroup('备注','input',"description",'','')
            ->addFormGroup('二级页面栏目图','imageSelect','topic_img_id')
            ->addFormGroup('首页图','imageSelect','head_img_id')
            ->addFormButtons()
            ->addFormEnd()
            ->html());
        return parent::m_add('themeAdd');
    }

    public function themeSave(){
        (new Theme())->goCheck();
        $m =new  ThemeModel();
        $m->name = input('post.name');
        $m->description = input('post.description');
        $m->topic_img_id = input('post.topic_img_id');
        $m->head_img_id = input('post.head_img_id');
        if($m->save()){
            return $this->success('保存成功');
        }else{
            return  $this->error('保存失败');
        }
    }

    public function themeEdit(){
        $v = (new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new ThemeModel();
        $result = $m->find(input('get.id'));
        if(!$result){
            return  $this->error('不存在该主题');
        }
        $ul=array();
        $li1['href']=site_url('weicode.theme');
        $li1['name']='主题管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='编辑主题';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('wui',(new WUI())->addUL($ul)
            ->addFormBegin(site_url('weicode.themeUpdate'))
            ->addFormHiddenInfo('id',$result->id)
            ->addFormGroup('主题名','input',"name",$result->name,'required')
            ->addFormGroup('备注','input',"description",$result->description,'')
            ->addFormGroup('二级页面栏目图','imageSelect','topic_img_id',$result->topic_img_id)
            ->addFormGroup('首页图','imageSelect','head_img_id',$result->head_img_id)
            ->addFormButtons()
            ->addFormEnd()
            ->html());
        return parent::m_add('themeEdit');
    }

    public function themeUpdate(){
        $id=input('id');
        $data  = $this->request->post();
        $m = new ThemeModel();
        if($m->save($data,$id)){
            return $this->success('保存成功');
        }else{
            return  $this->error('保存失败');
        }
    }

    public function themeDel(){
        $v =(new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        if(ThemeModel::destroy(input('id'))){
            return $this->success('删除成功');
        }else{
            return  $this->error('删除失败');
        }
    }

    //图片管理  s
    public function image(){
        $page = input('get.page');
        if (isset($page) && null !== $page) {
        }
        else {
            $page = 1;
        }
        $m=new ImageModel();
        $list   = $m->order(['id' => 'ASC'])->paginate(15, false, ['page' => $page]);
        $this->assign('list',$list);
        return $this->fetch("image");
    }

    public function imageAdd(){
        $ul=array();
        $li1['href']=site_url('weicode.image');
        $li1['name']='图片管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='增加新图片';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('wui',(new WUI())->addUL($ul)
            ->addFormBegin(site_url('weicode.imageSave'))
            ->addFormGroup('类型','select','from',1,'required',map('weicode-image-type'))
            ->addFormGroup('网络URL','input',"url1",'','')
            ->addFormGroup('本地图上传','image','url2')
            ->addFormGroup('备注','input',"description",'','')
            ->addFormButtons()
            ->addFormEnd()
            ->html());
        return parent::m_add('imageAdd');
    }

    public function imageSave(){
       $v = (new Image())->webCheck();
       if(is_string($v)){
          return  $this->error($v);
       }

       $m = new ImageModel();
       $m->description = input('post.description');
       $m->url = input('post.url1')|input('post.url2');
       $m->from = input('post.from');
       if($m->save()){
           return $this->success('保存成功');
       }else{
           return  $this->error('保存失败');
       }

    }

    public function imageDel(){
        $v =(new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        if(ImageModel::destroy(input('id'))){
            return $this->success('删除成功');
        }else{
            return  $this->error('删除失败');
        }
    }

    public function imageSelect(){
        $page = input('get.page');
        if (isset($page) && null !== $page) {
        }
        else {
            $page = 1;
        }
        $m=new ImageModel();
        $list   = $m->order(['id' => 'ASC'])->paginate(15, false, ['page' => $page]);
        $this->assign('list',$list);
        $this->assign('name',input('get.name'));
        return $this->fetch("imageSelect");
    }

    //产品管理  s
    public function product(){
        $page = input('get.page');
        if (isset($page) && null !== $page) {
        }
        else {
            $page = 1;
        }
        $m=new ProductModel();
        $list   = $m->with(['shopCategory','image'])->order(['id' => 'ASC'])->paginate(15, false, ['page' => $page]);
        $this->assign('list',$list);
        return $this->fetch("product");
    }

    public function productAdd(){

        $list=ShopCategoryModel::all();
        $ul=array(
            [
                'href'=>site_url('weicode.product'),
                'name'=>'产品管理',
                'active'=>false
            ],
            [
                'href'=>'',
                'name'=>'增加新产品',
                'active'=>true
            ],
        );

        $productUL=array(
            [
                'href' =>'baseInfo',
                'name' => '基础信息',
                'active' => true
            ]
        );

        $this->assign('wui',(new WUI())->addUL($ul)
            ->addULBegin($productUL)
            ->addTabPanelBegin('baseInfo',$productUL)
            ->addFormBegin(site_url('weicode.productSave'))
            ->addFormGroup('产品名','input',"name",'','required')
            ->addFormGroup('价格(元)','number',"price",'','required')
            ->addFormGroup('库存','number',"stock",'','required')
            ->addFormGroup('封面图','imageSelect','img_id')
            ->addFormGroup('摘要','textarea','summary')
            ->addFormGroup('介绍','ueditor','introduce')
            ->addFormGroup('分类','select',"category_id",'','required',toSelectOptions($list,'name','id'))
            ->addFormButtons()
            ->addFormEnd()
            ->addTabPanelEnd()

            ->html());
        return parent::m_add('productAdd');
    }

    public function productSave(){
        (new Product())->goCheck();
        $m =new  ProductModel();
        $data  = $this->request->post();
        if($m->save($data)){
            return $this->success('保存成功');
        }else{
            return  $this->error('保存失败');
        }
    }

    public function productPropertySave(){
        (new ProductProperty())->goCheck();
        $data  = $this->request->post();
        $names = $data['name'];
        $details=$data['detail'];
        $ids = '';
        if(array_key_exists('id',$data)){
            $ids = $data['id'];
        }
        $item=array();
        $item['product_id']=$data['product_id'];
        Db::startTrans();
        try{
            for($i=0;$i<count($names);$i++){
                $item['name'] = $names[$i];
                $item['detail'] = $details[$i];
                $m =new  ProductPropertyModel();
                if(!empty($ids)){
                    $m->save($item,['id'=>$ids[$i]]);
                }else{
                    $m->save($item);
                }
            }

            Db::commit();
        }catch (Exception $ex){
            Db::rollback();
            return  $this->error('保存失败');
        }
            return $this->success('保存成功');
    }

    public function productPropertyDel(){
        $v =(new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        if(ProductPropertyModel::destroy(input('id'))){
            return $this->success('删除成功');
        }else{
            return  $this->error('删除失败');
        }
    }


    public function productEdit(){
        $v = (new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new productModel();
        $result = $m->find(input('get.id'));
        if(!$result){
            return  $this->error('不存在该产品');
        }

        $pp = new ProductPropertyModel();
        $pp_list =$pp->where('product_id','eq',$result->id)->order(['id' => 'ASC'])->select();

        $list=ShopCategoryModel::all();
        $ul=array(
            [
                'href'=>site_url('weicode.product'),
                'name'=>'产品管理',
                'active'=>false
            ],
            [
                'href'=>'',
                'name'=>'编辑产品',
                'active'=>true
            ],
        );

        $productUL=array(
            [
                'href' =>'baseInfo',
                'name' => '基础信息',
                'active' => true
            ],
            [
                'href' =>'protInfo',
                'name' => '产品参数',
                'active' => false
            ]
        );

        $pp_html='';

        foreach ($pp_list as $pp){
            $pp_html.=(new WUI())->addFormGroup('参数名','input',"name[]",$pp->name)->addFormGroup('描述','textarea',"detail[]",$pp->detail)
       ->addFormHiddenInfo('id[]',$pp->id)->addFormGroup('删除参数','button','del','','action="'.site_url('weicode.productPropertyDel',['id'=>$pp->id]).'"','','','btn btn-danger')->getHtml();
        }

        if(empty($pp_html)){
            $pp_html =(new WUI())->addFormGroup('参数名','input',"name[]",'','required')
                ->addFormGroup('描述','textarea',"detail[]",'','required')->getHtml();
        }

        $this->assign('wui',(new WUI())->addUL($ul)
            ->addULBegin($productUL)
            ->addTabPanelBegin('baseInfo',$productUL)
            ->addFormBegin(site_url('weicode.productUpdate'))
            ->addFormHiddenInfo('id',$result->id)
            ->addFormGroup('产品名','input',"name",$result->name,'required')
            ->addFormGroup('价格(元)','number',"price",$result->price,'required')
            ->addFormGroup('库存','number',"stock",$result->stock,'required')
            ->addFormGroup('封面图','imageSelect','img_id',$result->img_id)
            ->addFormGroup('摘要','textarea','summary',$result->summary)
            ->addFormGroup('介绍','ueditor','introduce',$result->introduce)
            ->addFormGroup('分类','select',"category_id",$result->category_id,'required',toSelectOptions($list,'name','id'))
            ->addFormButtons()
            ->addFormEnd()
            ->addTabPanelEnd()

            ->addTabPanelBegin('protInfo',$productUL)
            ->addFormBegin(site_url('weicode.productPropertySave'))
            ->addFormHiddenInfo('product_id',$result->id)
            ->addHtml($pp_html)
            ->addFormGroup('添加参数','button','addPropertyBtn')
            ->addFormButtons()
            ->addFormEnd()
            ->addTabPanelEnd()

            ->html());
        return parent::m_add('productEdit');
    }


    public function productUpdate(){
        $id=input('id');
        $data  = $this->request->post();
        $m = new productModel();
        if($m->save($data,$id)){
            return $this->success('保存成功');
        }else{
            return  $this->error('保存失败');
        }
    }

    public function productDel(){
        $v =(new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }

        Db::startTrans();
        try{
            productModel::destroy(input('id'));
            ProductPropertyModel::destroy(['product_id'=>input('id')]);
            Db::commit();
        }catch (Exception $ex){
            Db::rollback();
            return  $this->error('删除失败');
        }

        return $this->success('删除成功');

    }

    //订单管理  s
    public function order(){
        $page = input('get.page');
        if (isset($page) && null !== $page) {
        }
        else {
            $page = 1;
        }
        $m=new OrderModel();
        $list   = $m->order(['id' => 'ASC'])->paginate(15, false, ['page' => $page]);
        $this->assign('list',$list);
        return $this->fetch("order");
    }

    public function orderDelivery(){
        $v = (new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $id=input('get.id');
        $order = new OrderService();
        $success = $order->delivery($id);
        if($success){
            return new SuccessMessage();
        }
    }

    public function orderRefund(){

    }



}