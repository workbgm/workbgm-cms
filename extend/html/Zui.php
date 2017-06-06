<?php
namespace app;
/**
 *  M-PHP开发框架
 * User: 吴渭明
 * Date: 2017/5/31
 * Time: 上午8:41
 * For:
 */
class Zui extends Html
{
    protected $html = '';

    /**
     * 实现tab
     * @param $tabArray  li(href,active,name)
     * @return $this
     */

    public function addTab($tabArray)
    {
        $tab = '<ul class="nav nav-tabs">';
        foreach ($tabArray as $tab_) {
            $li = '<li ' . ($tab_['active'] ? 'class="active"' : '') . '><a ' . ($tab_['href'] ? 'href="' . $tab_['href'] . '"' : '') . '>' . $tab_['name'] . '</a></li>';
            $tab .= $li;
        }
        $tab .= '</ul>';
        $this->html .= $tab;
        return $this;
    }

    public function addFormBegin($action){
        $form='<div class="container-fluid">';
        $form.='<form class="form-horizontal" action="'.$action.'" method="post">';
        $this->html.=$form;
        return $this;
    }

    public function addFormEnd(){
        $form='</form></div>';
        $this->html.=$form;
        return $this;
    }

    /**
     * @param $label
     * @param $type  input textarea hidden
     * @param  string $name      the name of the textarea tag.
     * @param  string $value     the default value of the $type tag.
     * @param  string $attrib    other attribs.
     */
    public function addFormGroup($label,$type,$name, $value = "", $attrib = ""){
        $form_group='<div class="form-group">';
        $form_group.='<label class="col-sm-2 col-md-2">'.$label.'</label>';
        $form_group.='<div class="col-md-10 col-sm-10">';
        switch ($type){
            case 'input':
                $form_group.=parent::input($name,$value,$attrib);
                break;
            case 'textarea':
                $form_group.=parent::textarea($name,$value,$attrib);
                break;
        }
        $form_group.='</div></div>';
        $this->html.=$form_group;
        return $this;
    }

    /**
     * 增加隐藏信息
     * @param  string $name      the name of the textarea tag.
     * @param  string $value     the default value of the  tag.
     * @param  string $attrib    other attribs.
     * @return $this
     */
    public function addFormHiddenInfo($name, $value = "", $attrib = ""){
        $this->html.=parent::hidden($name,$value,$attrib);
        return $this;

    }

    public function addFormButtons($save='保存',$reset='重置'){
        $form_group='<div class="form-group">';
        $form_group.='<div class="col-sm-offset-2 col-sm-10">';
        $form_group.=' <button type="submit" class="btn btn-primary">'.$save.'</button>';
        $form_group.=' <button type="reset" class="btn btn-primary">'.$reset.'</button>';
        $form_group.='</div></div>';
        $this->html.=$form_group;
        return $this;
    }

    public function html()
    {
        return $this->html;
    }
}