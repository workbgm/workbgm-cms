<?php
namespace app\admin\controller;
use app\common\controller\AdminBase;

/**
 *  M-PHP开发框架
 * User: 吴渭明
 * Date: 2017/4/1
 * Time: 16:54
 * For:
 */
class Entry extends AdminBase
{
    public function handler(){
        $module=input('m');
        $type=input('t');
        $action=input('ac');
        $class='';
        switch ($type){
            case 'admin':
                //后台
                $class='\addons\\'.$module. '\Admin';
                break;
            case 'web':
                $class='\addons\\'.$module.'\Web';
                //前台
                break;
        }
        if(!empty($class)){
            return call_user_func_array([new $class,$action],[]);
        }else{
            return false;
        }

    }
}