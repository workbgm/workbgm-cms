<?php
namespace app\admin\controller;

use app\common\controller\AdminBase;
use app\common\model\DevLog as DevLogModel;

class DevLog extends AdminBase
{
    protected $DevLog_Model;
    protected function _initialize()
    {
        parent::_initialize();
        $this->DevLog_Model = new DevLogModel();
     }

    /**
    * 开发日志管理
    * @return mixed
    */
    public function index($page = 1)
    {
        $DevLog_List   = $this->DevLog_Model->where('isdelete',0)->order(['id' => 'ASC'])->paginate(15, false, ['page' => $page]);;
        return $this->fetch('index',['list' => $DevLog_List]);
    }

    /**
    * 添加开发日志
    */
    public function add()
    {
        return $this->fetch('add');
    }

    /**
    * 保存开发日志
    */
    public function save()
    {
        if ($this->request->isPost()) {
            $data            = $this->request->post();
            $validate_result = $this->validate($data, 'DevLog');

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                if ($this->DevLog_Model->save($data)) {
                    $this->success('保存成功');
                } else {
                    $this->error('保存失败');
                }
            }
        }
    }

    /**
    * 编辑开发日志
    * @param $id
    * @return mixed
    */
    public function edit($id)
    {
        $DevLog = $this->DevLog_Model->find($id);
        return $this->fetch('edit', ['DevLog' => $DevLog]);
    }

    /**
    * 更新开发日志
    * @param $id
    */
    public function update($id)
    {
        if ($this->request->isPost()) {
            $data            = $this->request->post();
            $validate_result = $this->validate($data, 'DevLog');

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                if ($this->DevLog_Model->save($data, $id) !== false) {
                    $this->success('更新成功');
                } else {
                    $this->error('更新失败');
                }
            }
        }
    }

    /**
    * 删除开发日志
    * @param $id
    */
    public function delete($id)
    {
        if ($this->DevLog_Model->where('id',$id)->setField('isdelete',1)) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
    * 恢复开发日志
    * @param $id
    */
    public function recycle($id)
    {
        if ($this->DevLog_Model->where('id',$id)->setField('isdelete',0)) {
            $this->success('恢复成功');
        } else {
            $this->error('恢复失败');
        }
    }
    /**
    * 永久删除开发日志
    * @param $id
    */
    public function deleteForever($id)
    {
        if ($this->DevLog_Model->destroy($id)) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
}
