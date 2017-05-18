<?php
namespace app\admin\controller;

use app\common\model\Map as MapModel;
use app\common\controller\AdminBase;
use think\Db;

/**
 * 字典管理
 * Class Map
 * @package app\admin\controller
 */
class Map extends AdminBase
{

    protected $map_model;

    protected function _initialize()
    {
        parent::_initialize();
        $this->map_model = new MapModel();
        $map_list   = $this->map_model->order(['id' => 'ASC'])->select();

        $this->assign('map_list', $map_list);
    }

    /**
     * 字典管理
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 添加字典
     */
    public function add()
    {
        return $this->fetch('add');
    }

    /**
     * 保存字典
     */
    public function save()
    {
        if ($this->request->isPost()) {
            $data            = $this->request->post();
            $validate_result = $this->validate($data, 'Map');

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                if ($this->map_model->save($data)) {
                    $this->success('保存成功');
                } else {
                    $this->error('保存失败');
                }
            }
        }
    }

    /**
     * 编辑字典
     * @param $id
     * @return mixed
     */
    public function edit($id)
    {
        $map = $this->map_model->find($id);

        return $this->fetch('edit', ['map' => $map]);
    }

    /**
     * 更新字典
     * @param $id
     */
    public function update($id)
    {
        if ($this->request->isPost()) {
            $data            = $this->request->post();
            $validate_result = $this->validate($data, 'Map');

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                if ($this->map_model->save($data, $id) !== false) {
                    $this->success('更新成功');
                } else {
                    $this->error('更新失败');
                }
            }
        }
    }

    /**
     * 删除字典
     * @param $id
     */
    public function delete($id)
    {
        if ($this->map_model->destroy($id)) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
}