<?php

namespace addons\ssap;

use app\api\ssapmodel\SsapEventLog;
use app\api\ssapmodel\SsapSpecNodeInfo as SsapSpecNodeInfoModel;
use app\api\ssapmodel\SsapSpecSpecimenInfo as SsapSpecSpecimenInfoModel;
use app\api\ssapmodel\SsapSpecSpecimenLab;
use app\common\controller\AddonsBase;
use app\api\ssapmodel\SsapHeartbeat;
use app\WUI;
use app\_Data;
use think\Db;
use think\Exception;
use app\Excel;
use addons\ssap\Ec;

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
class Admin extends AddonsBase
{


    public function ssap()
    {
        $page = input('get.page');
        if (isset($page) && null !== $page) {
        } else {
            $page = 1;
        }
        $m = new SsapSpecNodeInfoModel();
        $list = $m->with(['group'])->paginate(15, false, ['page' => $page]);
        //-1 被撤回 1已采样  2 已上传  待采样
        $this->assign('list', $list);
        return $this->fetch("ssap");
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

    /**
     * 采样任务导出excel
     * @param $vo
     * @param $objActSheet
     */
    private function cyrw_excel($title, $vo, $objActSheet)
    {
        //文字居中
        $objActSheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActSheet->getDefaultStyle()->getFont()->setName('微软雅黑')->setSize(12);
        //设置sheet标题
        $objActSheet->setTitle('采样任务');
        //设置表格标题
        $objActSheet->setCellValue("A1", $title);
        $objActSheet->mergeCells("A1:E1");
        $objActSheet->getStyle("A1:E1")->getFont()->setName('微软雅黑')->setSize(20)->setBold(true)
            ->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_WHITE));
        $objActSheet->getStyle("A1:E1")->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $objActSheet->getStyle("A1:E1")->getFill()->getStartColor()->setRGB('1D7AD9');
        $objActSheet->getStyle("A1:E1")->applyFromArray($this->setBorderStyle('FFFFFF'));
        foreach (range('A', 'E') as $k) {
            $objActSheet->getColumnDimension($k)->setWidth(26);
            $objActSheet->getStyle($k . "2")->applyFromArray($this->setBorderStyle('FFFFFF'));
            $objActSheet->getStyle($k . "3")->applyFromArray($this->setBorderStyle('FFFFFF'));
        }
        //设置表头
        $objActSheet
            ->setCellValue("A2", "NODE_ID")
            ->setCellValue("B2", "采样地点")
            ->setCellValue("C2", "经纬度")
            ->setCellValue("D2", "采样项")
            ->setCellValue("E2", "采样任务状态");
        $objActSheet->getStyle("A2:E2")->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $objActSheet->getStyle("A2:E2")->getFill()->getStartColor()->setRGB('A6A6A6');
        $objActSheet->getStyle("A2:E2")->getFont()->setName('微软雅黑')->setSize(16)
            ->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_WHITE));
        //设置数据
        $objActSheet
            ->setCellValue("A3", $vo["NODE_ID"])
            ->setCellValue("B3", $vo['PROVINCE_NAME'] . $vo['CITY_NAME'] . $vo['COUNTRY_NAME'] . $vo['PLAN_NODE_ADDRESS'])
            ->setCellValue("C3", $vo['LATITUDE'] . ',' . $vo['LONGITUDE'])
            ->setCellValue("D3", $vo['PLAN_QUALITY_CONTROL'])
            ->setCellValue("E3", getNodeState($vo['STATE']));
        $objActSheet->getStyle("A3:E3")->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $objActSheet->getStyle("A3:E3")->getFill()->getStartColor()->setRGB('D9D9D9');
    }

    /**
     * 深层土壤导出excel
     * @param $vo
     * @param $objActSheet
     */
    private function sctr_excel($title, $vo, $objActSheet)
    {
        //文字居中
        $objActSheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActSheet->getDefaultStyle()->getFont()->setName('微软雅黑')->setSize(12);
        //设置sheet标题
        $objActSheet->setTitle('深层土壤');
        //设置表格标题
        $objActSheet->setCellValue("A1", $title);
        $objActSheet->mergeCells("A1:D1");
        $objActSheet->getStyle("A1:D1")->getFont()->setName('微软雅黑')->setSize(20)->setBold(true)
            ->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_WHITE));
        $objActSheet->getStyle("A1:D1")->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $objActSheet->getStyle("A1:D1")->getFill()->getStartColor()->setRGB('1D7AD9');
        $objActSheet->getStyle("A1:D1")->applyFromArray($this->setBorderStyle('FFFFFF'));
        foreach (range('A', 'D') as $k) {
            $objActSheet->getColumnDimension($k)->setWidth(26);
        }

        for ($i = 2; $i <= 5; $i++) {
            $objActSheet->getStyle("A" . $i)->applyFromArray($this->setBorderStyle('FFFFFF'));
            $objActSheet->getStyle("B" . $i)->applyFromArray($this->setBorderStyle('FFFFFF'));
            $objActSheet->getStyle("C" . $i)->applyFromArray($this->setBorderStyle('FFFFFF'));
            $objActSheet->getStyle("D" . $i)->applyFromArray($this->setBorderStyle('FFFFFF'));
            $objActSheet->getStyle("A" . $i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objActSheet->getStyle("A" . $i)->getFill()->getStartColor()->setRGB('A6A6A6');
            $objActSheet->getStyle("A" . $i)->getFont()->setName('微软雅黑')->setSize(16)
                ->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_WHITE));
            $objActSheet->getStyle("C" . $i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objActSheet->getStyle("C" . $i)->getFill()->getStartColor()->setRGB('A6A6A6');
            $objActSheet->getStyle("C" . $i)->getFont()->setName('微软雅黑')->setSize(16)
                ->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_WHITE));
            $objActSheet->getStyle("B" . $i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objActSheet->getStyle("B" . $i)->getFill()->getStartColor()->setRGB('D9D9D9');
            $objActSheet->getStyle("D" . $i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objActSheet->getStyle("D" . $i)->getFill()->getStartColor()->setRGB('D9D9D9');
        }
        //设置表头
        $objActSheet
            ->setCellValue("A2", "样点编码")->setCellValue("B2", $vo['SPECIMEN_CODE'])
            ->setCellValue("A3", "采样深度备注")->setCellValue("B3", $vo['DEPTH_INFO'])
            ->setCellValue("A4", "是否质控样品")->setCellValue("B4", $vo['is_quality_control']['OPTION_NAME'])
            ->setCellValue("A5", "土壤质地")->setCellValue("B5", $vo['soil_type_code']['OPTION_NAME']);
        $objActSheet
            ->setCellValue("C2", "实际经纬度")->setCellValue("D2", $vo['LATITUDE'] . ',' . $vo['LONGITUDE'] . ' 偏移距离:' . $vo['LNG_DISTANCE'] . 'm')
            ->setCellValue("C3", "采样深度(cm)")->setCellValue("D3", $vo['DEPTH_START'] . '-' . $vo['DEPTH_END'])
            ->setCellValue("C4", "样品质量(g)")->setCellValue("D4", $vo['WEIGHT'])
            ->setCellValue("C5", "三角土壤颜色")->setCellValue("D5", $vo['soil_colour']['OPTION_NAME']);
        $this->insert_excel('GPS屏显', 'A6', $objActSheet, ROOT_PATH . $vo['img_path1']['path']);
        $this->insert_excel('采样工作过程', 'B6', $objActSheet, ROOT_PATH . $vo['img_path2']['path']);
        $this->insert_excel('采样负责人', 'C6', $objActSheet, ROOT_PATH . $vo['img_path3']['path']);
        $this->insert_excel('样点变更图片', 'D6', $objActSheet, ROOT_PATH . $vo['img_path4']['path']);
    }


    /**
     * 表层土壤导出excel
     * @param $vo
     * @param $objActSheet
     */
    private function bctr_excel($title, $vo, $objActSheet)
    {
        //文字居中
        $objActSheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActSheet->getDefaultStyle()->getFont()->setName('微软雅黑')->setSize(12);
        //设置sheet标题
        $objActSheet->setTitle('表层土壤');
        //设置表格标题
        $objActSheet->setCellValue("A1", $title);
        $objActSheet->mergeCells("A1:D1");
        $objActSheet->getStyle("A1:D1")->getFont()->setName('微软雅黑')->setSize(20)->setBold(true)
            ->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_WHITE));
        $objActSheet->getStyle("A1:D1")->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $objActSheet->getStyle("A1:D1")->getFill()->getStartColor()->setRGB('1D7AD9');
        $objActSheet->getStyle("A1:D1")->applyFromArray($this->setBorderStyle('FFFFFF'));
        foreach (range('A', 'D') as $k) {
            $objActSheet->getColumnDimension($k)->setWidth(26);
        }

        for ($i = 2; $i <= 5; $i++) {
            $objActSheet->getStyle("A" . $i)->applyFromArray($this->setBorderStyle('FFFFFF'));
            $objActSheet->getStyle("B" . $i)->applyFromArray($this->setBorderStyle('FFFFFF'));
            $objActSheet->getStyle("C" . $i)->applyFromArray($this->setBorderStyle('FFFFFF'));
            $objActSheet->getStyle("D" . $i)->applyFromArray($this->setBorderStyle('FFFFFF'));
            $objActSheet->getStyle("A" . $i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objActSheet->getStyle("A" . $i)->getFill()->getStartColor()->setRGB('A6A6A6');
            $objActSheet->getStyle("A" . $i)->getFont()->setName('微软雅黑')->setSize(16)
                ->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_WHITE));
            $objActSheet->getStyle("C" . $i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objActSheet->getStyle("C" . $i)->getFill()->getStartColor()->setRGB('A6A6A6');
            $objActSheet->getStyle("C" . $i)->getFont()->setName('微软雅黑')->setSize(16)
                ->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_WHITE));
            $objActSheet->getStyle("B" . $i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objActSheet->getStyle("B" . $i)->getFill()->getStartColor()->setRGB('D9D9D9');
            $objActSheet->getStyle("D" . $i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objActSheet->getStyle("D" . $i)->getFill()->getStartColor()->setRGB('D9D9D9');
        }
        //设置表头
        $objActSheet
            ->setCellValue("A2", "样点编码")->setCellValue("B2", $vo['SPECIMEN_CODE'])
            ->setCellValue("A3", "采样深度备注")->setCellValue("B3", $vo['DEPTH_INFO'])
            ->setCellValue("A4", "是否质控样品")->setCellValue("B4", $vo['is_quality_control']['OPTION_NAME'])
            ->setCellValue("A5", "土壤质地")->setCellValue("B5", $vo['soil_type_code']['OPTION_NAME']);
        $objActSheet
            ->setCellValue("C2", "实际经纬度")->setCellValue("D2", $vo['LATITUDE'] . ',' . $vo['LONGITUDE'] . ' 偏移距离:' . $vo['LNG_DISTANCE'] . 'm')
            ->setCellValue("C3", "采样深度(cm)")->setCellValue("D3", $vo['DEPTH_START'] . '-' . $vo['DEPTH_END'])
            ->setCellValue("C4", "样品质量(g)")->setCellValue("D4", $vo['WEIGHT'])
            ->setCellValue("C5", "三角土壤颜色")->setCellValue("D5", $vo['soil_colour']['OPTION_NAME']);
        $this->insert_excel('GPS屏显', 'A6', $objActSheet, ROOT_PATH . $vo['img_path1']['path']);
        $this->insert_excel('采样工作过程', 'B6', $objActSheet, ROOT_PATH . $vo['img_path2']['path']);
        $this->insert_excel('采样负责人', 'C6', $objActSheet, ROOT_PATH . $vo['img_path3']['path']);
        $this->insert_excel('样点变更图片', 'D6', $objActSheet, ROOT_PATH . $vo['img_path4']['path']);
    }

    /**
     * 农产品导出excel
     * @param $vo
     * @param $objActSheet
     */
    private function ncp_excel($title, $vo, $objActSheet)
    {
        //文字居中
        $objActSheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActSheet->getDefaultStyle()->getFont()->setName('微软雅黑')->setSize(12);
        //设置sheet标题
        $objActSheet->setTitle('农产品');
        //设置表格标题
        $objActSheet->setCellValue("A1", $title);
        $objActSheet->mergeCells("A1:D1");
        $objActSheet->getStyle("A1:D1")->getFont()->setName('微软雅黑')->setSize(20)->setBold(true)
            ->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_WHITE));
        $objActSheet->getStyle("A1:D1")->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $objActSheet->getStyle("A1:D1")->getFill()->getStartColor()->setRGB('1D7AD9');
        $objActSheet->getStyle("A1:D1")->applyFromArray($this->setBorderStyle('FFFFFF'));
        foreach (range('A', 'D') as $k) {
            $objActSheet->getColumnDimension($k)->setWidth(26);
        }

        for ($i = 2; $i <= 10; $i++) {
            $objActSheet->getStyle("A" . $i)->applyFromArray($this->setBorderStyle('FFFFFF'));
            $objActSheet->getStyle("B" . $i)->applyFromArray($this->setBorderStyle('FFFFFF'));
            $objActSheet->getStyle("C" . $i)->applyFromArray($this->setBorderStyle('FFFFFF'));
            $objActSheet->getStyle("D" . $i)->applyFromArray($this->setBorderStyle('FFFFFF'));
            $objActSheet->getStyle("A" . $i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objActSheet->getStyle("A" . $i)->getFill()->getStartColor()->setRGB('A6A6A6');
            $objActSheet->getStyle("A" . $i)->getFont()->setName('微软雅黑')->setSize(16)
                ->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_WHITE));
            $objActSheet->getStyle("C" . $i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objActSheet->getStyle("C" . $i)->getFill()->getStartColor()->setRGB('A6A6A6');
            $objActSheet->getStyle("C" . $i)->getFont()->setName('微软雅黑')->setSize(16)
                ->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_WHITE));
            $objActSheet->getStyle("B" . $i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objActSheet->getStyle("B" . $i)->getFill()->getStartColor()->setRGB('D9D9D9');
            $objActSheet->getStyle("D" . $i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objActSheet->getStyle("D" . $i)->getFill()->getStartColor()->setRGB('D9D9D9');
        }
        //设置表头
        $objActSheet
            ->setCellValue("A2", "样点编码")->setCellValue("B2", $vo['SPECIMEN_CODE'])
            ->setCellValue("A3", "样点变更说明")->setCellValue("B3", $vo['DEPTH_INFO'])
            ->setCellValue("A4", "农产品名称")->setCellValue("B4", $vo['farm_produce_name']['OPTION_NAME'])
            ->setCellValue("A5", "是否质控样品")->setCellValue("B5", $vo['is_quality_control']['OPTION_NAME'])
            ->setCellValue("A6", "作物栽培季节")->setCellValue("B6", $vo['crop_season']['OPTION_NAME'])
            ->setCellValue("A7", "施肥情况")->setCellValue("B7", $vo['fertilization_condition']['OPTION_NAME'])
            ->setCellValue("A8", "施用农药情况")->setCellValue("B8", $vo['pesticide_condition']['OPTION_NAME'])
            ->setCellValue("A9", "亩均用量(g)")->setCellValue("B9", $vo['MU_DOSAGE'])
            ->setCellValue("A10", "样品说明")->setCellValue("B10", '');
        $objActSheet
            ->setCellValue("C2", "实际经纬度")->setCellValue("D2", $vo['LATITUDE'] . ',' . $vo['LONGITUDE'])
            ->setCellValue("C3", "农产品类型")->setCellValue("D3", $vo['farm_sample_type']['OPTION_NAME'])
            ->setCellValue("C4", "采样部位")->setCellValue("D4", $vo['sampling_site']['OPTION_NAME'])
            ->setCellValue("C5", "样品质量(g)")->setCellValue("D5", $vo['WEIGHT'])
            ->setCellValue("C6", "当季产量")->setCellValue("D6", $vo['SINGLE_SEASON_YIELD'])
            ->setCellValue("C7", "亩施肥量(kg)")->setCellValue("D7", $vo['MU_FERTILIZATION'])
            ->setCellValue("C8", "施用农药情况(其它)")->setCellValue("D8", $vo['PESTICIDE_CONDITION_OTHER'])
            ->setCellValue("C9", "作物品种")->setCellValue("D9", $vo['CROP_TYPE']);
        $this->insert_excel('GPS屏显', 'A11', $objActSheet, ROOT_PATH . $vo['img_path1']['path']);
        $this->insert_excel('采样工作过程', 'B11', $objActSheet, ROOT_PATH . $vo['img_path2']['path']);
        $this->insert_excel('采样负责人', 'C11', $objActSheet, ROOT_PATH . $vo['img_path3']['path']);
        $this->insert_excel('样点变更图片', 'D11', $objActSheet, ROOT_PATH . $vo['img_path4']['path']);
    }

    /**
     * 复合调查点位导出excel
     * @param $vo
     * @param $objActSheet
     */
    private function fhdcdw_excel($title, $vo, $objActSheet)
    {
        //文字居中
        $objActSheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActSheet->getDefaultStyle()->getFont()->setName('微软雅黑')->setSize(12);
        //设置sheet标题
        $objActSheet->setTitle('复合调查点位');
        //设置表格标题
        $objActSheet->setCellValue("A1", $title);
        $objActSheet->mergeCells("A1:D1");
        $objActSheet->getStyle("A1:D1")->getFont()->setName('微软雅黑')->setSize(20)->setBold(true)
            ->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_WHITE));
        $objActSheet->getStyle("A1:D1")->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
        $objActSheet->getStyle("A1:D1")->getFill()->getStartColor()->setRGB('1D7AD9');
        $objActSheet->getStyle("A1:D1")->applyFromArray($this->setBorderStyle('FFFFFF'));
        foreach (range('A', 'D') as $k) {
            $objActSheet->getColumnDimension($k)->setWidth(26);
        }

        for ($i = 2; $i <= 16; $i++) {
            $objActSheet->getStyle("A" . $i)->applyFromArray($this->setBorderStyle('FFFFFF'));
            $objActSheet->getStyle("B" . $i)->applyFromArray($this->setBorderStyle('FFFFFF'));
            $objActSheet->getStyle("C" . $i)->applyFromArray($this->setBorderStyle('FFFFFF'));
            $objActSheet->getStyle("D" . $i)->applyFromArray($this->setBorderStyle('FFFFFF'));
            $objActSheet->getStyle("A" . $i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objActSheet->getStyle("A" . $i)->getFill()->getStartColor()->setRGB('A6A6A6');
            $objActSheet->getStyle("A" . $i)->getFont()->setName('微软雅黑')->setSize(16)
                ->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_WHITE));
            $objActSheet->getStyle("C" . $i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objActSheet->getStyle("C" . $i)->getFill()->getStartColor()->setRGB('A6A6A6');
            $objActSheet->getStyle("C" . $i)->getFont()->setName('微软雅黑')->setSize(16)
                ->setColor(new \PHPExcel_Style_Color(\PHPExcel_Style_Color::COLOR_WHITE));
            $objActSheet->getStyle("B" . $i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objActSheet->getStyle("B" . $i)->getFill()->getStartColor()->setRGB('D9D9D9');
            $objActSheet->getStyle("D" . $i)->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
            $objActSheet->getStyle("D" . $i)->getFill()->getStartColor()->setRGB('D9D9D9');
        }
        //设置表头
        $objActSheet
            ->setCellValue("A2", "样点编码")->setCellValue("B2", $vo['NODE_CODE'])
            ->setCellValue("A3", "样点类型")->setCellValue("B3", $vo['spec_type']['OPTION_NAME'])
            ->setCellValue("A4", "布点网格大小")->setCellValue("B4", $vo['grid_size']['OPTION_NAME'])
            ->setCellValue("A5", "计划采样地点")->setCellValue("B5", $vo['PLAN_NODE_ADDRESS'])
            ->setCellValue("A6", "样品所在区域")->setCellValue("B6", $vo['site_region']['OPTION_NAME'])
            ->setCellValue("A7", "海拔高度(m)")->setCellValue("B7", $vo['ELEVATION'])
            ->setCellValue("A8", "计划经纬度")->setCellValue("B8", $vo['PLAN_LATITUDE'] . ',' . $vo['PLAN_LONGITUDE'])
            ->setCellValue("A9", "土壤发生分类1")->setCellValue("B9", $vo['soil_type_code']['OPTION_NAME'])
            ->setCellValue("A10", "土壤发生分类2")->setCellValue("B10", $vo['soil_type_code2']['OPTION_NAME'])
            ->setCellValue("A11", "土壤系统分类1")->setCellValue("B11", $vo['soil_sys_code']['OPTION_NAME'])
            ->setCellValue("A12", "土壤系统分类2")->setCellValue("B12", $vo['soil_sys_code2']['OPTION_NAME'])
            ->setCellValue("A13", "土壤系统分类3")->setCellValue("B13", $vo['soil_sys_code3']['OPTION_NAME'])
            ->setCellValue("A14", "土地利用方式")->setCellValue("B14", $vo['soil_use_type']['OPTION_NAME'])
            ->setCellValue("A15", "耕作方式")->setCellValue("B15", $vo['tillage_fashion']['OPTION_NAME'])
            ->setCellValue("A16", "灌溉方式")->setCellValue("B16", $vo['irrigate_fashion']['OPTION_NAME']);
        $objActSheet
            ->setCellValue("C2", "灌溉水类型")->setCellValue("D2", $vo['parent_rock_type']['OPTION_NAME'])
            ->setCellValue("C3", "地形类型")->setCellValue("D3", $vo['landform_type']['OPTION_NAME'])
            ->setCellValue("C4", "正东(多选)")->setCellValue("D4", strip_tags(getDirectionState($vo['DUE_EAST'])))
            ->setCellValue("C5", "正东其它")->setCellValue("D5", $vo['DUE_EASTOTHER'])
            ->setCellValue("C6", "正南(多选)")->setCellValue("D6", strip_tags(getDirectionState($vo['DUE_SOUTH'])))
            ->setCellValue("C7", "正南其它")->setCellValue("D7", $vo['DUE_SOUTH_OTHER'])
            ->setCellValue("C8", "正西(多选)")->setCellValue("D8", strip_tags(getDirectionState($vo['DUE_WEST'])))
            ->setCellValue("C9", "正西其它")->setCellValue("D9", $vo['DUE_WEST_OTHER'])
            ->setCellValue("C10", "正北(多选)")->setCellValue("D10", strip_tags(getDirectionState($vo['DUE_NORTH'])))
            ->setCellValue("C11", "正北其它")->setCellValue("D11", $vo['DUE_NORTH_OTHER'])
            ->setCellValue("C12", "天气情况")->setCellValue("D12", $vo['weather_type_code']['OPTION_NAME'])
            ->setCellValue("C13", "周边潜在污染源")->setCellValue("D13", $vo['POLLUTION_INFO'])
            ->setCellValue("C14", "污染源距离(m)")->setCellValue("D14", $vo['POLLUTION_DISTANCES'])
            ->setCellValue("C15", "采样日期")->setCellValue("D15", $vo['ADD_TIME']);
        $this->insert_excel('样点东侧', 'A17', $objActSheet, ROOT_PATH . $vo['img_path1']['path']);
        $this->insert_excel('样点南侧', 'B17', $objActSheet, ROOT_PATH . $vo['img_path2']['path']);
        $this->insert_excel('样点西侧', 'C17', $objActSheet, ROOT_PATH . $vo['img_path3']['path']);
        $this->insert_excel('样点北侧', 'D17', $objActSheet, ROOT_PATH . $vo['img_path4']['path']);
    }

    private function insert_excel($name, $p, $objActSheet, $path)
    {
        $objDrawing = new \PHPExcel_Worksheet_Drawing();
        $objDrawing->setName($name);
        $objDrawing->setDescription($name);
        $objDrawing->setPath($path);
        $objDrawing->setHeight(400);
        $objDrawing->setWidth(311);
        $objDrawing->setCoordinates($p);
        $objDrawing->setWorksheet($objActSheet);
    }

    private function other_excel($title, $data, $objPHPExcel)
    {
        //复核调查点位
        $objPHPExcel->createSheet();
        $this->fhdcdw_excel($title, $data['fhdcdw'], $objPHPExcel->getSheet(1));
        $objPHPExcel->createSheet();
        $this->ncp_excel($title, $data['ncp'], $objPHPExcel->getSheet(2));
        $objPHPExcel->createSheet();
        $this->bctr_excel($title, $data['bctr'], $objPHPExcel->getSheet(3));
        $objPHPExcel->createSheet();
        $this->sctr_excel($title, $data['sctr'], $objPHPExcel->getSheet(4));
    }


    public function excel()
    {
        set_time_limit(0);
        $node_id = input('get.NODE_ID');
        if (empty($node_id)) {
            return $this->error("请选择一个采样任务!");
        }
        $m = new SsapSpecNodeInfoModel();
        $vo = $m->with(['group'])->where('NODE_ID', $node_id)->find();
        $filename = $vo['NODE_ID'] . '-' . $vo['PROVINCE_NAME'] . $vo['CITY_NAME'] . $vo['COUNTRY_NAME'] . $vo['PLAN_NODE_ADDRESS'] . '.xls';
        $title = $vo['PROVINCE_NAME'] . $vo['CITY_NAME'] . $vo['COUNTRY_NAME'] . $vo['PLAN_NODE_ADDRESS'];
        $objPHPExcel = new \PHPExcel();
        $this->cyrw_excel($title, $vo, $objPHPExcel->getSheet(0));
        $this->other_excel($title, $this->get_snimByID($node_id), $objPHPExcel);
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');//告诉浏览器将要输出excel03文件
        header('Content-Disposition: attachment;filename="' . $filename . '"');//告诉浏览器将输出文件的名称(文件下载)
        header('Cache-Control: max-age=0');//禁止缓存
        $objWriter->save("php://output");
        exit;
    }



    public function excelAll()
    {
        set_time_limit(0);
        $m = new SsapSpecNodeInfoModel();
        $objPHPExcel = new \PHPExcel();
        $vos = $m->with(['group'])->select();
        $filename = '采集制样数据.xls';
        $objActSheet = $objPHPExcel->getSheet(0);
        //文字居中
        $objActSheet->getDefaultStyle()->getAlignment()->setVertical(\PHPExcel_Style_Alignment::VERTICAL_CENTER)
            ->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objActSheet->getDefaultStyle()->getFont()->setName('微软雅黑')->setSize(12);
        //设置excel表头
        $cyrw_arr = array("NODE_ID", "采样地点", "经纬度", "采样项", "采样任务状态");
        $cyrw_arr_v=array("NODE_ID","PROVINCE_NAME|CITY_NAME|COUNTRY_NAME|PLAN_NODE_ADDRESS"
            ,"LATITUDE|LONGITUDE","PLAN_QUALITY_CONTROL","STATE:getNodeState"
        );
        $cyrw_e = new Ec("采样任务", $cyrw_arr,
            1, 1, $objActSheet);
        $fhdw_arr = array("样点编码", "样点类型", "布点网格大小", "计划采样地点", "样品所在区域",
            "海拔高度(m)", "计划经纬度", "土壤发生分类1", "土壤发生分类2",
            "土壤系统分类1", "土壤系统分类2", "土壤系统分类3", "土地利用方式", "耕作方式",
            "灌溉方式", "灌溉水类型", "地形类型", "正东(多选)", "正东其它", "正南(多选)",
            "正南其它", "正西(多选)", "正西其它", "正北(多选)", "正北其它", "天气情况",
            "周边潜在污染源", "污染源距离(m)", "采样日期"
        );
        $fhdw_arr_v = array('NODE_CODE','spec_type.OPTION_NAME',
            'grid_size.OPTION_NAME','PLAN_NODE_ADDRESS','site_region.OPTION_NAME',
            'ELEVATION','PLAN_LATITUDE|PLAN_LONGITUDE','soil_type_code.OPTION_NAME',
            'soil_type_code2.OPTION_NAME','soil_sys_code.OPTION_NAME','soil_sys_code2.OPTION_NAME',
            'soil_sys_code3.OPTION_NAME','soil_use_type.OPTION_NAME','tillage_fashion.OPTION_NAME',
            'irrigate_fashion.OPTION_NAME','parent_rock_type.OPTION_NAME','landform_type.OPTION_NAME',
            'DUE_EAST:getDirectionState','DUE_EASTOTHER','DUE_SOUTH:getDirectionState','DUE_SOUTH_OTHER',
            'DUE_WEST:getDirectionState','DUE_WEST_OTHER','DUE_NORTH:getDirectionState','DUE_NORTH_OTHER',
            'weather_type_code.OPTION_NAME','POLLUTION_INFO','POLLUTION_DISTANCES','ADD_TIME'
            );
        $fhdw_e = new Ec("复合调查点位", $fhdw_arr,
            1, $cyrw_e->getEndC() + 1, $objActSheet);
        $ncp_arr = array("样点编码",
            "样点变更说明",
            "农产品名称",
            "是否质控样品",
            "作物栽培季节",
            "施肥情况",
            "施用农药情况",
            "亩均用量(g)",
            "样品说明",
            "实际经纬度",
            "农产品类型",
            "采样部位",
            "样品质量(g)",
            "当季产量",
            "亩施肥量(kg)",
            "施用农药情况(其它)",
            "作物品种");
        $ncp_arr_v = array('SPECIMEN_CODE','DEPTH_INFO','farm_produce_name.OPTION_NAME',
            'is_quality_control.OPTION_NAME','crop_season.OPTION_NAME','fertilization_condition.OPTION_NAME',
            'pesticide_condition.OPTION_NAME','MU_DOSAGE','','LATITUDE|LONGITUDE','farm_sample_type.OPTION_NAME',
            'sampling_site.OPTION_NAME','WEIGHT','SINGLE_SEASON_YIELD','MU_FERTILIZATION','PESTICIDE_CONDITION_OTHER',
            'CROP_TYPE'
            );
        $ncp_e = new Ec("农产品", $ncp_arr,
            1, $fhdw_e->getEndC() + 1, $objActSheet);
        $ncp_yp_arr = array("样品编号",
            "测试项目",
            "是否质控样",
            "样品重量(g)",
            "过筛孔径(mm)",
            "流转中心",
            "实验室名称",
            "样品编码",
            "样包编码",
            "样包类别",
            "样包状态",
            "样包创建时间",
            "流转状态");
        $ncp_yp_arr_v=array(
            "ID","TEST_NAME","TEST_NAME","WEIGHT_ID","APERTURE","NAME_PREP_LAB","NAME_ANA_LAB","SPEAR1","package.PROJECT_SPARE1",
            "package.PROJECT_KIND_STR|package.PROJECT_SPARE3","package.PROJECT_TYPE:projectType","package.CREATE_TIME","STATE:state"
        );
        $ncp_yp_e = new Ec("农产品样品", $ncp_yp_arr,
            1, $ncp_e->getEndC() + 1, $objActSheet);
        $bctr_arr = array("样点编码",
            "采样深度备注",
            "是否质控样品",
            "土壤质地",
            "实际经纬度",
            "采样深度(cm)",
            "样品质量(g)",
            "三角土壤颜色");
        $bctr_arr_v = array('SPECIMEN_CODE','DEPTH_INFO','is_quality_control.OPTION_NAME',
            'soil_type_code.OPTION_NAME','LATITUDE|LONGITUDE','DEPTH_START|DEPTH_END',
            'WEIGHT','soil_colour.OPTION_NAME'
            );
        $bctr_e = new Ec("表层土壤", $bctr_arr,
            1, $ncp_yp_e->getEndC() + 1, $objActSheet);
        $bctr_yp_arr = array("样品编号",
            "测试项目",
            "是否质控样",
            "样品重量(g)",
            "过筛孔径(mm)",
            "流转中心",
            "实验室名称",
            "样品编码",
            "样包编码",
            "样包类别",
            "样包状态",
            "样包创建时间",
            "流转状态");
        $bctr_yp_e = new Ec("表层土壤样品", $bctr_yp_arr,
            1, $bctr_e->getEndC() + 1, $objActSheet);
        //深层土壤
        $sctr_arr = array("样点编码",
            "采样深度备注",
            "是否质控样品",
            "土壤质地",
            "实际经纬度",
            "采样深度(cm)",
            "样品质量(g)",
            "三角土壤颜色");
        $sctr_e = new Ec("深层土壤", $sctr_arr,
            1, $bctr_yp_e->getEndC() + 1, $objActSheet);
        $sctr_yp_arr = array("样品编号",
            "测试项目",
            "是否质控样",
            "样品重量(g)",
            "过筛孔径(mm)",
            "流转中心",
            "实验室名称",
            "样品编码",
            "样包编码",
            "样包类别",
            "样包状态",
            "样包创建时间",
            "流转状态");
        $sctr_yp_e = new Ec("深层土壤样品", $sctr_yp_arr,
            1, $sctr_e->getEndC() + 1, $objActSheet);
        for($i=1;$i<Excel::columnIndexFromString("DB");$i++){
            $objActSheet->getColumnDimension(Excel::stringFromColumnIndex($i))->setWidth(26);
        }

        $e_index=3;
        foreach ($vos as $cyrw){
            $obj= $this->get_MakeByID($cyrw['NODE_ID']);
            $fhdcdw=G($obj,'fhdcdw');
            $ncp=G($obj,'ncp');
            $bctr=G($obj,'bctr');
            $sctr=G($obj,'sctr');
            $ncp_make_dlz=G($obj,'ncp.make_dlz');
            $ncp_make_ylz=G($obj,'ncp.make_ylz');
            $ncp_make_len=$this->getLen($ncp_make_dlz)+$this->getLen($ncp_make_ylz);
            $bctr_make_dlz=G($obj,'bctr.make_dlz');
            $bctr_make_ylz=G($obj,'bctr.make_ylz');
            $bctr_make_len=$this->getLen($bctr_make_dlz)+$this->getLen($bctr_make_ylz);
            $sctr_make_dlz=G($obj,'sctr.make_dlz');
            $sctr_make_ylz=G($obj,'sctr.make_ylz');
            $sctr_make_len=$this->getLen($sctr_make_dlz)+$this->getLen($sctr_make_ylz);

            $cyrw_e->setVal($cyrw,$cyrw_arr_v,$e_index);
            $fhdw_e->setVal($fhdcdw,$fhdw_arr_v,$e_index);
            $ncp_e->setVal($ncp,$ncp_arr_v,$e_index);

            $ncp_yp_e->setVals($ncp_make_dlz,$ncp_yp_arr_v,$e_index);
            $ncp_yp_e->setVals($ncp_make_ylz,$ncp_yp_arr_v,$e_index+$this->getLen($ncp_make_dlz));

            $bctr_e->setVal($bctr,$bctr_arr_v,$e_index);

            $bctr_yp_e->setVals($bctr_make_dlz,$ncp_yp_arr_v,$e_index);
            $bctr_yp_e->setVals($bctr_make_ylz,$ncp_yp_arr_v,$e_index+$this->getLen($bctr_make_dlz));

            $sctr_e->setVal($sctr,$bctr_arr_v,$e_index);

            $sctr_yp_e->setVals($sctr_make_dlz,$ncp_yp_arr_v,$e_index);
            $sctr_yp_e->setVals($sctr_make_ylz,$ncp_yp_arr_v,$e_index+$this->getLen($sctr_make_dlz));
            $max_=max($ncp_make_len,$bctr_make_len,$sctr_make_len);
            $max=max($ncp_make_len,$bctr_make_len,$sctr_make_len,1);
            if($max_>0){
                $cyrw_e->merge($e_index,$max);
                $fhdw_e->merge( $e_index,$max);
                $ncp_e->merge( $e_index,$max);
                $bctr_e->merge( $e_index,$max);
                $sctr_e->merge( $e_index,$max);
            }
            $objActSheet->getStyle("A".$e_index.":DB".$e_index)->applyFromArray($this->setBorderStyle('000000'));
            $e_index=$e_index+$max;
            if($max_>0) {
                $objActSheet->getStyle("A" . ($e_index-1) . ":DB" . ($e_index-1))->applyFromArray($this->setBorderStyle('000000'));
            }
        }
        $objPHPExcel->setActiveSheetIndex(0);
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        header('Content-Type: application/vnd.ms-excel');//告诉浏览器将要输出excel03文件
        header('Content-Disposition: attachment;filename="' . $filename . '"');//告诉浏览器将输出文件的名称(文件下载)
        header('Cache-Control: max-age=0');//禁止缓存
        $objWriter->save("php://output");
        exit;
    }


    private function getLen($d){
        if(is_string($d)){
            return 0;
        }else if(is_array($d)){
            return sizeof($d);
        }
    }

    public function device()
    {
        $page = input('get.page');
        $imei=input('get.imei');
        $m = new SsapHeartbeat();
        if (isset($page) && null !== $page) {
        } else {
            $page = 1;
        }
        if (isset($imei) && null !== $imei) {
            $list = $m->where('imei',$imei)->paginate(25, false, ['page' => $page]);
        }else{
            $list = $m->paginate(25, false, ['page' => $page]);
        }
        //-1 被撤回 1已采样  2 已上传  待采样
        $this->assign('list', $list);
        return $this->fetch("device");
    }

    //获取采样数据
    private function get_snimByID($node_id)
    {
        $mn = new SsapSpecNodeInfoModel();
        $fhdcdw = $mn->with([
            'gridSize', 'siteRegion', 'specType', 'soilTypeCode', 'soilTypeCode2', 'soilSysCode', 'soilSysCode2', 'soilSysCode3',
            'soilUseType', 'tillageFashion', 'irrigateFashion', 'parentRockType', 'landformType', 'weatherTypeCode',
            'imgPath1', 'imgPath2', 'imgPath3', 'imgPath4', 'imgPath5'
        ])->where("NODE_ID", $node_id)->find();
        $obj['fhdcdw'] = $fhdcdw;
        $m = new SsapSpecSpecimenInfoModel();
        $datas = $m->with([
            'farmSampleType', 'farmProduceName', 'samplingSite', 'isQualityControl', 'cropSeason',
            'fertilizationCondition', 'pesticideCondition', 'soilTypeCode', 'soilColour',
            'imgPath1', 'imgPath2', 'imgPath3', 'imgPath4'
        ])->where("NODE_ID", $node_id)->select();
        foreach ($datas as $data) {
            $specimenCode = $data['SPECIMEN_CODE'];
            $delete_last = substr($specimenCode, -1);//最后一个
            if (strcasecmp($delete_last, 'C') == 0) {
                $obj['ncp'] = $data;
            } else if (strcasecmp($delete_last, 'S') == 0) {
                $obj['bctr'] = $data;
            } else if (strcasecmp($delete_last, 'D') == 0) {
                $obj['sctr'] = $data;
            }
        }
        $obj['title'] = '采样地点:' . $fhdcdw['PROVINCE_NAME'] . $fhdcdw['CITY_NAME'] . $fhdcdw['COUNTRY_NAME'] . $fhdcdw['PLAN_NODE_ADDRESS'];
        return $obj;
    }

    //采样数据
    public function show()
    {
        $node_id = input('get.NODE_ID');
        $this->assign('data', $this->get_snimByID($node_id));
        return $this->fetch("show");
    }

    //获取制样数据
    private function get_MakeByID($node_id)
    {
        $mn = new SsapSpecNodeInfoModel();
        $fhdcdw = $mn->with([
            'gridSize', 'siteRegion', 'specType', 'soilTypeCode', 'soilTypeCode2', 'soilSysCode', 'soilSysCode2', 'soilSysCode3',
            'soilUseType', 'tillageFashion', 'irrigateFashion', 'parentRockType', 'landformType', 'weatherTypeCode',
            'imgPath1', 'imgPath2', 'imgPath3', 'imgPath4', 'imgPath5'
        ])->where("NODE_ID", $node_id)->find();
        $obj['fhdcdw'] = $fhdcdw;
        $m = new SsapSpecSpecimenInfoModel();
        $datas = $m->with([
            'farmSampleType', 'farmProduceName', 'samplingSite', 'isQualityControl', 'cropSeason',
            'fertilizationCondition', 'pesticideCondition', 'soilTypeCode', 'soilColour',
            'imgPath1', 'imgPath2', 'imgPath3', 'imgPath4'
        ])->where("NODE_ID", $node_id)->select();
        $ms = new SsapSpecSpecimenLab();
        foreach ($datas as $data) {
            $specimenCode = $data['SPECIMEN_CODE'];
            $delete_last = substr($specimenCode, -1);//最后一个
            if (strcasecmp($delete_last, 'C') == 0) {
                $obj['ncp'] = $data;
                $obj['ncp']['make_dlz'] = $ms->with(['package'])->where("SPECIMEN_ID", $data['SPECIMEN_ID'])->where('STATE', 1)->select();
                $obj['ncp']['make_ylz'] = $ms->with(['package'])->where("SPECIMEN_ID", $data['SPECIMEN_ID'])->where('STATE', 2)->select();
            } else if (strcasecmp($delete_last, 'S') == 0) {
                $obj['bctr'] = $data;
                $obj['bctr']['make_dlz'] = $ms->with(['package'])->where("SPECIMEN_ID", $data['SPECIMEN_ID'])->where('STATE', 1)->select();
                $obj['bctr']['make_ylz'] = $ms->with(['package'])->where("SPECIMEN_ID", $data['SPECIMEN_ID'])->where('STATE', 2)->select();
            } else if (strcasecmp($delete_last, 'D') == 0) {
                $obj['sctr'] = $data;
                $obj['sctr']['make_dlz'] = $ms->with(['package'])->where("SPECIMEN_ID", $data['SPECIMEN_ID'])->where('STATE', 1)->select();
                $obj['sctr']['make_ylz'] = $ms->with(['package'])->where("SPECIMEN_ID", $data['SPECIMEN_ID'])->where('STATE', 2)->select();
            }
        }
        $obj['title'] = '采样地点:' . $fhdcdw['PROVINCE_NAME'] . $fhdcdw['CITY_NAME'] . $fhdcdw['COUNTRY_NAME'] . $fhdcdw['PLAN_NODE_ADDRESS'];
        return $obj;

    }

    //制样数据
    public function make()
    {
        $node_id = input('get.NODE_ID');
        $this->assign('data', $this->get_MakeByID($node_id));
        return $this->fetch("make");
    }

    //流转数据
    public function circul()
    {
        $node_id = input('get.NODE_ID');
        $this->assign('data', $this->get_MakeByID($node_id));
        return $this->fetch("circul");
    }

    //设备日志
    public function event()
    {
        $imei=input('get.imei');
        $m = new SsapEventLog();
        $vos = $m->where('imei', $imei)->order(['id' => 'DESC'])->limit(50)->select();
        $this->assign('data', $vos);
        return $this->fetch("event");
    }
}