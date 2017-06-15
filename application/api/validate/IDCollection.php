<?php
/**
 *  WORKBGM 开发平台
 * User: 吴渭明
 * Date: 2017/6/9
 * Time: 下午2:28
 * For:
 */


namespace app\api\validate;


class IDCollection extends  BaseValidate
{
    protected $rule=[
        'ids' => 'require|checkIDs'
    ];

    protected $message=[
        'ids'=>'ids必须是以,分割的正整数'
    ];

    protected function  checkIDs($value){
        $values = explode(',',$value);
        if(empty($value)){
            return false;
        }
        foreach ($values as $id){
            if(!$this->isPositiveInteger($id)){
                return false;
            }
        }
        return true;
    }

}