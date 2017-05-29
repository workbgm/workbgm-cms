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
    return site_index().'/entry/handler?m='.$info[0].'&ac='.$info[1].'&t=admin&'.http_build_query($args);
}

/**
 * 生成前台访问路径
 * @param $url
 * @param array $args
 * @return string
 */
function web_url($url,$args=[]){
    $info=explode('.',$url);
    return site_index().'/module/entry/handler?m='.$info[0].'&ac='.$info[1].'&t=web&'.http_build_query($args);
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
    return date($f,$u);
}

/**
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

function getTableComment($tablename,$database=null){
    if(empty($database)){
        $database=Config::get('database.database');
    }
    $sql_str = "show table status where name='"
        . $tablename . "'";
    return Db::query($sql_str)[0]['Comment'];
}
/**
 * 获取分类所有子分类
 * @param int $cid 分类ID
 * @return array|bool
 */
function get_category_children($cid)
{
    if (empty($cid)) {
        return false;
    }

    $children = Db::name('category')->where(['path' => ['like', "%,{$cid},%"]])->select();

    return array2tree($children);
}

/**
 * 获取导航一级菜单
 * @return [array] [id,name]
 */
function get_category_children_1_level()
{
    return Db::name('category')->where(['pid' => ['eq', "0"]])->select();
}

function get_category_bycid($cid)
{
    if (empty($cid)) {
        return false;
    }

    return Db::name('category')->find($cid);
}

/**
 * 根据分类ID获取文章列表（包括子分类）
 * @param int $cid 分类ID
 * @param int $limit 显示条数
 * @param array $where 查询条件
 * @param array $order 排序
 * @param array $filed 查询字段
 * @return bool|false|PDOStatement|string|\think\Collection
 */
function get_articles_by_cid($cid, $limit = 10, $where = [], $order = [], $filed = [])
{
    if (empty($cid)) {
        return false;
    }

    $ids = Db::name('category')->where(['path' => ['like', "%,{$cid},%"]])->column('id');
    $ids = (!empty($ids) && is_array($ids)) ? implode(',', $ids) . ',' . $cid : $cid;

    $fileds = array_merge(['id', 'cid', 'title', 'author', 'introduction', 'thumb', 'reading', 'publish_time'], (array)$filed);
    $map = array_merge(['cid' => ['IN', $ids], 'status' => 1, 'publish_time' => ['<= time', date('Y-m-d H:i:s')]], (array)$where);
    $sort = array_merge(['is_top' => 'DESC', 'sort' => 'DESC', 'publish_time' => 'DESC'], (array)$order);

    $article_list = Db::name('article')->where($map)->field($fileds)->order($sort)->limit($limit)->select();

    return $article_list;
}

/**
 * 根据分类ID获取文章列表，带分页（包括子分类）
 * @param int $cid 分类ID
 * @param int $page_size 每页显示条数
 * @param array $where 查询条件
 * @param array $order 排序
 * @param array $filed 查询字段
 * @return bool|\think\paginator\Collection
 */
function get_articles_by_cid_paged($cid, $page_size = 15, $where = [], $order = [], $filed = [])
{
    if (empty($cid)) {
        return false;
    }

    $ids = Db::name('category')->where(['path' => ['like', "%,{$cid},%"]])->column('id');
    $ids = (!empty($ids) && is_array($ids)) ? implode(',', $ids) . ',' . $cid : $cid;

    $fileds = array_merge(['id', 'cid', 'title', 'introduction', 'thumb', 'reading', 'publish_time'], (array)$filed);
    $map = array_merge(['cid' => ['IN', $ids], 'status' => 1, 'publish_time' => ['<= time', date('Y-m-d H:i:s')]], (array)$where);
    $sort = array_merge(['is_top' => 'DESC', 'sort' => 'DESC', 'publish_time' => 'DESC'], (array)$order);

    $article_list = Db::name('article')->where($map)->field($fileds)->order($sort)->paginate($page_size);

    return $article_list;
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
 */
function map($group)
{
    $options = Db::name('map')->where(['group' => ['eq', $group]])->select();
    return $options;
}

//function hook($hook, $fun)
//{
//    $hook = 'app\\plugins\\' . $hook;
//    \app\common\Hook::call($hook, $fun);
//}

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
        $require_html = 'lay-verify="required"';
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



class html
{
    /**
     * 生成title标签。
     * Create the title tag.
     *
     * @param  mixed $title
     * @static
     * @access public
     * @return string.
     */
    public static function title($title)
    {
        return "<title>$title</title>\n";
    }

    /**
     * 生成meta标签。
     * Create a meta.
     *
     * @param mixed $name   the meta name
     * @param mixed $value  the meta value
     * @static
     * @access public
     * @return string
     */
    public static function meta($name, $value)
    {
        if($name == 'charset') return "<meta charset='$value'>\n";
        return "<meta name='$name' content='$value'>\n";
    }

    /**
     * 生成favicon标签。
     * Create favicon tag
     *
     * @param mixed $url  the url of the icon.
     * @static
     * @access public
     * @return string
     */
    public static function favicon($url)
    {
        return "<link rel='icon' href='$url' type='image/x-icon' />\n<link rel='shortcut icon' href='$url' type='image/x-icon' />\n";
    }

    /**
     * 创建图标。
     * Create icon.
     *
     * @param name $name  the name of the icon.
     * @param cssClass $class  the extra css class of the icon.
     * @static
     * @access public
     * @return string
     */
    public static function icon($name, $class = '')
    {
        $class = empty($class) ? ('icon-' . $name) : ('icon-' . $name . ' ' . $class);
        return "<i class='$class'></i>";

    }

    /**
     * 生成rss标签。
     * Create the rss tag.
     *
     * @param  string $url
     * @param  string $title
     * @static
     * @access public
     * @return string
     */
    public static function rss($url, $title = '')
    {
        return "<link href='$url' title='$title' type='application/rss+xml' rel='alternate' />";
    }

    /**
     * 生成超链接。
     * Create tags like <a href="">text</a>
     *
     * @param  string $href      the link url.
     * @param  string $title     the link title.
     * @param  string $misc      other params.
     * @param  string $newline
     * @static
     * @access public
     * @return string
     */
    static public function a($href = '', $title = '', $misc = '', $newline = true)
    {
        global $config;

        if(empty($title)) $title = $href;
        $newline = $newline ? "\n" : '';
        $href = helper::processOnlyBodyParam($href);

        return "<a href='$href' $misc>$title</a>$newline";
    }

    /**
     * 生成邮件链接。
     * Create tags like <a href="mailto:">text</a>
     *
     * @param  string $mail      the email address
     * @param  string $title     the email title.
     * @static
     * @access public
     * @return string
     */
    static public function mailto($mail = '', $title = '')
    {
        $html   = '';
        $mails  = explode(',', $mail);
        $titles = explode(',', $title);
        foreach($mails as $key => $m)
        {
            if(empty($m)) continue;
            $t     = empty($titles[$key]) ? $mail : $titles[$key];
            $html .= " <a href='mailto:$m'>$t</a>";
        }
        return $html;
    }

    /**
     * 生成select标签。
     * Create tags like "<select><option></option></select>"
     *
     * @param  string $name          the name of the select tag.
     * @param  array  $options       the array to create select tag from.
     * @param  string $selectedItems the item(s) to be selected, can like item1,item2.
     * @param  string $attrib        other params such as multiple, size and style.
     * @param  string $append        adjust if add options[$selectedItems].
     * @static
     * @access public
     * @return string
     */
    static public function select($name = '', $options = array(), $selectedItems = "", $attrib = "", $append = false)
    {
        $options = (array)($options);
        if($append and !isset($options[$selectedItems])) $options[$selectedItems] = $selectedItems;
        if(!is_array($options) or empty($options)) return false;

        /* The begin. */
        $id = $name;
        if(strpos($name, '[') !== false) $id = trim(str_replace(']', '', str_replace('[', '', $name)));
        $id = "id='{$id}'";
        if(strpos($attrib, 'id=') !== false) $id = '';

        $string = "<select name='$name' {$id} $attrib>\n";

        /* The options. */
        if(is_array($selectedItems)) $selectedItems = implode(',', $selectedItems);
        $selectedItems = ",$selectedItems,";
        foreach($options as $key => $value)
        {
            $key      = str_replace('item', '', $key);
            $selected = strpos($selectedItems, ",$key,") !== false ? " selected='selected'" : '';
            $string  .= "<option value='$key'$selected>$value</option>\n";
        }

        /* End. */
        return $string .= "</select>\n";
    }

    /**
     * 生成带optgroup标签的select标签。
     * Create select with optgroup.
     *
     * @param  string $name          the name of the select tag.
     * @param  array  $groups        the option groups.
     * @param  string $selectedItems the item(s) to be selected, can like item1,item2.
     * @param  string $attrib        other params such as multiple, size and style.
     * @static
     * @access public
     * @return string
     */
    static public function selectGroup($name = '', $groups = array(), $selectedItems = "", $attrib = "")
    {
        if(!is_array($groups) or empty($groups)) return false;

        /* The begin. */
        $id = $name;
        if(strpos($name, '[') !== false) $id = trim(str_replace(']', '', str_replace('[', '', $name)));
        $string = "<select name='$name' id='$id' $attrib>\n";

        /* The options. */
        $selectedItems = ",$selectedItems,";
        foreach($groups as $groupName => $options)
        {
            $string .= "<optgroup label='$groupName'>\n";
            foreach($options as $key => $value)
            {
                $key      = str_replace('item', '', $key);
                $selected = strpos($selectedItems, ",$key,") !== false ? " selected='selected'" : '';
                $string  .= "<option value='$key'$selected>$value</option>\n";
            }
            $string .= "</optgroup>\n";
        }

        /* End. */
        return $string .= "</select>\n";
    }

    /**
     * 生成单选按钮。
     * Create tags like "<input type='radio' />"
     *
     * @param  string $name       the name of the radio tag.
     * @param  array  $options    the array to create radio tag from.
     * @param  string $checked    the value to checked by default.
     * @param  string $attrib     other attribs.
     * @param  string $type       inline or block
     * @static
     * @access public
     * @return string
     */
    static public function radio($name = '', $options = array(), $checked = '', $attrib = '', $type = 'inline')
    {
        $options = (array)($options);
        if(!is_array($options) or empty($options)) return false;
        $isBlock = $type == 'block';

        $string  = '';
        foreach($options as $key => $value)
        {
            if($isBlock) $string .= "<div class='radio'><label>";
            else $string .= "<label class='radio-inline'>";
            $string .= "<input type='radio' name='$name' value='$key' ";
            $string .= ($key == $checked) ? " checked ='checked'" : "";
            $string .= $attrib;
            $string .= " id='$name$key' /> ";
            $string .= $value;
            if($isBlock) $string .= '</label></div>';
            else $string .= '</label>';
        }
        return $string;
    }

    /**
     * 生成多选按钮。
     * Create tags like "<input type='checkbox' />"
     *
     * @param  string $name      the name of the checkbox tag.
     * @param  array  $options   the array to create checkbox tag from.
     * @param  string $checked   the value to checked by default, can be item1,item2
     * @param  string $attrib    other attribs.
     * @param  string $type       inline or block
     * @static
     * @access public
     * @return string
     */
    static public function checkbox($name, $options, $checked = "", $attrib = "", $type = 'inline')
    {
        $options = (array)($options);
        if(!is_array($options) or empty($options)) return false;

        if(is_array($checked)) $checked = implode(',', $checked);
        $string  = '';
        $checked = ",$checked,";
        $isBlock = $type == 'block';

        foreach($options as $key => $value)
        {
            $key     = str_replace('item', '', $key);
            if($isBlock) $string .= "<div class='checkbox'><label>";
            else $string .= "<label class='checkbox-inline'>";
            $string .= "<input type='checkbox' name='{$name}[]' value='$key' ";
            $string .= (strpos($checked, ",$key,") !== false) ? " checked ='checked'" : "";
            $string .= $attrib;
            $string .= " id='$name$key' /> ";
            $string .= $value;
            if($isBlock) $string .= '</label></div>';
            else $string .= '</label>';
        }
        return $string;
    }

    /**
     * 生成input输入标签。
     * Create tags like "<input type='text' />"
     *
     * @param  string $name     the name of the text input tag.
     * @param  string $value    the default value.
     * @param  string $attrib   other attribs.
     * @static
     * @access public
     * @return string
     */
    static public function input($name, $value = "", $attrib = "")
    {
        $id = "id='$name'";
        if(strpos($attrib, 'id=') !== false) $id = '';
        $value = str_replace("'", '&#039;', $value);
        return "<input type='text' name='$name' {$id} value='$value' $attrib />\n";
    }

    /**
     * 生成隐藏的提交标签。
     * Create tags like "<input type='hidden' />"
     *
     * @param  string $name     the name of the text input tag.
     * @param  string $value    the default value.
     * @param  string $attrib   other attribs.
     * @static
     * @access public
     * @return string
     */
    static public function hidden($name, $value = "", $attrib = "")
    {
        return "<input type='hidden' name='$name' id='$name' value='$value' $attrib />\n";
    }

    /**
     * 创建密码输入框。
     * Create tags like "<input type='password' />"
     *
     * @param  string $name     the name of the text input tag.
     * @param  string $value    the default value.
     * @param  string $attrib   other attribs.
     * @static
     * @access public
     * @return string
     */
    static public function password($name, $value = "", $attrib = "")
    {
        return "<input type='password' name='$name' id='$name' value='$value' $attrib />\n";
    }

    /**
     * 创建编辑器标签。
     * Create tags like "<textarea></textarea>"
     *
     * @param  string $name      the name of the textarea tag.
     * @param  string $value     the default value of the textarea tag.
     * @param  string $attrib    other attribs.
     * @static
     * @access public
     * @return string
     */
    static public function textarea($name, $value = "", $attrib = "")
    {
        return "<textarea name='$name' id='$name' $attrib>$value</textarea>\n";
    }

    /**
     * 创建文件上传标签。
     * Create tags like "<input type='file' />".
     *
     * @param  string $name      the name of the file name.
     * @param  string $attrib    other attribs.
     * @static
     * @access public
     * @return string
     */
    static public function file($name, $attrib = "")
    {
        return "<input type='file' name='$name' id='$name' $attrib />\n";
    }

    /**
     * 创建日期输入框。
     * Create date picker.
     *
     * @param  string $name     the name of the text input tag.
     * @param  string $value    the default value.
     * @param  string $options
     * @param  string $attrib
     * @static
     * @access public
     * @return void
     */
    static public function date($name, $value = "", $options = '', $attrib = '')
    {
        $html = "<div class='input-append date date-picker' {$options}>";
        $html .= "<input type='text' name='{$name}' id='$name' value='$value' {$attrib} />\n";
        $html .= "<span class='add-on'><button class='btn btn-default' type='button'><i class='icon-calendar'></i></button></span></div>";
        return $html;
    }

    /**
     * 创建日期时间输入框。
     * Create dateTime picker.
     *
     * @param  string $name     the name of the text input tag.
     * @param  string $value    the default value.
     * @param  string $options
     * @param  string $attrib
     * @static
     * @access public
     * @return void
     */
    static public function dateTime($name, $value = "", $options = '', $attrib = '')
    {
        $html = "<div class='input-append date time-picker' {$options}>";
        $html .= "<input type='text' name='{$name}' id='$name' value='$value' {$attrib} />\n";
        $html .= "<span class='add-on'><button class='btn btn-default' type='button'><i class='icon-calendar'></i></button></span></div>";
        return $html;
    }

    /**
     * 创建img标签。
     * create tags like "<img src='' />".
     *
     * @param string $name      the name of the image name.
     * @param string $attrib    other attribs.
     * @static
     * @access public
     * @return string
     */
    static public function image($image, $attrib = '')
    {
        return "<img src='$image' $attrib />\n";
    }

    /**
     * 创建提交按钮。
     * Create submit button.
     *
     * @param  string $label    the label of the button
     * @param  string $class    the class of the button
     * @param  string $misc     other params
     * @static
     * @access public
     * @return string the submit button tag.
     */
    public static function submitButton($label = '', $class = 'btn btn-primary', $misc = '')
    {
        return " <button type='submit' id='submit' class='$class' $misc>$label</button>";
    }

    /**
     * 创建重置按钮。
     * Create reset button.
     *
     * @param  string $label
     * @param  string $class
     * @static
     * @access public
     * @return string the reset button tag.
     */
    public static function resetButton($label = '', $class = '')
    {
        return " <button type='reset' id='reset' class='btn btn-reset $class'>$label</button>";
    }

    /**
     * 创建返回按钮。
     * Back button.
     *
     * @param  string $label
     * @param  string $misc
     * @static
     * @access public
     * @return string the back button tag.
     */
    public static function backButton($label = '', $misc = '')
    {
        if(helper::inOnlyBodyMode()) return false;
        return  "<a href='javascript:history.go(-1);' class='btn btn-back' $misc>{$label}</a>";
    }

    /**
     * 创建通用按钮。
     * Create common button.
     *
     * @param  string $label the label of the button
     * @param  string $class the class of the button
     * @param  string $misc  other params
     * @param  string $icon  icon
     * @static
     * @access public
     * @return string the common button tag.
     */
    public static function commonButton($label = '', $class = 'btn btn-default', $misc = '', $icon = '')
    {
        if($icon) $label = "<i class='icon-" . $icon . "'></i> " . $label;
        return " <button type='button' class='$class' $misc>$label</button>";
    }

    /**
     * 创建一个带有链接的按钮。
     * create a button, when click, go to a link.
     *
     * @param  string $label    the link title
     * @param  string $link     the link url
     * @param  string $class    the link style
     * @param  string $misc     other params
     * @param  string $target   the target window
     * @static
     * @access public
     * @return string
     */
    public static function linkButton($label = '', $link = '', $class='btn btn-default', $misc = '', $target = 'self')
    {
        return " <button type='button' class='$class' $misc onclick='$target.location.href=\"$link\"'>$label</button>";
    }

    /**
     * 创建关闭模态框按钮。
     * Create a button to close.
     *
     * @static
     * @access public
     * @return string
     */
    public static function closeButton()
    {
        return "<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>&times;</button>";
    }

    /**
     * 打印星星。
     * Print the star images.
     *
     * @param  float    $stars 0 1 1.5 2 2.5 3 3.5 4 4.5 5
     * @access public
     * @static
     * @access public
     * @return void
     */
    public static function printStars($stars)
    {
        $redStars   = 0;
        $halfStars  = 0;
        $whiteStars = 5;
        if($stars)
        {
            /* If stars more than max, then fix it. */
            if($stars > $whiteStars) $stars = $whiteStars;

            $redStars  = floor($stars);
            $halfStars = $stars - $redStars ? 1 : 0;
            $whiteStars = 5 - ceil($stars);
        }
        echo "<span class='stars-list'>";
        for($i = 1; $i <= $redStars;   $i ++) echo "<i class='icon-star'></i>";
        for($i = 1; $i <= $halfStars;  $i ++) echo "<i class='icon-star-half-full'></i>";
        for($i = 1; $i <= $whiteStars; $i ++) echo "<i class='icon-star-empty'></i>";
        echo '</span>';
    }
}

/**
 * JS类。
 * JS class.
 *
 * @package front
 */
class js
{
    /**
     * 引入一个js文件。
     * Import a js file.
     *
     * @param  string $url
     * @param  string $ieParam    like 'lt IE 9'
     * @static
     * @access public
     * @return string
     */
    public static function import($url, $ieParam = '')
    {
        global $config;
        $pathInfo = parse_url($url);
        $mark  = !empty($pathInfo['query']) ? '&' : '?';

        $hasLimit = ($ieParam and stripos($ieParam, 'ie') !== false);
        if($hasLimit) echo "<!--[if $ieParam]>\n";
        echo "<script src='$url{$mark}v={$config->version}' type='text/javascript'></script>\n";
        if($hasLimit) echo "<![endif]-->\n";
    }

    /**
     * 开始输出js。
     * The start of javascript.
     *
     * @param  bool   $full
     * @static
     * @access public
     * @return string
     */
    static public function start($full = true)
    {
        if($full) return "<html><meta charset='utf-8'/><style>body{background:white}</style><script>";
        return "<script language='Javascript'>";
    }

    /**
     * 结束输出js。
     * The end of javascript.
     *
     * @param  bool    $newline
     * @static
     * @access public
     * @return void
     */
    static public function end($newline = true)
    {
        if($newline) return "\n</script>\n";
        return "</script>\n";
    }

    /**
     * 显示一个警告框。
     * Show a alert box.
     *
     * @param  string $message
     * @static
     * @access public
     * @return string
     */
    static public function alert($message = '')
    {
        return self::start() . "alert('" . $message . "')" . self::end() . self::resetForm();
    }

    /**
     * 关闭浏览器窗口。
     * Close window
     *
     * @static
     * @access public
     * @return void
     */
    static public function close()
    {
        return self::start() . "window.close()" . self::end();
    }

    /**
     * 显示错误信息。
     * Show error info.
     *
     * @param  string|array $message
     * @static
     * @access public
     * @return string
     */
    static public function error($message)
    {
        $alertMessage = '';
        if(is_array($message))
        {
            foreach($message as $item)
            {
                is_array($item) ? $alertMessage .= join('\n', $item) . '\n' : $alertMessage .= $item . '\n';
            }
        }
        else
        {
            $alertMessage = $message;
        }
        return self::alert($alertMessage);
    }

    /**
     * 重置禁用的提交按钮。
     * Reset the submit form.
     *
     * @static
     * @access public
     * @return string
     */
    static public function resetForm()
    {
        return self::start() . 'if(window.parent) window.parent.document.body.click();' . self::end();
    }

    /**
     * 显示一个确认框，点击确定跳转到$okURL，点击取消跳转到$cancelURL。
     * show a confirm box, press ok go to okURL, else go to cancleURL.
     *
     * @param  string $message      显示的内容。              the text to be showed.
     * @param  string $okURL        点击确定后跳转的地址。    the url to go to when press 'ok'.
     * @param  string $cancleURL    点击取消后跳转的地址。    the url to go to when press 'cancle'.
     * @param  string $okTarget     点击确定后跳转的target。  the target to go to when press 'ok'.
     * @param  string $cancleTarget 点击取消后跳转的target。  the target to go to when press 'cancle'.
     * @static
     * @access public
     * @return string
     */
    static public function confirm($message = '', $okURL = '', $cancleURL = '', $okTarget = "self", $cancleTarget = "self")
    {
        $js = self::start();

        $confirmAction = '';
        if(strtolower($okURL) == "back")
        {
            $confirmAction = "history.back(-1);";
        }
        elseif(!empty($okURL))
        {
            $confirmAction = "$okTarget.location = '$okURL';";
        }

        $cancleAction = '';
        if(strtolower($cancleURL) == "back")
        {
            $cancleAction = "history.back(-1);";
        }
        elseif(!empty($cancleURL))
        {
            $cancleAction = "$cancleTarget.location = '$cancleURL';";
        }

        $js .= <<<EOT
if(confirm("$message"))
{
    $confirmAction
}
else
{
    $cancleAction
}
EOT;
        $js .= self::end();
        return $js;
    }

    /**
     * $target会跳转到$url指定的地址。
     * change the location of the $target window to the $URL.
     *
     * @param   string $url    the url will go to.
     * @param   string $target the target of the url.
     * @static
     * @access  public
     * @return  string the javascript string.
     */
    static public function locate($url, $target = "self")
    {
        /* If the url if empty, goto the home page. */
        if(!$url)
        {
            global $config;
            $url = $config->webRoot;
        }

        $js  = self::start();
        if(strtolower($url) == "back")
        {
            $js .= "history.back(-1);\n";
        }
        else
        {
            $js .= "$target.location='$url';\n";
        }
        return $js . self::end();
    }

    /**
     * 关闭当前窗口。
     * Close current window.
     *
     * @static
     * @access public
     * @return string
     */
    static public function closeWindow()
    {
        return self::start(). "window.close();" . self::end();
    }

    /**
     * 经过一段时间后跳转到指定的页面。
     * Goto a page after a timer.
     *
     * @param   string $url    the url will go to.
     * @param   string $target the target of the url.
     * @param   int    $time   the timer, msec.
     * @static
     * @access  public
     * @return  string the javascript string.
     */
    static public function refresh($url, $target = "self", $time = 3000)
    {
        $js  = self::start();
        $js .= "setTimeout(\"$target.location='$url'\", $time);";
        $js .= self::end();
        return $js;
    }

    /**
     * 重新加载窗口。
     * Reload a window.
     *
     * @param   string $window the window to reload.
     * @static
     * @access  public
     * @return  string the javascript string.
     */
    static public function reload($window = 'self')
    {
        $js  = self::start();
        $js .= "$window.location.reload(true);\n";
        $js .= self::end();
        return $js;
    }

    /**
     * 用Javascript关闭colorbox弹出框。
     * Close colorbox in javascript.
     * This is a obsolete method, you can use 'closeModal' instead.
     *
     * @param  string $window
     * @static
     * @access public
     * @return string
     */
    static public function closeColorbox($window = 'self')
    {
        return self::closeModal($window);
    }

    /**
     * 用Javascript关闭模态框。
     * Close modal with javascript.
     *
     * @param  string $window
     * @param  string $location
     * @param  string $callback
     * @static
     * @access public
     * @return string
     */
    static public function closeModal($window = 'self', $location = 'this', $callback = 'null')
    {
        $js  = self::start();
        $js .= "if($window.location.href == self.location.href){ $window.window.close();}";
        $js .= "else{ $window.$.cookie('selfClose', 1);$window.$.closeModal($callback, '$location');}";
        $js .= self::end();
        return $js;
    }



    /**
     * 执行js代码。
     * Execute some js code.
     *
     * @param string $code
     * @static
     * @access public
     * @return string
     */
    static public function execute($code)
    {
        $js = self::start($full = false);
        $js .= $code;
        $js .= self::end();
        echo $js;
    }

    /**
     * 设置Javascript变量值。
     * Set js value.
     *
     * @param  string   $key
     * @param  mix      $value
     * @static
     * @access public
     * @return string
     */
    static public function set($key, $value)
    {
        global $config;
        $prefix = (isset($config->framework->jsWithPrefix) and $config->framework->jsWithPrefix == false) ? '' : 'v.';

        static $viewOBJOut;
        $js  = self::start(false);
        if(!$viewOBJOut and $prefix)
        {
            $js .= 'if(typeof(v) != "object") v = {};';
            $viewOBJOut = true;
        }

        if(is_numeric($value))
        {
            $js .= "{$prefix}{$key} = {$value};";
        }
        elseif(is_array($value) or is_object($value) or is_string($value))
        {
            /* Fix for auto-complete when user is number.*/
            if(is_array($value) or is_object($value))
            {
                $value = (array)$value;
                foreach($value as $k => $v)
                {
                    if(is_numeric($v)) $value[$k] = (string)$v;
                }
            }

            $value = json_encode($value);
            $js .= "{$prefix}{$key} = {$value};";
        }
        elseif(is_bool($value))
        {
            $value = $value ? 'true' : 'false';
            $js .= "{$prefix}{$key} = $value;";
        }
        else
        {
            $value = addslashes($value);
            $js .= "{$prefix}{$key} = '{$value};'";
        }
        $js .= self::end($newline = false);
        echo $js;
    }
}

class css
{
    /**
     * 引入css文件。
     * Import a css file.
     *
     * @param  string $url
     * @access public
     * @return void
     */
    public static function import($url, $attrib = '')
    {
        global $config;
        if(!empty($attrib)) $attrib = ' ' . $attrib;
        echo "<link rel='stylesheet' href='$url?v={$config->version}' type='text/css' media='screen'{$attrib} />\n";
    }

    /**
     * 打印css代码。
     * Print a css code.
     *
     * @param  string    $css
     * @static
     * @access public
     * @return void
     */
    public static function internal($css)
    {
        echo "<style>$css</style>";
    }
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

include EXTEND_PATH ."data/Data.php";