<?php
namespace app\admin\controller;

use app\common\controller\AdminBase;
use think\Db;
use app\common\model\DatabaseBackups as DatabaseBackupsModel;

class DatabaseBackups extends AdminBase
{
    protected $DatabaseBackups_Model;
    protected function _initialize()
    {
        parent::_initialize();
        $this->DatabaseBackups_Model = new DatabaseBackupsModel();
     }

    /**
    * 管理
    * @return mixed
    */
    public function index($page = 1)
    {
        $DatabaseBackups_List   = $this->DatabaseBackups_Model->where('isdelete',0)->order(['id' => 'ASC'])->paginate(15, false, ['page' => $page]);;
        return $this->fetch('index',['list' => $DatabaseBackups_List]);
    }

//    private function showPro($msg){
//        //省略php接口中处理数据的代码
//        echo json_encode($msg);//返回结果给ajax
//        // get the size of the output
//        $size = ob_get_length();
//        // send headers to tell the browser to close the connection
//        header("Content-Length: $size");
//        header('Connection: close');
//        ob_end_flush();
//        ob_flush();
//        flush();
//        sleep ( 1 );
//    }

    /**
     * 导出数据库备份
     */
    public function exportDatabase(){
        ignore_user_abort(true);
        set_time_limit(0); //no time limit
        header("Content-type:text/html;charset=utf-8");
        $message="";//返回信息
        $path = RUNTIME_PATH.'mysql/';
        $database = config('database')['database'];
//echo "运行中，请耐心等待...<br/>";
        $info = "-- ----------------------------\r\n";
        $info .= "-- 日期：".date("Y-m-d H:i:s",time())."\r\n";
        $info .= "-- MySQL - 5.5.52-MariaDB : Database - ".$database."\r\n";
        $info .= "-- ----------------------------\r\n\r\n";
        $info .= "CREATE DATAbase IF NOT EXISTS `".$database."` DEFAULT CHARACTER SET utf8 ;\r\n\r\n";
        $info .= "USE `".$database."`;\r\n\r\n";

// 检查目录是否存在
        if(is_dir($path)){
// 检查目录是否可写
            if(is_writable($path)){
//echo '目录可写';exit;
            }else{
//echo '目录不可写';exit;
                chmod($path,0777);
            }
        }else{
//echo '目录不存在';exit;
// 新建目录
            mkdir($path, 0777, true);
//chmod($path,0777);
        }

// 检查文件是否存在
        $file_name = $path.$database.'-'.date("Y-m-d-H-i-s",time()).'.sql';
        if(file_exists($file_name)){
            $message="数据备份文件已存在！";
            return [
                'status'=>0,
                'info'=>$message,
                'file'=>$file_name
            ];
        }
        file_put_contents($file_name,$info,FILE_APPEND);

//查询数据库的所有表
        $result = Db::query('show tables');
//        $table_count =count($result);
//        $index=1;
//print_r($result);exit;
        foreach ($result as $k=>$v) {
//查询表结构
            $val = $v['Tables_in_'.$database];
//            $this->showPro( '['.$index.'/'.$table_count.'-'.$val.']');
//            $index++;
            $sql_table = "show create table ".$val;
            $res = Db::query($sql_table);
//print_r($res);exit;
            $info_table = "-- ----------------------------\r\n";
            $info_table .= "-- Table structure for `".$val."`\r\n";
            $info_table .= "-- ----------------------------\r\n\r\n";
            $info_table .= "DROP TABLE IF EXISTS `".$val."`;\r\n\r\n";
            $info_table .= $res[0]['Create Table'].";\r\n\r\n";
//查询表数据
            $info_table .= "-- ----------------------------\r\n";
            $info_table .= "-- Data for the table `".$val."`\r\n";
            $info_table .= "-- ----------------------------\r\n\r\n";
            file_put_contents($file_name,$info_table,FILE_APPEND);
            $sql_data = "select * from ".$val;
            $data = Db::query($sql_data);
//print_r($data);exit;
            $count= count($data);
//print_r($count);exit;
            if($count<1) continue;
            foreach ($data as $key => $value){
                $sqlStr = "INSERT INTO `".$val."` VALUES (";
                foreach($value as $v_d){
                    $v_d = str_replace("'","\'",$v_d);
                    $sqlStr .= "'".$v_d."', ";
                }
//需要特别注意对数据的单引号进行转义处理
//去掉最后一个逗号和空格
                $sqlStr = substr($sqlStr,0,strlen($sqlStr)-2);
                $sqlStr .= ");\r\n";
                file_put_contents($file_name,$sqlStr,FILE_APPEND);
            }
            $info = "\r\n";
            file_put_contents($file_name,$info,FILE_APPEND);
        }
        $message="数据备份完成！";
        return [
            'status'=>1,
            'info'=>$message,
            'file'=>$file_name
        ];
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
            $validate_result = $this->validate($data, 'DatabaseBackups');

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                if ($this->DatabaseBackups_Model->save($data)) {
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
        $DatabaseBackups = $this->DatabaseBackups_Model->find($id);
        return $this->fetch('edit', ['DatabaseBackups' => $DatabaseBackups]);
    }

    /**
    * 更新
    * @param $id
    */
    public function update($id)
    {
        if ($this->request->isPost()) {
            $data            = $this->request->post();
            $validate_result = $this->validate($data, 'DatabaseBackups');

            if ($validate_result !== true) {
                $this->error($validate_result);
            } else {
                if ($this->DatabaseBackups_Model->save($data, $id) !== false) {
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
        if ($this->DatabaseBackups_Model->where('id',$id)->setField('isdelete',1)) {
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
        if ($this->DatabaseBackups_Model->where('id',$id)->setField('isdelete',0)) {
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
        if ($this->DatabaseBackups_Model->destroy($id)) {
            $this->success('删除成功');
        } else {
            $this->error('删除失败');
        }
    }
}
