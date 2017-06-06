<?php
namespace app\admin\controller;

use app\common\controller\AdminBase;
use app\common\model\Wechat as Test1Model;

class Test1 extends AdminBase
{
    protected $Test1_Model;
    protected function _initialize()
    {
        parent::_initialize();
        $this->Test1_Model = new Test1Model();
     }

    /**
    * 管理
    * @return mixed
    */
    public function index($page = 1)
    {
        $Test1_List   = $this->Test1_Model->where('isdelete',0)->order(['id' => 'ASC'])->paginate(15, false, ['page' => $page]);;
        return $this->fetch('index',['list' => $Test1_List]);
    }

    /**
    * 添加
    */
    public function add()
    {
        return $this->fetch('add');
    }

    /**
    * 保存
    */
    public function save()
    {
        if ($this->request->isPost()) {
            $data            = $this->request->post();
            $validate_result = $this->validate($data, 'Test1');

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                if ($this->Test1_Model->save($data)) {
                    $this->success('保存成功');
                } else {
                    $this->error('保存失败');
                }
            }
        }
    }

    /**
    * 编辑
    * @param $id
    * @return mixed
    */
    public function edit($id)
    {
        $Test1 = $this->Test1_Model->find($id);
        return $this->fetch('edit', ['Test1' => $Test1]);
    }

    /**
    * 更新
    * @param $id
    */
    public function update($id)
    {
        if ($this->request->isPost()) {
            $data            = $this->request->post();
            $validate_result = $this->validate($data, 'Test1');

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                if ($this->Test1_Model->save($data, $id) !== false) {
                    $this->success('更新成功');
                } else {
                    $this->error('更新失败');
                }
            }
        }
    }

    /**
    * 删除
    * @param $id
    */
    public function delete($id)
    {
        if ($this->Test1_Model->where('id',$id)->setField('isdelete',1)) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
    * 恢复
    * @param $id
    */
    public function recycle($id)
    {
        if ($this->Test1_Model->where('id',$id)->setField('isdelete',0)) {
            $this->success('恢复成功');
        } else {
            $this->error('恢复失败');
        }
    }
    /**
    * 永久删除
    * @param $id
    */
    public function deleteForever($id)
    {
        if ($this->Test1_Model->destroy($id)) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
}
