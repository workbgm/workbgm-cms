<?php
namespace app\admin\controller;

use app\common\controller\AdminBase;
use think\Db;
use PHPExcel;
use Hisune\EchartsPHP\ECharts;
/**
 * 后台首页
 * Class Demo
 * @package app\admin\controller
 */
class Demo extends AdminBase
{
    protected function _initialize()
    {
        parent::_initialize();
    }

    /**
     * phpExcel测试，从模板
     * @param string $temPath
     */
    public function excel($temPath=''){
        $temPath = ROOT_PATH."data\\excel\\tmp-1.xls";
        //检查文件路径
        if(!file_exists($temPath)){
            $this->error('模板不存在');
            return;
        }
        //加载模板
        $phpexcel =  \PHPExcel_IOFactory::createReader("Excel5")->load($temPath);
        $phpexcel->getSheet(0)->setCellValue('B1', '1');
        $phpexcel->getSheet(0)->setCellValue('B2', '2');
        $phpexcel->getSheet(0)->setCellValue('B3', '3');
        $objWriter = new \PHPExcel_Writer_Excel5($phpexcel);
        header("Content-Type: application/force-download");
        header("Content-Type: application/octet-stream");
        header("Content-Type: application/download");
        header('Content-Disposition:inline;filename="demo.xls"');
        header("Content-Transfer-Encoding: binary");
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Pragma: no-cache");
        $objWriter->save('php://output');
    }

    /**
     * 测试echarts
     */
    public function echarts(){
        $chart = new ECharts();
        $chart->tooltip->show = true;
        $chart->legend->data[] = '销量';
        $chart->xAxis[] = array(
            'type' => 'category',
            'data' => array("衬衫","羊毛衫","雪纺衫","裤子","高跟鞋","袜子")
        );
        $chart->yAxis[] = array(
            'type' => 'value'
        );
        $chart->series[] = array(
            'name' => '销量',
            'type' => 'bar',
            'data' => array(5, 20, 40, 10, 10, 20)
        );
        return $chart->render('simple-custom-id');
//        $char=$chart->render('simple-custom-id');
//        $this->assign('echarts',$char);
//        return $this->fetch();
    }
}