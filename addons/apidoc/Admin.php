<?php
namespace addons\apidoc;
use addons\apidoc\model\Apidoc as ApidocModel;
use addons\apidoc\validate\Apidoc;
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
 * @package Addons\apidoc
 */
class Admin extends  AddonsBase
{

    public function doc(){
        $page = input('get.page');
        if (isset($page) && null !== $page) {
        }
        else {
            $page = 1;
        }
        $m=new ApidocModel();
        $list   = $m->where('isdelete',0)->order(['id' => 'ASC'])->paginate(15, false, ['page' => $page]);
        $this->assign('list',$list);
        return $this->fetch("doc");
    }

    public function docAdd(){
        $ul=array();
        $li1['href']=site_url('apidoc.doc');
        $li1['name']='文档管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='增加新文档';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('wui',(new WUI())->addUL($ul)
            ->addFormBegin(site_url('apidoc.docSave'))
            ->addFormGroup('标题','input',"title",'','required')
            ->addFormGroup('动作','input',"action",'','required')
            ->addFormGroup('描述','ueditor',"description",'','')
            ->addFormButtons()
            ->addFormEnd()
            ->html());
        return parent::m_add('docAdd');
    }

    public function docSave(){
        (new Apidoc())->goCheck();
        $m =new  ApidocModel();
        $m->title = input('post.title');
        $m->description = input('post.description');
        $m->action = input('post.action');
        if($m->save()){
            return $this->success('保存成功');
        }else{
            return  $this->error('保存失败');
        }
    }

    public function docEdit(){
        $v = (new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new ApidocModel();
        $result = $m->find(input('get.id'));
        if(!$result){
            return  $this->error('不存在该文档');
        }
        $ul=array();
        $li1['href']=site_url('apidoc.doc');
        $li1['name']='文档管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='编辑文档';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('wui',(new WUI())->addUL($ul)
            ->addFormBegin(site_url('apidoc.docUpdate'))
            ->addFormHiddenInfo('id',$result->id)
            ->addFormGroup('标题','input',"title",$result->title,'required')
            ->addFormGroup('动作','input','action',$result->action)
            ->addFormGroup('描述','ueditor',"description",$result->description,'')
            ->addFormButtons()
            ->addFormEnd()
            ->html());
        return parent::m_add('docEdit');
    }

    public function docUpdate(){
        $id=input('id');
        $data  = $this->request->post();
        $m = new ApidocModel();
        if($m->save($data,$id)){
            return $this->success('保存成功');
        }else{
            return  $this->error('保存失败');
        }
    }

    public function docDel(){
        $v =(new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new ApidocModel();
        if($m->where('id',input('id'))->setField('isdelete',1)){
            return $this->success('删除成功');
        }else{
            return  $this->error('删除失败');
        }
    }

    public function docRead(){
        $v = (new IDMustBePostiveInt())->webCheck();
        if(is_string($v)){
            return  $this->error($v);
        }
        $m = new ApidocModel();
        $result = $m->find(input('get.id'));
        if(!$result){
            return  $this->error('不存在该文档');
        }
        $ul=array();
        $li1['href']=site_url('apidoc.doc');
        $li1['name']='文档管理';
        $li1['active']=false;
        $ul[0]=$li1;
        $li2['href']='';
        $li2['name']='阅读文档';
        $li2['active']=true;
        $ul[1]=$li2;
        $this->assign('wui',(new WUI())->addUL($ul)
            ->addHtml('<div style="padding:20px;">')
            ->addHtml('<strong class="with-padding" style="font-size: 26px;">'.$result->title.'</strong>')
            ->addHtml('<small class="with-padding">最后更新时间:'.$result->update_time.'</small>')
            ->addHtml('<div class="alert alert-success">目标模块：'.$result->action.'</div>')
            ->addHtml('<div>'.htmlspecialchars_decode($result->description).'</div>')
            ->addHtml('</div>')
            ->html());
        return parent::m_add('docRead');
    }

}