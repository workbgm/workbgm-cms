<?php
namespace app\[MODULE]\controller[NAMESPACE];

use app\common\controller\AdminBase;
use app\common\model\[NAME] as [NAME]Model;

class [NAME] extends AdminBase
{
    protected $[NAME]_Model;
    protected function _initialize()
    {
        parent::_initialize();
        $this->[NAME]_Model = new [NAME]Model();
     }

    /**
    * [TABLECOMMENT]管理
    * @return mixed
    */
    public function index($page = 1)
    {
        $[NAME]_List   = $this->[NAME]_Model->where('isdelete',0)->order(['id' => 'ASC'])->paginate(15, false, ['page' => $page]);;
        return $this->fetch('index',['list' => $[NAME]_List]);
    }

    /**
    * 添加[TABLECOMMENT]
    */
    public function add()
    {
        return $this->fetch('add');
    }

    /**
    * 保存[TABLECOMMENT]
    */
    public function save()
    {
        if ($this->request->isPost()) {
            $data            = $this->request->post();
            $validate_result = $this->validate($data, '[NAME]');

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                if ($this->[NAME]_Model->save($data)) {
                    $this->success('保存成功');
                } else {
                    $this->error('保存失败');
                }
            }
        }
    }

    /**
    * 编辑[TABLECOMMENT]
    * @param $id
    * @return mixed
    */
    public function edit($id)
    {
        $[NAME] = $this->[NAME]_Model->find($id);
        return $this->fetch('edit', ['[NAME]' => $[NAME]]);
    }

    /**
    * 更新[TABLECOMMENT]
    * @param $id
    */
    public function update($id)
    {
        if ($this->request->isPost()) {
            $data            = $this->request->post();
            $validate_result = $this->validate($data, '[NAME]');

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                if ($this->[NAME]_Model->save($data, $id) !== false) {
                    $this->success('更新成功');
                } else {
                    $this->error('更新失败');
                }
            }
        }
    }

    /**
    * 删除[TABLECOMMENT]
    * @param $id
    */
    public function delete($id)
    {
        if ($this->[NAME]_Model->where('id',$id)->setField('isdelete',1)) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
    * 恢复[TABLECOMMENT]
    * @param $id
    */
    public function recycle($id)
    {
        if ($this->[NAME]_Model->where('id',$id)->setField('isdelete',0)) {
            $this->success('恢复成功');
        } else {
            $this->error('恢复失败');
        }
    }
    /**
    * 永久删除[TABLECOMMENT]
    * @param $id
    */
    public function deleteForever($id)
    {
        if ($this->[NAME]_Model->destroy($id)) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
}
