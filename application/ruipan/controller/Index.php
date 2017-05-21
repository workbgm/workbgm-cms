<?php
namespace app\ruipan\controller;

use app\common\controller\HomeBase;
use app\common\model\Article as ArticleModel;
use think\Db;
use think\Url;
use think\Session;

class Index extends HomeBase
{

	 protected function _initialize()
    {
        parent::_initialize();
        $infomation_list=get_articles_by_cid_paged(7,6);
        $mobile_list=get_articles_by_cid_paged(8,6);
        $iot_list=get_articles_by_cid_paged(9,6);
        $this->assign('infomation_list',$infomation_list);
        $this->assign('mobile_list',$mobile_list);
        $this->assign('iot_list',$iot_list);
    }

//    public function index()
//    {
//    	$this->assign('articles',get_articles_by_cid(2));
//        Session::set('lst_id', 3);
//        return $this->fetch();
//    }
//
//    public function lst(){
//        $id  = $this->request->param('nav_id');
//        if(empty($id)){
//            return false;
//        }
//        $type  = $this->request->param('type');
//        $link  = $this->request->param('link');
//        if($type=='cate'){
//            $this->assign('articles',get_articles_by_cid($link));
//        }
//        Session::set('lst_id', $id);
//    	return $this->fetch();
//    }
//
//    public function article(){
//    	$id  = $this->request->param('id/d');
//    	 if (empty($id)) {
//            return false;
//        }
//
//    	$article_model  = new ArticleModel();
//    	$article = $article_model->get($id);
//    	$this->assign('article',$article);
//    	return $this->fetch();
//    }

    //    首页
    public function index(){
	     return $this->fetch('/index');
    }

    // 新闻中心
    //最新新闻cid 5  行业新闻6
    public function news($page = 1){
        $page_count=3;
        $classification  = $this->request->param('classification');
        if($classification=='最新新闻' || empty($classification))
        {
            $classification='最新新闻';
            $new_list=get_articles_by_cid_paged(5,$page_count);
        }
        else if($classification=='行业趋势'){
            $new_list=get_articles_by_cid_paged(6,$page_count);
        }
        $this->assign('new_list',$new_list);
        $this->assign('classification',$classification );
        return $this->fetch('/news');
    }

    //solution

    // 解决方案
    //信息化cid 7  移动应用8  物联网9
    public function solution($page = 1){
        $page_count=3;
        $classification  = $this->request->param('classification');
        if($classification=='信息化' || empty($classification))
        {
            $classification='信息化';
            $solution_list=get_articles_by_cid_paged(7,$page_count);
        }
        else if($classification=='移动应用'){
            $solution_list=get_articles_by_cid_paged(8,$page_count);
        }

        else if($classification=='物联网'){
            $solution_list=get_articles_by_cid_paged(9,$page_count);
        }

        $this->assign('solution_list',$solution_list);
        $this->assign('classification',$classification );
        return $this->fetch('/solution');
    }

    // 关于我们
    public function aboutus(){
        return $this->fetch('/aboutus');
    }

    // 联系我们
    public function contactus(){
        return $this->fetch('/contactus');
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

}
