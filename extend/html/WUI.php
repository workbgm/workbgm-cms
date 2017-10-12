<?php

namespace app;
require_once "WUIData.php";

/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/5/31
 * Time: 上午8:41
 * For:
 */
class WUI extends Html
{

    //组件start M开头
    const M_TEXTAREA = 1;
    const M_UEDITOR = 2;
    const M_FILE = 3;
    const M_IMAGE = 4;
    const M_SELECT = 5;
    const M_IMAGESELECT = 6;
    const M_NUMBER = 7;
    const M_BUTTON = 8;
    const M_INPUT = 9;
    const M_DATE = 10;
    const M_DATETIME = 11;
    const M_MSELECT = 12;
    //组件end
    //组件类型  start T开头
    const T_PRIMARY = 1;
    const T_SUCCESS = 2;
    const T_WARNING = 3;
    const T_DANGER = 4;
    const T_INFO = 5;
    //组件类型  end


    protected $html = '';
    private $form_class = 'form-horizontal';

    /**
     * 实现tab
     * @param $tabArray  li(href,active,name)
     * @return $this
     */

    public function addUL($tabArray)
    {
        $tab = '<ul class="nav nav-tabs">';

        foreach ($tabArray as $tab_) {
            $li = '<li ' . ($tab_['active'] ? 'class="active"' : '') . '><a ' . ($tab_['href'] ? 'href="' . $tab_['href'] . '"' : '') . '  >' . $tab_['name'] . '</a></li>';
            $tab .= $li;
        }
        $tab .= '</ul>';
        $this->html .= $tab;
        return $this;
    }

    /**
     * 实现tab
     * @param $tabArray  li(href,active,name)
     * @return $this
     */

    public function addULBegin($tabArray)
    {
        $tab = '<div class="container-fluid"><ul class="nav nav-secondary">';
        $a = 'data-tab';
        $b = '#';
        $tab_content = '<div class="tab-content with-padding">';
        foreach ($tabArray as $tab_) {
            $li = '<li ' . ($tab_['active'] ? 'class="active"' : '') . '><a ' . ($tab_['href'] ? 'href="' . $b . $tab_['href'] . '"' : '') . '  ' . $a . '>' . $tab_['name'] . '</a></li>';
            $tab .= $li;
        }
        $tab .= '</ul>' . $tab_content;
        $this->html .= $tab;
        return $this;
    }

    public function addULEnd()
    {
        $this->html .= '</div></div>';
        return $this;
    }

    protected function getTab($id, $tabArray)
    {
        $tab = null;
        foreach ($tabArray as $tab_) {
            if ($tab_['href'] == $id) {
                $tab = $tab_;
                return $tab;
            }
        }
        return $tab;
    }

    public function addTabPanelBegin($id, $tabArray)
    {
        $tab_content = '';
        $tab_ = self::getTab($id, $tabArray);
        if ($tab_) {
            $tab_content .= '<div class="tab-pane fade ' . ($tab_['active'] ? 'active in ' : '') . '" id="' . $tab_['href'] . '">';
            $this->html .= $tab_content;
        }
        return $this;

    }

    public function addTabPanelEnd()
    {
        $this->html .= '</div>';
        return $this;

    }


    public function addFormBegin($action, $class = "form-horizontal")
    {
        $form = '<div class="container-fluid">';
        $form .= '<form class="' . $class . '" action="' . $action . '" method="post">';
        $this->html .= $form;
        $this->form_class = $class;
        return $this;
    }

    public function addFormEnd()
    {
        $form = '</form></div>';
        $this->html .= $form;
        return $this;
    }

    public function addHtml($html = '')
    {
        $this->html .= $html;
        return $this;
    }

    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param $label
     * @param $type  input textarea hidden
     * @param  string $name the name of the textarea tag.
     * @param  string $value the default value of the $type tag.
     * @param  string $attrib other attribs.
     */
    public function addFormGroup($label, $type, $name, $value = "", $attrib = "", $options = array(), $placeholder = "", $class = '', $icon = '')
    {
        $form_group = '<div class="form-group" id="' . $name . '_group">';
        if ($this->form_class == 'form-horizontal') {
            $form_group .= '<label class="col-sm-2 col-md-2">' . $label . '</label>';
            $form_group .= '<div class="col-md-10 col-sm-10">';
        } else if ($this->form_class == 'form-inline') {
            $form_group .= '<label>' . $label . '</label>';
        }
        switch ($type) {
            case WUI::M_INPUT:
            case 'input':
                $form_group .= parent::input($name, $value, $attrib, $placeholder);
                break;
            case WUI::M_TEXTAREA:
            case 'textarea':
                $form_group .= parent::textarea($name, $value, $attrib);
                break;
            case WUI::M_UEDITOR:
            case 'ueditor':
                $form_group .= self::ueditor($name, $value, $attrib);
                break;
            case WUI::M_FILE:
            case 'file':
                $form_group .= parent::file($name, $value, $attrib);
                break;
            case WUI::M_IMAGE:
            case 'image':
                $form_group .= self::image($name, $value, $attrib);
                break;
            case WUI::M_SELECT:
            case 'select':
                $form_group .= self::map_select($name, $options, $value, $attrib);
                break;
            case WUI::M_MSELECT:
            case 'mselect':
                $attrib = $attrib . ' multiple';
                $form_group .= self::map_select($name, $options, $value, $attrib);
                break;
            case WUI::M_IMAGESELECT:
            case 'imageSelect':
                $form_group .= self::imageSelect($name, $value, $attrib);
                break;
            case WUI::M_NUMBER:
            case 'number':
                $form_group .= self::number($name, $value, $attrib, $placeholder);
                break;
            case WUI::M_BUTTON:
            case 'button':
                $form_group .= self::button($name, $label, $class, $attrib, $icon);
                break;
            case WUI::M_DATE:
            case 'date':
                $form_group .= self::date($name, $value, $attrib, $placeholder);
                break;
            case WUI::M_DATETIME:
            case 'datetime':
                $form_group .= self::dateTime($name, $value, $attrib, $placeholder);
                break;
        }
        if ($this->form_class == 'form-horizontal') {
            $form_group .= '</div></div>';
        } else if ($this->form_class == 'form-inline') {
            $form_group .= '</div>';
        }

        $this->html .= $form_group;
        return $this;
    }

    /**
     * 创建编辑器标签。
     * Create tags like "<textarea></textarea>"
     *
     * @param  string $name the name of the textarea tag.
     * @param  string $value the default value of the textarea tag.
     * @param  string $attrib other attribs.
     * @static
     * @access public
     * @return string
     */
    static public function ueditor($name, $value = "", $attrib = "")
    {
        $script = "<script>$(document).ready(function () {var ue = UE.getEditor('" . $name . "');ue.addListener('selectionchange',function(){var html = ue.getContent();$('#" . $name . "').val(html);});})</script>";
        return "<textarea  name='$name' id='$name' $attrib>$value</textarea>\n" . $script;
    }

    /**
     * 创建数字组件
     * @param $name
     * @param string $value
     * @param string $attrib
     * @param string $placeholder
     * @return string
     */
    static public function number($name, $value = "", $attrib = "", $placeholder = "")
    {
        $id = "id='$name'";
        if (strpos($attrib, 'id=') !== false) $id = '';
        $value = str_replace("'", '&#039;', $value);
        return "<input placeholder='" . $placeholder . "' type='number' class=\"form-control\" name='$name' {$id} value='$value' $attrib />\n";
    }

    /**
     * 创建图片上传组件
     * @param $name
     * @param string $value
     * @param string $attrib
     * @return string
     */
    static public function image($name, $value = "", $attrib = "")
    {
        return '<div class="input-group"><input class="form-control" type="text" name="' . $name . '" value="' . $value . '" readonly="">' . '<span class="input-group-addon"><a class="icon-eye-open"  name="image-see" data-name="' . $name . '">预览&下载</a></span></div>' . parent::file('file', 'imge="' . $name . '"');
    }

    /**
     * 创建图片选择组件
     * @param $name
     * @param string $value
     * @param string $attrib
     * @return string
     */
    static public function imageSelect($name, $value = "", $attrib = "")
    {
        return '<input class="form-control" type="text" name="' . $name . '" value="' . $value . '" readonly=""><button type="button" class="btn btn-primary" data-height="400px" data-iframe="' . site_url("weicode.imageSelect", ['name' => $name]) . '" data-toggle="modal">选择图片</button>';
    }

    /**
     * 创建日期组件
     * @param string $name
     * @param string $value
     * @param string $attrib
     * @param string $placeholder
     * @return string
     */
    static public function date($name, $value = "", $attrib = "", $placeholder = "")
    {
        return '<input class="form-control form-date" ' . $attrib . '  placeholder="' . $placeholder . '" name="' . $name . '"  type="text" value="' . $value . '" readonly="">';
    }

    /**
     * 创建带时间的日期组件
     * @param string $name
     * @param string $value
     * @param string $attrib
     * @param string $placeholder
     * @return string
     */
    static public function dateTime($name, $value = "", $attrib = "", $placeholder = "")
    {
        return '<input class="form-control form-datetime" ' . $attrib . '  placeholder="' . $placeholder . '" name="' . $name . '"  type="text" value="' . $value . '" readonly="">';
    }

    /**
     * @param string $panelType
     * @param bool $hasHead
     * @param string $headHTML
     * @param string $class
     * @return $this
     */
    public function addPanelBegin($panelType = '', $hasHead = false, $headHTML = '', $class = '')
    {
        switch ($panelType) {
            case WUI::T_DANGER:
                $class .= ' panel-danger';
                break;
            case WUI::T_INFO:
                $class .= ' panel-info';
                break;
            case WUI::T_PRIMARY:
                $class .= ' panel-primary';
                break;
            case WUI::T_SUCCESS:
                $class .= ' panel-success';
                break;
            case WUI::T_WARNING:
                $class .= ' panel-warning';
                break;
        }
        $panel = '<div class="panel ' . $class . ' ">';
        $head = '';
        if ($hasHead) {
            $head .= '<div class="panel-heading">' . $headHTML . '</div>';
        }
        $this->html .= $panel . $head . ' <div class="panel-body">';
        return $this;
    }

    /**
     * @param bool $hasFooter
     * @param string $footerHTML
     * @return $this
     */
    public function addPanelEnd($hasFooter = false,
                                $footerHTML = '')
    {
        $footer = '';
        if ($hasFooter) {
            $footer .= '<div class="panel-footer">' . $footerHTML . '</div>';
        }
        $this->html .= '</div>' . $footer . '</div>';
        return $this;
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
    static public function map_select($name, $options, $value = "", $attrib = "")
    {
        $options_html = '';
        $vs = explode(',',$value);
        foreach ($options as $option) {
            $selected='';
            foreach ($vs as $v)
            {
                if ($v == $option['value']) {
                    $selected = 'selected="selected"';
                }

            }
            $options_html .= '<option  '.$selected.' value="' . $option['value'] . '" >' . $option['name'] . '</option>';
        }
        $html = ' <select  class="form-control" ' . $attrib . ' name="' . $name . '"  >' . $options_html . '
                    </select>';
        return $html;
    }

    /**
     * 增加隐藏信息
     * @param  string $name the name of the textarea tag.
     * @param  string $value the default value of the  tag.
     * @param  string $attrib other attribs.
     * @return $this
     */
    public function addFormHiddenInfo($name, $value = "", $attrib = "")
    {
        $this->html .= parent::hidden($name, $value, $attrib);
        return $this;

    }

    /**
     * 创建通用按钮。
     * Create common button.
     *
     * @param  string $label the label of the button
     * @param  string $class the class of the button
     * @param  string $misc other params
     * @param  string $icon icon
     * @static
     * @access public
     * @return string the common button tag.
     */
    static public function button($name, $label, $class = 'btn btn-default', $attrib = "", $icon = '')
    {
        $idHtml = '';
        if ($name) {
            $idHtml = ' id="' . $name . '" ' . ' name="' . $name . '" ';
        }
        $attrib .= $attrib . $idHtml;
        if (empty($class)) {
            $class = 'btn btn-default';
        }
        return parent::commonButton($label, $class, $attrib, $icon);
    }


    /**
     * 为表单增加按钮
     * @param string $save 保存按钮的文字
     * @param string $otherButtonHtml 其它额外的按钮，请调用本类的button方法
     * @return $this
     */
    public function addFormButtons($save = '保存', $otherButtonHtml = '')
    {
        if ($this->form_class == 'form-horizontal') {
            $form_group = '<div class="form-group">';
            $form_group .= '<div class="col-sm-offset-2 col-sm-10">';
            $form_group .= ' <button type="submit" class="btn btn-primary">' . $save . '</button>';
            $form_group .= $otherButtonHtml;
            $form_group .= '</div></div>';
        } else if ($this->form_class == 'form-inline') {
            $form_group = ' <button type="submit" class="btn btn-primary">' . $save . '</button>';
            $form_group .= $otherButtonHtml;
        }
        $this->html .= $form_group;
        return $this;
    }

    public function html()
    {
        return $this->html;
    }

    static public $DATA;
}

WUI::$DATA = new WUIData();