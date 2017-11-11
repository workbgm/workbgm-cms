<?php
namespace app\admin\controller;

use app\common\controller\AdminBase;
use app\common\model\GenApp as GenAppModel;

class GenApp extends AdminBase
{
    protected $GenApp_Model;
    protected function _initialize()
    {
        parent::_initialize();
        $this->GenApp_Model = new GenAppModel();
     }

    /**
    * 生成应用管理
    * @return mixed
    */
    public function index($page = 1)
    {
        $GenApp_List   = $this->GenApp_Model->where('isdelete',0)->order(['id' => 'ASC'])->paginate(15, false, ['page' => $page]);
        return $this->fetch('index',['list' => $GenApp_List]);
    }

    /**
    * 添加生成应用
    */
    public function add()
    {
        return $this->fetch('add');
    }

    /**
    * 保存生成应用
    */
    public function save()
    {
        if ($this->request->isPost()) {
            $data            = $this->request->post();
            $validate_result = $this->validate($data, 'GenApp');

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                if ($this->GenApp_Model->save($data)) {
                    $this->success('保存成功');
                } else {
                    $this->error('保存失败');
                }
            }
        }
    }

    /**
    * 编辑生成应用
    * @param $id
    * @return mixed
    */
    public function edit($id)
    {
        $GenApp = $this->GenApp_Model->find($id);
        return $this->fetch('edit', ['GenApp' => $GenApp]);
    }

    /**
     * @param $id
     */
    public function gen($id){
        $GenApp = $this->GenApp_Model->find($id);
        if($GenApp){
            $domain = $GenApp->domain;
            $web = $domain.'.'.config('setting.domain');
            $web_map =$web." ".config('setting.www').$web;
            $webs = '';
            $myfile = fopen(config('setting.rewrite_map'), "a") or die("Unable to open file!");
            fwrite($myfile, "\r\n".$web_map);
            fclose($myfile);
            return $this->success('写入成功');
        }

        return $this->error('写入失败');
    }

    /**
    * 更新生成应用
    * @param $id
    */
    public function update($id)
    {
        if ($this->request->isPost()) {
            $data            = $this->request->post();
            $validate_result = $this->validate($data, 'GenApp');

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                if ($this->GenApp_Model->save($data, $id) !== false) {
                    $this->success('更新成功');
                } else {
                    $this->error('更新失败');
                }
            }
        }
    }

    /**
    * 删除生成应用
    * @param $id
    */
    public function delete($id)
    {
        if ($this->GenApp_Model->where('id',$id)->setField('isdelete',1)) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }

    /**
    * 恢复生成应用
    * @param $id
    */
    public function recycle($id)
    {
        if ($this->GenApp_Model->where('id',$id)->setField('isdelete',0)) {
            $this->success('恢复成功');
        } else {
            $this->error('恢复失败');
        }
    }
    /**
    * 永久删除生成应用
    * @param $id
    */
    public function deleteForever($id)
    {
        if ($this->GenApp_Model->destroy($id)) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
}
