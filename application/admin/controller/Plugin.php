<?php
namespace app\admin\controller;
use app\common\controller\AdminBase;
use app\common\model\Module;
use think\Db;
use app\common\Hook;

/**
 * 插件管理
 * Class Plugin
 * @package app\admin\controller
 */
class Plugin extends AdminBase
{

    protected function _initialize()
    {
        parent::_initialize();
    }

    //模块标识
    protected $name;

    //模块列表
    public function index()
    {
        //模块数据
        $actionsData = [];
        foreach (glob('addons/*') as $v) {
            //存在文件说明是合法的模块
            if (is_file($v . '/maintest.php')) {
                $actionsData[] = include $v . '/maintest.php';
            }
        }

        //查找所有已经安装了的模块(判断安装或者卸载)
        $has =(new Module())->column('name');
        $this->assign('has', $has);
        $this->assign('actionData', $actionsData);
        return $this->fetch();
    }

    //设计模块
    public function add()
    {
        if ($this->request->isPost()) {
            $this->name = $_POST['name'];
            if (is_dir('addons/' . $this->name)) {
                $this->error($this->name . '模块已存在');
                die;
            } else {
                //1.创建出模块的目录和模块基本配置文件
                $this->createConfigFile();
                //2.创建模块基本文件(前台控制器,后台控制器文件)
                $this->createModuleFile();
                //3.提示
                $this->success($this->name . "模块生成", 'index');
                die;
            }
        }

        return $this->fetch();
    }

    //1.创建出模块的目录和模块基本配置文件
    protected function createConfigFile()
    {
        $actions = $_POST['actions'];
        //preg_split正则表达式分割字符串
        $actions = array_filter(preg_split('/(\r|\n)/', $actions));
        $actionsData = [];
        foreach ($actions as $v) {
            $info = explode('|', $v);
            $actionsData[] = [
                'title' => $info[0],
                'name' => $info[1],
                'icon'=>isset($info[2])?$info[2]:''
            ];
        }
        $_POST['name'] = $_POST['name'];
        $_POST['actions'] = $actionsData;
        //创建目录 //(0755线上的是linux环境权限不同)
        mkdir('addons/' . $this->name . '/view', 0755, true);
        file_put_contents('addons/' . $this->name . '/maintest.php', '<?php return ' . var_export($_POST, true) . ' ;?>');
    }

    //2.创建模块基本文件(前台控制器,后台控制器文件)
    protected function createModuleFile()
    {
        //创建模块基本文件(前台控制器,后台控制器文件)
        foreach (glob('data/module/*.php') as $v) {
            //获取文件内容
            $content = file_get_contents($v);
            //替换命名空间
            $content = str_replace('{NAME}', $this->name, $content);
            //压入到模块里去 生成后台、前台控制器文件
            file_put_contents('addons/' . $this->name . '/' . basename($v), $content);
        }

        foreach (glob('data/module/*.html') as $v) {
            //获取文件内容
            $content = file_get_contents($v);
            //替换命名空间
            $content = str_replace('{NAME}', $this->name, $content);
            //压入到模块里去 生成后台、前台控制器文件
            file_put_contents('Addons/' . $this->name . '/view/' . basename($v), $content);
        }
    }

    //安装模块
    public function install()
    {
        $name = input('name');
        $data = include 'addons/' . $name . '/maintest.php';
        $data['actions'] = json_encode($data['actions'], JSON_UNESCAPED_UNICODE);
        $module= new Module();
        $validate_result = $this->validate($data, 'Module');

        if ($validate_result !== true) {
            $this->error($validate_result);
        } else {
            if( $module->data($data)->save() ){
            $this->success('安装成功', 'index');
            }else{
                    $this->error('安装失败', 'index');
                }
        }
    }

    //卸载模块
    public function uninstall()
    {
        $name = input('name');
        $ModuleModel = new Module();
        if ($ModuleModel->where("name='{$name}'")->delete()) {
            $this->success('卸载成功', 'index');
        } else {
            $this->error('卸载失败', 'index');
        }
    }

    public function  delete(){
        $name = input('name');
        if(empty($name)) return $this->error('删除失败，非法的模块', 'index');;
        $path='addons/' . $name;
        $this->deleteAll($path);
        return $this->success('删除成功', 'index');
    }

    protected function deleteAll($path) {
        $op = dir($path);
        while(false != ($item = $op->read())) {
            if($item == '.' || $item == '..') {
                continue;
            }
            if(is_dir($op->path.'/'.$item)) {
                self::deleteAll($op->path.'/'.$item);
                rmdir($op->path.'/'.$item);
            } else {
                unlink($op->path.'/'.$item);
            }

        }
    }
}