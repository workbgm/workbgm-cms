<?php
namespace app\index\controller;

use app\common\controller\HomeBase;
use app\common\model\Article as ArticleModel;
use think\Db;
use think\Url;
use think\Session;
use PHPExcel;

class Index extends HomeBase
{

	 protected function _initialize()
    {
        parent::_initialize();
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

    public function excel(){
        $objPHPExcel=new PHPExcel();
        $objSheet=$objPHPExcel->getActiveSheet();
        $objSheet->setTitle("demo");
//        $objSheet->setCellValue("A1","姓名")->setCellValue("B1","分数");
//        $objSheet->setCellValue("A2","吴渭明")->setCellValue("B2","99");
//        $objSheet->setCellValue("A3","吴渭明")->setCellValue("B3","100");
        $array=array(
            array("姓名","分数"),
            array("姓名","1"),
            array("姓名","2"),
            array("姓名","3")
        );
        $objSheet->fromArray($array);
        $fileName = "demo.xls";
        $fileName = iconv("utf-8", "gb2312", $fileName);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); //文件通过浏览器下载
        exit;
    }
}
