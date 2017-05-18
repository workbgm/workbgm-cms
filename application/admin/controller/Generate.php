<?php
namespace app\admin\controller;

use think\Config;
use think\Loader;
use think\Db;
use app\common\controller\AdminBase;

/**
 * 字典管理
 * Class Generate
 * @package app\admin\controller
 */
class Generate extends AdminBase
{

    protected function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 首页
     * @return mixed
     */
    public function index()
    {
        $tables = Db::getTables();
        $this->assign('tables', $tables);
        $table_name=$this->request->param('table');
        if ($table_name) {
            $table = $this->request->param('table');
            $prefix = Config::get('database.prefix');
            $tableInfo = Db::getTableInfo($table);
            $controller = Loader::parseName(preg_replace('/^(' . $prefix . ')/', '', $table), 1);
            $fields_name=array();
            for($i=0;$i<count($tableInfo['fields']);$i++){
                $fields_name[$i]=getFieldComment($table_name,$tableInfo['fields'][$i]);
            }
            $tableInfo['names']=$fields_name;
            $tableInfo['comment']=getTableComment($table_name);
            $this->assign('table_info', json_encode($tableInfo));
            $this->assign('controller', $controller);
        }
        return $this->fetch();
    }

    /**
     * 模拟终端
     */
    public function cmd()
    {
        echo "<p style='color: green'>代码开始生成中……</p>\n";
        $config = explode(".", $this->request->param('config', 'generate'));
        $configFile = ROOT_PATH . $config[0] . '.php';
        if (!file_exists($configFile)) {
            echo "<p style='color: red;font-weight: bold'>配置文件不存在：{$configFile}</p>\n";
            exit();
        }

        $data = include $configFile;
        $generate = new \Generate();
        $generate->run($data, $this->request->param('file', 'all'));
        echo "<p style='color: green;font-weight: bold'>代码生成成功！</p>\n";
        exit();
    }

    /**
     * 生成代码
     */
    public function run()
    {
        $generate = new \Generate();
        $data = $this->request->post();
        unset($data['file']);
        $generate->run($data, $this->request->post('file'));

        if (isset($data['delete_file']) && $data['delete_file']) {
            return $this->success('删除成功');
        }
        return $this->success('生成成功');
    }
}