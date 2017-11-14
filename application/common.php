<?php

use think\Db;
use think\Config;
use think\Loader;
use think\Cache;

// 插件目录
define('ADDON_PATH', ROOT_PATH . 'addons' . DS);

// 如果插件目录不存在则创建
if (!is_dir(ADDON_PATH)) {
    @mkdir(ADDON_PATH, 0777, true);
}

// 注册类的根命名空间
Loader::addNamespace('addons', ADDON_PATH);

function G($arr,$keys){
    $key_arr=explode('.',$keys);
    $obj=$arr;
    foreach ($key_arr as $key){
        if(isset($obj[$key])){
            $obj=$obj[$key];
        }else{
            return '';
        }
    }
    return $obj;
}

function GA($arr,$keys,$and=''){
    $key_arr=explode('|',$keys);
    $result='';
    $last = array_pop($key_arr);
    foreach ($key_arr as $key){
        $result.=G($arr,$key).$and;
    }
    $result.=G($arr,$last);
    return $result;
}

function GF($arr,$keys,$and='')
{
    if(empty($keys)){return '';}
    $key_arr = explode(':', $keys);
    if(count($key_arr)==2){
        return $key_arr[1](GA($arr,$key_arr[0]));
    }else{
        return GA($arr,$keys,$and);
    }
}

function state($s){
    if($s==1){
        return "待流转";
    }else if($s==2){
        return "已流转";
    }else{
        return "";
    }
}

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



function projectType($d){
   if($d==2){
       return '已寄出';
   }else{
       return '可用';
   }
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

function getDeviceState($time)
{
    $status = time()-strtotime($time);
    if(abs($status)<600){ //十分钟内
        return '<span class="label label-success">在线</span>';
    }
    return '<span class="label">离线</span>';
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
                        $showText.='居民点';
                        break;
                    case 1:
                        $showText.='厂矿';
                        break;
                    case 2:
                        $showText.='耕地';
                        break;
                    case 3:
                        $showText.='林地';
                        break;
                    case 4:
                        $showText.='草地';
                        break;
                    case 5:
                        $showText.='水域';
                        break;
                    case 6:
                        $showText.='其他';
                        break;
                }
            }
        }
    }
    return $showText;
}

/**
 * 获取微信token
 */
function get_weixin_access_token(){
    $accessToken = Cache::get('access_token');
    if(!$accessToken){
        $appId=config('wechat.app_id');
        $appSecurity=config('wechat.app_security');
        $url="https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$appId}&secret={$appSecurity}";
        $result=curl_get($url);
        $accessTokenArr=json_decode($result,true);
        Cache::set('access_token',$accessTokenArr['access_token'],7200);
        $accessToken=$accessTokenArr['access_token'];
    }
    return $accessToken;
}

/**
 * 获取微信的标签组
 * @return mixed
 */
function get_weixin_tags(){
    $accessToken=get_weixin_access_token();
    return curl_get("https://api.weixin.qq.com/cgi-bin/tags/get?access_token={$accessToken}");
}

/**
 * 获取微信标签下所有用户的openid
 * 链接:http://yn.zipscloud.com/api.php/v1/getopenidsbytagid
 * //{"count":2,"data":{"openid":["offZnuFSCsgMZlBGsm97RrQiAC1g","offZnuEiImmBLDMpsnDPWbsFlEUI"]},"next_openid":"offZnuEiImmBLDMpsnDPWbsFlEUI"}
 */
function getOpenIdsByTagId($tagId){
    $result="";
    if(!empty($tagId)){
        $accessToken=get_weixin_access_token();
        $parms['tagid']=$tagId;
        $parms['next_openid']='';
        $url = "https://api.weixin.qq.com/cgi-bin/user/tag/get?access_token={$accessToken}";
        $result=curl_post_raw($url,json_encode($parms));
    }
    return $result;
}

/**
 * 发送模板消息
 * 链接:http://yn.zipscloud.com/api.php/v1/sendtemplatemessage
 */
function sendTemplateMessage($title,$time,$url,$tagId){
    $data=array(
        'first'=>array('value'=>urlencode("您好，公司有新内部公告，请点击查看!"),'color'=>"#4a7d3b"),
        'keyword1'=>array('value'=>urlencode($title),'color'=>'#4a7d3b'),
        'keyword2'=>array('value'=>urlencode($time),'color'=>'#4a7d3b'),
        'remark'=>array('value'=>urlencode('感谢您的使用。'),'color'=>'#4a7d3b'),
    );
    $toUsers=json_decode(getOpenIdsByTagId($tagId),true)['data']['openid'];
    $accessToken=get_weixin_access_token();
    $urlAPI="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$accessToken}";
    $result='';
    foreach ($toUsers as $toUser){
        $template = array(
            'touser' => $toUser,
            'template_id' => "D6A3SHR2bOEA-akkI4zVMBYtAKhpIPVFa9imyKVSJ90",
            'url' => $url,
            "topcolor"=>"#FF0000",
            'data' => $data
        );
        $arr=json_decode(curl_post_raw($urlAPI,urldecode(json_encode($template))),true);
        $errcode=$arr['errcode'];
        if($errcode===0){
            $result.=$toUser.':'.'发送成功'.'-';
        }else{
            $result.=$toUser.':'.'发送失败,错误代码【'.$errcode.'】-';
        }

    }
    return  $result;
}

/*
    字符串GBK转码为UTF-8，数字转换为数字。
*/
function ct2($s){
    if(is_numeric($s)) {
        return intval($s);
    } else {
        return iconv("GBK","UTF-8",$s);
    }
}
/*
    批量处理gbk->utf-8
*/
function icon_to_utf8($s) {
    if(is_array($s)) {
        foreach($s as $key => $val) {
            $s[$key] = icon_to_utf8($val);
        }
    } else {
        $s = ct2($s);
    }
    return $s;
}

/**
 * 表格排序
 * @param $title
 * @param $name
 */
function sort_by($title,$name,$reset=''){
    $order='ASC';
    $color='style="color:#000"';
    if(!empty($reset)){
        if(isset($reset[$name])){
            $order=$reset[$name];
            $color='';
        }
    }
    $html='<input type="hidden" class="sort" name="order[]" sort="'.$name.'" value="'.$name.'|'.$order.'">';
    if($order=='ASC'){
        $btn_text='<i class="icon icon-chevron-up" '.$color.'></i>';
    }else if($order=='DESC'){
        $btn_text='<i class="icon icon-chevron-down" '.$color.'></i>';
    }
    $html.='<button class="btn btn-sm btn-link order-th-btn" sort="'.$name.'" type="button">'.$btn_text.'</button>';
    echo $title.$html;
}

include EXTEND_PATH ."cms/Cms.php";
include EXTEND_PATH ."data/Data.php";
include EXTEND_PATH ."html/Html.php";
include EXTEND_PATH ."html/Css.php";
include EXTEND_PATH ."html/Js.php";
include EXTEND_PATH ."html/WUI.php";
include EXTEND_PATH ."weicode/WeiCode.php";
include EXTEND_PATH ."excel/Excel.php";