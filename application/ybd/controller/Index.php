<?php
namespace app\ybd\controller;

use app\common\controller\HomeBase;
use app\common\model\Article as ArticleModel;
use think\Session;
use app\common\model\CmsMsg as CmsMsgModel;
use Cms;

class Index extends HomeBase
{

	 protected function _initialize()
    {
        parent::_initialize();
        $this->setResources();
        Session::set("w_current_menu","index");
    }

    //    首页  in
    public function index(){
	     Session::set("w_current_menu","index");
        Session::set("w_current_menu_2",null);
        $this->assign('case_list',Cms::get_articles_by_cid_paged(17,4));
        $this->assign('new_list',Cms::get_articles_by_cid_paged(13,6));
	     return $this->fetch('/index');
    }

    // 公司介绍  in
    public function companyintroduction(){
        Session::set("w_current_menu","aboutus");
        Session::set("w_current_menu_2","companyintroduction");
        $this->assign('article',Cms::get_article_bycid(29));
        return $this->fetch('/companyintroduction');
    }

    // 公司文化  in
    public function companycultural(){
        Session::set("w_current_menu","aboutus");
        Session::set("w_current_menu_2","companycultural");
        $this->assign('article',Cms::get_article_bycid(30));
        return $this->fetch('/companycultural');
    }

    // 公司资质  in
    public function companyqua(){
        Session::set("w_current_menu","aboutus");
        Session::set("w_current_menu_2","companyqua");
        $this->assign('article',Cms::get_article_bycid(31));
        return $this->fetch('/companyqua');
    }

    // 组织架构  in
    public function companymou(){
        Session::set("w_current_menu","aboutus");
        Session::set("w_current_menu_2","companymou");
        $this->assign('article',Cms::get_article_bycid(32));
        return $this->fetch('/companymou');
    }

    // 公司动态  in
    public function companynews(){
        Session::set("w_current_menu","news");
        Session::set("w_current_menu_2","companynews");
        $id=input('id');
        if(!empty($id)){
            $this->assign('article',Cms::get_article_bycid($id));
        }else{
            $this->assign('list',Cms::get_articles_by_cid_paged(13,5));
        }
        return $this->fetch('/companynews');
    }

    // 行业资讯  in
    public function industrynews(){
        Session::set("w_current_menu","news");
        Session::set("w_current_menu_2","industrynews");
        $id=input('id');
        if(!empty($id)){
            $this->assign('article',Cms::get_article_bycid($id));
        }else{
            $this->assign('list',Cms::get_articles_by_cid_paged(14,5));
        }
        return $this->fetch('/industrynews');
    }

    // 安全资讯  in
    public function safenews(){
        Session::set("w_current_menu","news");
        Session::set("w_current_menu_2","safenews");
        $id=input('id');
        if(!empty($id)){
            $this->assign('article',Cms::get_article_bycid($id));
        }else{
            $this->assign('list',Cms::get_articles_by_cid_paged(15,5));
        }
        return $this->fetch('/safenews');
    }

    // 联系我们 in
    public function contactus(){
        Session::set("w_current_menu","contactus");
        return $this->fetch('/contactus');
    }


    // 电力咨询 in
    public function eadvisory(){
        Session::set("w_current_menu","solutions");
        Session::set("w_current_menu_2","eadvisory");
        $this->assign('article',Cms::get_article_bycid(33));
        return $this->fetch('/eadvisory');
    }

    // 电力设计 in
    public function edesign(){
        Session::set("w_current_menu","solutions");
        Session::set("w_current_menu_2","edesign");
        $this->assign('article',Cms::get_article_bycid(34));
        return $this->fetch('/edesign');
    }

    // 电力工程 in
    public function eproject(){
        Session::set("w_current_menu","solutions");
        Session::set("w_current_menu_2","eproject");
        $this->assign('article',Cms::get_article_bycid(35));
        return $this->fetch('/eproject');
    }

    // 智慧能源管理 in
    public function emanagement(){
        Session::set("w_current_menu","solutions");
        Session::set("w_current_menu_2","emanagement");
        $this->assign('article',Cms::get_article_bycid(36));
        return $this->fetch('/emanagement');
    }

    // 电力运维托管 in
    public function etrusteeship(){
        Session::set("w_current_menu","solutions");
        Session::set("w_current_menu_2","etrusteeship");
        $this->assign('article',Cms::get_article_bycid(38));
        return $this->fetch('/etrusteeship');
    }

    // 购电售电 in
    public function ebusiness(){
        Session::set("w_current_menu","solutions");
        Session::set("w_current_menu_2","ebusiness");
        $this->assign('article',Cms::get_article_bycid(37));
        return $this->fetch('/ebusiness');
    }

    // 经典案例 in
    public function cases(){
        Session::set("w_current_menu","cases");
        Session::set("w_current_menu_2","cases");
        $id=input('id');
        if(!empty($id)){
            $this->assign('article',Cms::get_article_bycid($id));
        }else{
            $this->assign('list',Cms::get_articles_by_cid_paged(17,5));
        }
        return $this->fetch('/cases');
    }

    // 招聘信息 in
    public function jobsinfo(){
        Session::set("w_current_menu","jobs");
        Session::set("w_current_menu_2","jobsinfo");
        $id=input('id');
        if(!empty($id)){
            $this->assign('article',Cms::get_article_bycid($id));
        }else{
            $this->assign('list',Cms::get_articles_by_cid_paged(19,5));
        }
        return $this->fetch('/jobsinfo');
    }

    // 应聘流程 in
    public function jobscir(){
        Session::set("w_current_menu","jobs");
        Session::set("w_current_menu_2","jobscir");
        $this->assign('article',Cms::get_article_bycid(39));
        return $this->fetch('/jobscir');
    }

    // 入职指南 in
    public function jobsguide(){
        Session::set("w_current_menu","jobs");
        Session::set("w_current_menu_2","jobsguide");
        $this->assign('article',Cms::get_article_bycid(40));
        return $this->fetch('/jobsguide');
    }

    //文章
        public function article(){
    	$id  = $this->request->param('id/d');
    	 if (empty($id)) {
            return false;
        }
    	$article_model  = new ArticleModel();
    	$article = $article_model->get($id);
    	$this->assign('article',$article);
        $classification  = $this->request->param('classification');
        $type  = $this->request->param('type');
        $this->assign('classification',$classification );
        $this->assign('type',$type );
        return $this->fetch('/article');
    }

    //留言对话框html  in
    public function msgdialog(){
        return $this->fetch('/public/msgdialog');
    }

    public function message()
    {
        if ($this->request->isPost()) {
            $data            = $this->request->post();
            $validate_result = $this->validate($data, 'CmsMsg');

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                $cmsMsg_Model= new CmsMsgModel();
                if ($cmsMsg_Model->save($data)) {
                    $this->success('保存成功');
                } else {
                    $this->error('保存失败');
                }
            }
        }
    }

}
