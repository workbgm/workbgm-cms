<?php
namespace app\index\controller;

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
        Url::root('/index.php');
    }

    public function index()
    {
    	$this->assign('articles',get_articles_by_cid(2));
        Session::set('lst_id', 3);
        return $this->fetch();
    }

    public function lst(){
        $id  = $this->request->param('nav_id');
        if(empty($id)){
            return false;
        }
        $type  = $this->request->param('type');
        $link  = $this->request->param('link');
        if($type=='cate'){
            $this->assign('articles',get_articles_by_cid($link));
        }
        Session::set('lst_id', $id);
    	return $this->fetch();
    }

    public function article(){
    	$id  = $this->request->param('id/d');
    	 if (empty($id)) {
            return false;
        }

    	$article_model  = new ArticleModel();
    	$article = $article_model->get($id);
    	$this->assign('article',$article);
    	return $this->fetch();
    }
}
