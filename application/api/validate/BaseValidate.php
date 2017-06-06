<?php
/**
 *  M-PHP开发框架
 * User: 吴渭明
 * Date: 2017/6/3
 * Time: 下午10:53
 * For:
 */


namespace app\api\validate;


use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends  Validate
{
    public function goCheck(){
        $request=Request::instance();
        $params=$request->param();
        $result=$this->check($params);
        if(!$result){
            $error=$this->error;
            throw new Exception($error);
        }else{
            return true;
        }
    }

}