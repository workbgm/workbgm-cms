<?php
namespace app\common\controller;

use app\common\model\Module;
use org\Auth;
use think\Loader;
use think\Cache;
use think\Controller;
use think\Db;
use think\Session;
use PHPExcel;

/**
 * 后台公用基础控制器
 * Class AdminBase
 * @package app\common\controller
 */
class AdminBase extends Controller
{
    protected function _initialize()
    {
        parent::_initialize();

        $this->checkAuth();
        $this->getMenu();
        $this->initModule();
        $this->adminSiteConfig();

        // 输出当前请求控制器（配合后台侧边菜单选中状态）
        $this->assign('controller', Loader::parseName($this->request->controller()));
        $this->assign('moduleType','admin');
    }

        /**
     * 站点配置
     */
    public function adminSiteConfig()
    {
       if (Cache::has('admin_site_config')) {
            $site_config = Cache::get('admin_site_config');
        } else {
            $site_config = Db::name('system')->field('value')->where('name', 'site_config')->find();
            $site_config = unserialize($site_config['value']);
            Cache::set('admin_site_config', $site_config);
        }
        $this->assign($site_config);
    }



    /**
     * 权限检查
     * @return bool
     */
    protected function checkAuth()
    {

        if (!Session::has('admin_id')) {
            $this->redirect('admin/login/index');
        }

        $module     = $this->request->module();
        $controller = $this->request->controller();
        $action     = $this->request->action();

        // 排除权限
        $not_check = ['admin/Index/index', 'admin/AuthGroup/getjson', 'admin/System/clear'];

        if (!in_array($module . '/' . $controller . '/' . $action, $not_check)) {
            $auth     = new Auth();
            $admin_id = Session::get('admin_id');
            if (!$auth->check($module . '/' . $controller . '/' . $action, $admin_id) && $admin_id != 1) {
                $this->error('没有权限');
            }
        }
    }

    protected  function initModule(){
        $module=new Module();
        $ModuleData = $module->select();
        foreach ($ModuleData as $k=>$v){
            $ModuleData[$k]['actions'] = json_decode($v['actions'],true);
        }
        $this->assign('modules',$ModuleData);
    }

    /**
     * 获取侧边栏菜单
     */
    protected function getMenu()
    {
        $menu     = [];
        $admin_id = Session::get('admin_id');
        $auth     = new Auth();

        $auth_rule_list = Db::name('auth_rule')->where('status', 1)->order(['sort' => 'DESC', 'id' => 'ASC'])->select();

        foreach ($auth_rule_list as $value) {
            if ($auth->check($value['name'], $admin_id) || $admin_id == 1) {
                $menu[] = $value;
            }
        }
        $menu = !empty($menu) ? array2tree($menu) : [];
        $nav_1_id  = $this->request->param('nav_1_id');
        if(!empty($nav_1_id)){
            Session::set('nav_1_id', $nav_1_id);
        }
        $this->assign('menu2', $this->getMenu2($menu,Session::get('nav_1_id')));
        $nav_2_id  = $this->request->param('nav_2_id');
        if(!empty($nav_2_id)){
            Session::set('nav_2_id', $nav_2_id);
        }
        $this->assign('menu', $menu);
    }

    protected  function  getMenu2($menus,$id){
        foreach ($menus as $menu){
            if($menu['id']==$id){
                if(isset($menu['children'])){
                    return $menu['children'];
                }else{
                    return null;
                }

            }
        }
    }

    public function excel($fields){
        $model = D($this->modeName."View");
        $field_keys=array();
        $field_vals=array();
        foreach ($fields as $k=>$v) {
            if(!empty($k)){
                Array_push($field_keys,$k);
            }
            Array_push($field_vals,$v);
        }
        $data[0]=$fields;
        $list = $model->field(implode(',',$field_keys))->select();
        $data=array_merge($data,$list);
        EXCEL($menu['NAME'], $data,$field_keys);
    }
}