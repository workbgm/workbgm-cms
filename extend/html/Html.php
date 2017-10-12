<?php
namespace app;
/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/5/31
 * Time: 上午8:24
 * For:
 */
class Html
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
    static public function input($name, $value = "", $attrib = "",$placeholder="")
    {
        $id = "id='$name'";
        if(strpos($attrib, 'id=') !== false) $id = '';
        $value = str_replace("'", '&#039;', $value);
        return "<input type='text' placeholder='".$placeholder."' class=\"form-control\" name='$name' {$id} value='$value' $attrib />\n";
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
        return "<input type='password' class=\"form-control\" name='$name' id='$name' value='$value' $attrib />\n";
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
        return "<textarea class=\"form-control\" name='$name' id='$name' $attrib>$value</textarea>\n";
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
        return "<input type='file' name='$name'  $attrib />\n";
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