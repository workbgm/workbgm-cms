<?php

namespace app\common\controller;

use app\common\model\Module;
use org\Auth;
use think\Loader;
use think\Cache;
use think\Controller;
use think\Db;
use think\Session;

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

        $this->getMenu();
        $this->initModule();
        $this->adminSiteConfig();

        // 输出当前请求控制器（配合后台侧边菜单选中状态）
        $this->assign('controller', Loader::parseName($this->request->controller()));
        $this->assign('moduleType', 'admin');
        $this->checkAuth();
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

    private function isIn($ac,$arr){
        foreach ($arr as $a){
            if (strpos(strtolower($ac), strtolower($a)) !== false) {
                return true;
            }
        }
        return false;
    }


    /**
     * 权限检查
     * @return bool
     */
    protected function checkAuth()
    {

        $module = $this->request->module();
        $controller = $this->request->controller();
        $action = $this->request->action();
        $key = '';
        if ($action == 'handler'|| $module == 'addons') {  //扩展
            $m = $this->request->param('m');
            $ac = $this->request->param('ac');
            $t = $this->request->param('t');
            $key = $m . '/' . $ac . '/' . $t;
        } else {
            $key = $module . '/' . $controller . '/' . $action;
        }

        if (!Session::has('admin_id')) {
            $this->redirect('admin/login/index');
        }

        // 排除权限
        $not_check = ['admin/Index/index', 'admin/AuthGroup/getjson', 'admin/System/clear'];

        if (!in_array($key, $not_check)) {
            $admin_id = Session::get('admin_id');
            if ($action == 'handler'|| $module == 'addons') {  //扩展
                if($this->isIn($ac,['add','update','del']))
                {
                    if (!$this->checkAddonByAuth($key, $admin_id) && $admin_id != 1) {
                        $this->error('没有权限');
                    }
                }
            } else {
                $auth = new Auth();
                if (!$auth->check($key, $admin_id) && $admin_id != 1) {
                    $this->error('没有权限');
                }
            }
        }
    }

    protected function checkAddonByAuth($link, $userid = '')
    {
        $auth = new Auth();
        $groups = $auth->getGroups($userid);
        $ids = []; //保存用户所属用户组设置的所有权限规则id
        foreach ($groups as $g) {
            $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
        }
        $ids = array_unique($ids);
        $map = [
            'id' => ['in', $ids],
            'systype' => 1
        ];
        //读取用户组所有权限规则
        $rules = Db::name('auth_rule')->where($map)->field('condition,name')->select();
        foreach ($rules as $rule) {
            if (strcasecmp($link, $rule['name']) == 0) {
                return true;
            }
        }
        return false;
    }

    protected function getAddonsByAuth($userid = '')
    {
        $auth = new Auth();
        $groups = $auth->getGroups($userid);
        $ids = []; //保存用户所属用户组设置的所有权限规则id
        foreach ($groups as $g) {
            $ids = array_merge($ids, explode(',', trim($g['rules'], ',')));
        }
        $ids = array_unique($ids);
        $map = [
            'id' => ['in', $ids],
            'systype' => 1,
            'status' => 1
        ];
        //读取用户组所有权限规则
        $rules = Db::name('auth_rule')->where($map)->field('condition,name')->select();
        $addons = [];
        foreach ($rules as $rule) {
            $addons_m = explode('/', $rule['name']);
            if (count($addons_m) == 3) {
                $addons = array_merge($addons, [strtolower($addons_m[0] . '/' . $addons_m[1])]);
            }
        }
        $addons = array_unique($addons);
        return $addons;
    }

    protected function getAllAddons($addons)
    {
        $module = new Module();
        $ModuleData = $module->select();
        if ($ModuleData) {
            $ModuleData = collection($ModuleData)->toArray();
        }
        $ModuleData_=[];
        foreach ($ModuleData as $k => $v) {
            $md = $ModuleData[$k];
            $md['actions']=[];
            foreach ($addons as $addon) {
                if (strpos($addon, $v['name']."/") !== false) {
                    $ModuleData[$k]['actions'] = json_decode($v['actions'], true);
                    foreach ($ModuleData[$k]['actions'] as $ki => $action) {
                        $ac = explode('/', $addon)[1];
                        if (strcasecmp($ac, $action['name']) == 0) {
                            //unset($ModuleData[$k]['actions'][$ki]);
                            array_push($md['actions'],$ModuleData[$k]['actions'][$ki]);
                        }
                    }
                }
            }
            if(count($md['actions'])) {
                array_push($ModuleData_, $md);
            }
        }
        return array_values($ModuleData_);
    }

    protected function initModule()
    {
        $admin_id = Session::get('admin_id');
        if ($admin_id == 1) {
            $module = new Module();
            $ModuleData = $module->select();
            foreach ($ModuleData as $k => $v) {
                $ModuleData[$k]['actions'] = json_decode($v['actions'], true);
            }
        } else {
            $ModuleData = $this->getAllAddons($this->getAddonsByAuth($admin_id));
        }
        $this->assign('modules', $ModuleData);
    }

    /**
     * 获取侧边栏菜单
     */
    protected function getMenu()
    {
        $menu = [];
        $admin_id = Session::get('admin_id');
        $auth = new Auth();
        $map['status'] = 1;
        $map['systype'] = 0;
        $auth_rule_list = Db::name('auth_rule')->where($map)->order(['sort' => 'DESC', 'id' => 'ASC'])->select();

        foreach ($auth_rule_list as $value) {
            if ($auth->check($value['name'], $admin_id) || $admin_id == 1) {
                $menu[] = $value;
            }
        }
        $menu = !empty($menu) ? array2tree($menu) : [];
        $nav_1_id = $this->request->param('nav_1_id');
        if (!empty($nav_1_id)) {
            Session::set('nav_1_id', $nav_1_id);
        }
        $this->assign('menu2', $this->getMenu2($menu, Session::get('nav_1_id')));
        $nav_2_id = $this->request->param('nav_2_id');
        if (!empty($nav_2_id)) {
            Session::set('nav_2_id', $nav_2_id);
        }
        $this->assign('menu', $menu);
    }

    protected function getMenu2($menus, $id)
    {
        foreach ($menus as $menu) {
            if ($menu['id'] == $id) {
                if (isset($menu['children'])) {
                    return $menu['children'];
                } else {
                    return null;
                }

            }
        }
    }

    /**
     * 列出model的list
     * $m:模型
     * $tpl:要跳转的模板
     * @return mixed
     */
    public function m_index($page = 1, $m, $tpl)
    {
        $list = $m->where('isdelete', 0)->order(['id' => 'ASC'])->paginate(15, false, ['page' => $page]);
        $this->assign('list', $list);
        return $this->fetch($tpl);
    }

    /**
     * 跳转到add页面
     * $tpl:要跳转的模板
     */
    public function m_add($tpl)
    {
        return $this->fetch($tpl);
    }

    /**
     * 保存
     * $v:验证器名字
     * $m:模型
     */
    public function m_save($v, $m, $tpl)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            foreach ($data as $key => $value) {
                if (is_array($data[$key])) {
                    $data[$key] = serialize($data[$key]);
                }
            }
            $validate_result = $this->validate($data, $v);

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                if ($m->save($data)) {
                    $this->success('保存成功');
                } else {
                    $this->error('保存失败');
                }
            }
        }
    }

    /**
     * 编辑
     * $m:模型
     * $tpl:要跳转的模板
     * @param $id
     * @return mixed
     */
    public function m_edit($id, $m, $tpl)
    {
        $obj = $m->find($id);
        $this->assign('obj', $obj);
        return $this->fetch($tpl);
    }

    /**
     * 更新
     * $v:验证器名字
     * $m:模型
     * @param $id
     */
    public function m_update($id, $v, $m)
    {
        if ($this->request->isPost()) {
            $data = $this->request->post();
            foreach ($data as $key => $value) {
                if (is_array($data[$key])) {
                    $data[$key] = serialize($data[$key]);
                }
            }
            $validate_result = $this->validate($data, $v);

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                if ($m->save($data, $id) !== false) {
                    $this->success('更新成功');
                } else {
                    $this->error('更新失败');
                }
            }
        }
    }

    /**
     * 删除
     * $m:模型
     * @param $id
     */
    public function m_delete($id, $m)
    {
        if ($m->where('id', $id)->setField('isdelete', 1)) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
     * 恢复
     * $m:模型
     * @param $id
     */
    public function m_recycle($id, $m)
    {
        if ($m->where('id', $id)->setField('isdelete', 0)) {
            $this->success('恢复成功');
        } else {
            $this->error('恢复失败');
        }
    }

    /**
     * 永久删除
     * $m:模型
     * @param $id
     */
    public function m_deleteForever($id, $m)
    {
        if ($m->destroy($id)) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
}