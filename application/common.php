<?php

use think\Db;
use think\Config;
use think\Loader;

// 插件目录
define('ADDON_PATH', ROOT_PATH . 'addons' . DS);

// 如果插件目录不存在则创建
if (!is_dir(ADDON_PATH)) {
    @mkdir(ADDON_PATH, 0777, true);
}

// 注册类的根命名空间
Loader::addNamespace('addons', ADDON_PATH);

/**
 * 获取当前admin.php路径
 * @return string
 */
function site_index(){
    $the_file_path = $_SERVER['PHP_SELF'];
    $findme   = '/admin.php';
    $pos = strpos($the_file_path, $findme)+strlen($findme);
    $target_path = substr($the_file_path, 0,$pos);
    $site_url = "http://".$_SERVER['HTTP_HOST'].$target_path;
    return  $site_url;
}

/**
 * 生成后台访问路径
 * @param $url
 * @param array $args
 * @return string
 */
function site_url($url,$args=[]){
    $info=explode('.',$url);
    return site_index().'/entry/handler/m/'.$info[0].'/ac/'.$info[1].'/t/admin?'.http_build_query($args);
//    return site_index().'/entry/handler?m='.$info[0].'&ac='.$info[1].'&t=admin&'.http_build_query($args);
}

/**
 * 生成前台访问路径
 * @param $url
 * @param array $args
 * @return string
 */
function web_url($url,$args=[]){
    $info=explode('.',$url);
    return site_index().'/module/entry/handler/m/'.$info[0].'/ac/'.$info[1].'/t/web?'.http_build_query($args);
//    return site_index().'/module/entry/handler?m='.$info[0].'&ac='.$info[1].'&t=web&'.http_build_query($args);
}

/**
 * 显示状态
 * @param int $status     0|1|-1
 * @return string
 */
function get_status($status)
{
    switch ($status) {
        case 0 :
            $showText = '<span class="with-padding bg-default">禁用</span>';
            break;
        case -1 :
            $showText = '<span class="with-padding bg-danger">删除</span>';
            break;
        case 1 :
        default :
            $showText = '<span class="with-padding bg-primary">正常</span>';
    }
    return  $showText;
}

/**
 * @param $u
 * @param string $f  格式
 * @return false|string
 */
function unixTo($u,$f='Y-m-d H:i:s'){
    return date($f,(int)$u);
}

/**
 * 获取字段备注
 * @param $tablename 表名
 * @param $columnname 字段名
 */
function getFieldComment($tablename,$columnname,$database=null)
{
    if(empty($database)){
        $database=Config::get('database.database');
    }
    $sql_str = "SELECT COLUMN_COMMENT FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '"
        . $tablename . "' AND TABLE_SCHEMA = '" . $database
        . "' AND COLUMN_NAME = '" . $columnname . "'";
    return Db::query($sql_str)[0]['COLUMN_COMMENT'];
}

/**
 * 获取表备注
 * @param $tablename
 * @param null $database
 * @return mixed
 */
function getTableComment($tablename,$database=null){
    if(empty($database)){
        $database=Config::get('database.database');
    }
    $sql_str = "show table status where name='"
        . $tablename . "'";
    return Db::query($sql_str)[0]['Comment'];
}

/**
 * 数组层级缩进转换
 * @param array $array 源数组
 * @param int $pid
 * @param int $level
 * @return array
 */
function array2level($array, $pid = 0, $level = 1)
{
    static $list = [];
    foreach ($array as $v) {
        if ($v['pid'] == $pid) {
            $v['level'] = $level;
            $list[] = $v;
            array2level($array, $v['id'], $level + 1);
        }
    }

    return $list;
}

/**
 * 构建层级（树状）数组
 * @param array $array 要进行处理的一维数组，经过该函数处理后，该数组自动转为树状数组
 * @param string $pid_name 父级ID的字段名
 * @param string $child_key_name 子元素键名
 * @return array|bool
 */
function array2tree(&$array, $pid_name = 'pid', $child_key_name = 'children')
{
    $counter = array_children_count($array, $pid_name);
    if (!isset($counter[0]) || $counter[0] == 0) {
        return $array;
    }
    $tree = [];
    while (isset($counter[0]) && $counter[0] > 0) {
        $temp = array_shift($array);
        if (isset($counter[$temp['id']]) && $counter[$temp['id']] > 0) {
            array_push($array, $temp);
        } else {
            if ($temp[$pid_name] == 0) {
                $tree[] = $temp;
            } else {
                $array = array_child_append($array, $temp[$pid_name], $temp, $child_key_name);
            }
        }
        $counter = array_children_count($array, $pid_name);
    }

    return $tree;
}

/**
 * 子元素计数器
 * @param array $array
 * @param int $pid
 * @return array
 */
function array_children_count($array, $pid)
{
    $counter = [];
    foreach ($array as $item) {
        $count = isset($counter[$item[$pid]]) ? $counter[$item[$pid]] : 0;
        $count++;
        $counter[$item[$pid]] = $count;
    }

    return $counter;
}

/**
 * 把元素插入到对应的父元素$child_key_name字段
 * @param        $parent
 * @param        $pid
 * @param        $child
 * @param string $child_key_name 子元素键名
 * @return mixed
 */
function array_child_append($parent, $pid, $child, $child_key_name)
{
    foreach ($parent as &$item) {
        if ($item['id'] == $pid) {
            if (!isset($item[$child_key_name]))
                $item[$child_key_name] = [];
            $item[$child_key_name][] = $child;
        }
    }

    return $parent;
}

/**
 * 循环删除目录和文件
 * @param string $dir_name
 * @return bool
 */
function delete_dir_file($dir_name)
{
    $result = false;
    if (is_dir($dir_name)) {
        if ($handle = opendir($dir_name)) {
            while (false !== ($item = readdir($handle))) {
                if ($item != '.' && $item != '..') {
                    if (is_dir($dir_name . DS . $item)) {
                        delete_dir_file($dir_name . DS . $item);
                    } else {
                        unlink($dir_name . DS . $item);
                    }
                }
            }
            closedir($handle);
            if (rmdir($dir_name)) {
                $result = true;
            }
        }
    }

    return $result;
}

/**
 * 判断是否为手机访问
 * @return  boolean
 */
function is_mobile()
{
    static $is_mobile;

    if (isset($is_mobile)) {
        return $is_mobile;
    }

    if (empty($_SERVER['HTTP_USER_AGENT'])) {
        $is_mobile = false;
    } elseif (strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
        || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false
    ) {
        $is_mobile = true;
    } else {
        $is_mobile = false;
    }

    return $is_mobile;
}

/**
 * 手机号格式检查
 * @param string $mobile
 * @return bool
 */
function check_mobile_number($mobile)
{
    if (!is_numeric($mobile)) {
        return false;
    }
    $reg = '#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#';

    return preg_match($reg, $mobile) ? true : false;
}


/**
 * [返回字典组列表]
 * @param [type] $group [组值]
 * @return select所需要的值
 */
function map($group)
{
    $options = Db::name('map')->where(['group' => ['eq', $group]])->select();
    return $options;
}

/**
 * 专门给select用的数据源整理
 * @param $list  数据库查出来的表记录
 * @param string $name  对应的name
 * @param string $value  对应的value
 * @return array
 */
function toSelectOptions($list,$name="name",$value="value"){
    $arr = [];
    foreach ($list as $vo){
        $item['name'] = $vo[$name];
        $item['value'] = $vo[$value];
        array_push($arr,$item);
    }
    return $arr;
}


function help_sub($text, $length)
{
    if (mb_strlen($text, 'utf8') > $length) {
        return mb_substr($text, 0, $length, 'utf8') . '...';
    }
    return $text;
}

/**
 * 模拟tab产生空格
 * @param int $step
 * @param string $string
 * @param int $size
 * @return string
 */
function tab($step = 1, $string = ' ', $size = 4)
{
    return str_repeat($string, $size * $step);
}

/**
 * 用于高亮搜索关键词
 * @param string $string 原文本
 * @param string $needle 关键词
 * @param string $class  span标签class名
 * @return mixed
 */
function high_light($string, $needle = '', $class = 'c-red')
{
    return $needle !== '' ? str_replace($needle, "<span class='{$class}'>" . $needle . "</span>", $string) : $string;
}


/**
 * [select标签]
 * @param  [type]  $label      [显示名称]
 * @param  [type]  $name       [表单name]
 * @param  [type]  $options    [select选择项，name/value]
 * @param  boolean $isrequired [是否必填]
 * @param  string $value [默认选择项]
 * @param  string $attrib [其他附加属性]
 * @return [type]              [select的html]
 */
function m_select($label, $name, $options, $isrequired = false, $value = "", $attrib = "", $itemid)
{
    $require_html = '';
    if ($isrequired) {
        $require_html = 'required';
    }
    $options_html = '';
    foreach ($options as $option) {
        if ($value == $option['value']) {
            $options_html .= '<option selected="selected" value="' . $option['value'] . '" >' . $option['name'] . '</option>';
        } else {
            $options_html .= '<option value="' . $option['value'] . '" >' . $option['name'] . '</option>';
        }
    }
    $html = ' <div class="form-group" id="' . $itemid . '">
             <label class="col-sm-2">' . $label . '</label>
                <div class="col-md-6 col-sm-10">
                    <select  class="form-control" ' . $attrib . ' name="' . $name . '"  ' . $require_html . '>' . $options_html . '
                    </select>
                </div>
             </div>';
    return $html;
}

function EXCEL($fileName, $data, $head) {
    //对数据进行检验
    if (empty($data) || !is_array($data)) {
        die("data must be a array");
    }
    $date = date("Y_m_d", time());
    $fileName .= "_{$date}.xls";
    $objPHPExcel = new \PHPExcel();
    $objProps = $objPHPExcel->getProperties();
    $objActSheet = $objPHPExcel->getActiveSheet();
    $objPHPExcel->getActiveSheet()->getStyle()->getFont()->setName('微软雅黑'); //设置字体
    $objPHPExcel->getActiveSheet()->getDefaultRowDimension()->setRowHeight(25); //设置默认高度

    //设置边框
    $sharedStyle1 = new \PHPExcel_Style();
    $sharedStyle1->applyFromArray(array('borders' => array('allborders' => array('style' => \PHPExcel_Style_Border::BORDER_THIN))));
    $rowIndex = 1;
    foreach ($data as $ke => $row) {
        $columnIndex = 1;
        foreach ($head as $h) {
            $columnName = getColumnNameByNum($columnIndex);
            $objActSheet->setCellValue($columnName . $rowIndex, $row[$h]);
            $columnIndex++;
        }
        $rowIndex++;
    }
    for ($index = 1; $index <= $columnIndex; $index++) {
        $objPHPExcel->getActiveSheet()->getColumnDimension(getColumnNameByNum($index))->setWidth('20'); //设置列宽
    }

    $fileName = iconv("utf-8", "gb2312", $fileName);
    //设置活动单指数到第一个表,所以Excel打开这是第一个表
    $objPHPExcel->setActiveSheetIndex(0);
    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename=\"$fileName\"");
    header('Cache-Control: max-age=0');
    $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output'); //文件通过浏览器下载
    exit;
}

/**
 * @param string $url post/put
 * @param array $params
 * @return mixed
 */
function curl_p($url, array $params = array(),$method='post')
{
    $data_string = json_encode($params);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $method = strtolower(trim($method));
    if($method=='post'){
        curl_setopt($ch, CURLOPT_POST, 1);
    }else if($method=='put'){
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "put");
    }

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt(
        $ch, CURLOPT_HTTPHEADER,
        array(
            'Content-Type: application/json'
        )
    );
    $data = curl_exec($ch);
    curl_close($ch);
    return ($data);
}

function curl_post_raw($url, $rawData)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $rawData);
    curl_setopt(
        $ch, CURLOPT_HTTPHEADER,
        array(
            'Content-Type: text'
        )
    );
    $data = curl_exec($ch);
    curl_close($ch);
    return ($data);
}

/**
 * @param string $url get请求地址
 * @param int $httpCode 返回状态码
 * @return mixed
 */
function curl_get($url, &$httpCode = 0)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    //不做证书校验,部署在linux环境下请改为true
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $file_contents = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $file_contents;
}

function getRandChar($length)
{
    $str = null;
    $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
    $max = strlen($strPol) - 1;

    for ($i = 0;
         $i < $length;
         $i++) {
        $str .= $strPol[rand(0, $max)];
    }

    return $str;
}



function fromArrayToModel($m , $array)
{
    foreach ($array as $key => $value)
    {
        $m[$key] = $value;
    }
    return $m;
}

/**
 * 显示状态
 * @param //-1 被撤回 1已采样  2 已上传  待采样
 * @return string
 */
function getNodeState($status)
{
    switch ($status) {
        case -1:
            $showText = '被撤回';
            break;
        case 1 :
            $showText = '已采样';
            break;
        case 2 :
            $showText = '已上传';
            break;
        default :
            $showText = '待采样';
    }
    return  $showText;
}


function getDirectionState($status)
{
//居民点、厂矿、耕地、林地、草地、水域、其他
    $showText='';
    $len = strlen($status);
    if($len!=7){
        $showText='<span class="with-padding bg-danger">数据不合法</span>';
        return $showText;
    }else{
        for($i=0;$i<$len;$i++){
            if($status[$i]){
                switch ($i){
                    case 0:
                        $showText.='<span class="with-padding bg-primary">居民点</span>';
                        break;
                    case 1:
                        $showText.='<span class="with-padding bg-primary">厂矿</span>';
                        break;
                    case 2:
                        $showText.='<span class="with-padding bg-primary">耕地</span>';
                        break;
                    case 3:
                        $showText.='<span class="with-padding bg-primary">林地</span>';
                        break;
                    case 4:
                        $showText.='<span class="with-padding bg-primary">草地</span>';
                        break;
                    case 5:
                        $showText.='<span class="with-padding bg-primary">水域</span>';
                        break;
                    case 6:
                        $showText.='<span class="with-padding bg-primary">其他</span>';
                        break;
                }
            }
        }
    }
    return $showText;
}

include EXTEND_PATH ."cms/Cms.php";
include EXTEND_PATH ."data/Data.php";
include EXTEND_PATH ."html/Html.php";
include EXTEND_PATH ."html/Css.php";
include EXTEND_PATH ."html/Js.php";
include EXTEND_PATH ."html/WUI.php";
include EXTEND_PATH ."weicode/WeiCode.php";