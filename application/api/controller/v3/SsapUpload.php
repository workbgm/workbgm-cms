<?php
namespace app\api\controller\v3;

use app\api\ssapmodel\SsapImgMap;
use think\Controller;
use think\Session;

/**
 * 通用上传接口
 * Class Upload
 * @package app\api\controller
 */
class SsapUpload extends Controller
{
    protected function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 通用图片上传接口
     * @return \think\response\Json
     */
    public function upload()
    {
        $config = [
            'size' => 20971520,
            'ext'  => 'jpg,gif,png,bmp,pdf'
        ];

        $file = $this->request->file('file');
        $fileName=input("filename");

        $upload_path = str_replace('\\', '/', ROOT_PATH . 'public/uploads');
        $save_path   = '/public/uploads/';
        $info        = $file->validate($config)->move($upload_path);

        if ($info) {
            $m= new SsapImgMap();
            $result = [
                'error' => 0,
                'url'   => str_replace('\\', '/', $save_path . $info->getSaveName())
            ];
            $data['filename']=$fileName;
            $data['path']=$result['url'];
            $m->save($data);
        } else {
            $result = [
                'error'   => 1,
                'message' => $file->getError()
            ];
        }

        return json($result);
    }

}