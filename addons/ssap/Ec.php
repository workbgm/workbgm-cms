<?php
/**
 * Created by PhpStorm.
 * User: wuweiming
 * Date: 2017/10/26
 * Time: 11:20
 */

namespace addons\ssap;
use app\Excel;


class Ec
{
    private $title;//标题
    private $rowIndex;//行号
    private $arry_c;//列名数组
    private $start_c;//开始列序号,从1开始
    private  $end_c;
    private $objActSheet;//excel sheet

    /**
     * Ec constructor.
     * @param $title
     * @param $arry_c
     * @param $start_c
     */
    public function __construct($title, $arry_c,$rowIndex, $start_c,$objActSheet)
    {
        $this->title = $title;
        $this->arry_c = $arry_c;
        $this->start_c = $start_c;
        $this->rowIndex=$rowIndex;
        $this->objActSheet=$objActSheet;
        $this->inExcel();
    }

    /**
     * @return mixed
     */
    public function getRowIndex()
    {
        return $this->rowIndex;
    }

    /**
     * @param mixed $rowIndex
     */
    public function setRowIndex($rowIndex)
    {
        $this->rowIndex = $rowIndex;
    }

    /**
     * @return mixed
     */
    public function getObjActSheet()
    {
        return $this->objActSheet;
    }

    /**
     * @param mixed $objActSheet
     */
    public function setObjActSheet($objActSheet)
    {
        $this->objActSheet = $objActSheet;
    }
    private function setBorderStyle($color)
    {
        $styleArray = array(
            'borders' => array(
                'allborders' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_THIN,
                    'color' => array('rgb' => $color),
                ),
            ),
        );
        return $styleArray;
    }

    private function inExcel(){

        $this->objActSheet->setCellValue(Excel::stringFromColumnIndex($this->start_c).$this->rowIndex,$this->title);
        $index=$this->rowIndex+1;
        for($i=0;$i<count($this->arry_c );$i++){
            $this->objActSheet->setCellValue(Excel::stringFromColumnIndex($this->start_c+$i).$index,$this->arry_c[$i]);
        }
        //获取end_c
        $this->end_c=$this->start_c+count($this->arry_c)-1;
        $this->objActSheet->mergeCells(Excel::stringFromColumnIndex($this->start_c).$this->rowIndex.":".Excel::stringFromColumnIndex($this->end_c).$this->rowIndex);
        $this->objActSheet->getStyle(Excel::stringFromColumnIndex($this->start_c).$this->rowIndex.":".Excel::stringFromColumnIndex($this->end_c).$this->rowIndex)->applyFromArray($this->setBorderStyle('000000'))->getFont()->setName('微软雅黑')->setSize(20)->setBold(true);
    }


    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getArryC()
    {
        return $this->arry_c;
    }

    /**
     * @param mixed $arry_c
     */
    public function setArryC($arry_c)
    {
        $this->arry_c = $arry_c;
    }

    /**
     * @return mixed
     */
    public function getStartC()
    {
        return $this->start_c;
    }

    /**
     * @param mixed $start_c
     */
    public function setStartC($start_c)
    {
        $this->start_c = $start_c;
    }

    /**
     * @return mixed
     */
    public function getEndC()
    {
        return $this->end_c;
    }

    /**
     * @param mixed $end_c
     */
    public function setEndC($end_c)
    {
        $this->end_c = $end_c;
    }//结束序号

    public function setVal($vo,$arry_v,$index){
        for($i=0;$i<count($arry_v);$i++){
            $this->objActSheet->setCellValue(Excel::stringFromColumnIndex($this->start_c+$i).$index,GF($vo,$arry_v[$i],'-'));
        }
    }

    public function setVals($vos,$arry_v,$index){
        if($vos=='')return ;
        $i=$index;
        foreach ($vos as $vo){
            $this->setVal($vo,$arry_v,$i);
            $i++;
        }
    }

    public function merge($index,$max){
        for($i=0;$i<count($this->arry_c );$i++){
            $this->objActSheet->mergeCells(Excel::stringFromColumnIndex($this->start_c+$i).$index.":".Excel::stringFromColumnIndex($this->start_c+$i).($index+$max-1));
        }

    }

}