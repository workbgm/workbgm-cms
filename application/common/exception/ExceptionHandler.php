<?php
/**
 *  M-PHP开发框架
 * User: 吴渭明
 * Date: 2017/6/5
 * Time: 下午3:43
 * For:
 */


namespace app\common\exception;


use Exception;
use think\exception\Handle;
use think\Request;
use think\Log;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;

    public function render(Exception $e)
    {
        if($e instanceof  BaseException){
            //如果是自定义的异常
            $this->code =$e->code;
            $this->msg=$e->msg;
            $this->errorCode=$e->errorCode;
        }else{
            if(!config('app_debug')){
                $this->code = 500;
                $this->msg = '服务器内部错误';
                $this->errorCode = 999;
                $this->recordErrorLog($e);
            }else{
                return parent::render($e);
            }
        }
        $request=Request::instance();
        $result = [
          'msg' => $this->msg,
            'error_code'=>$this->errorCode,
            'request_url' => $request->url()
        ];
        return json($result,$this->errorCode);
    }

    private function recordErrorLog(\think\Exception $e){
        Log::init([
            'type'=>'File',
            'path'=>LOG_PATH,
            'level'=>['error']
        ]);
        Log::record($e->getMessage(),'error');
    }
}