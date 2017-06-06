<?php
/**
 *  M-PHP开发框架
 * User: 吴渭明
 * Date: 2017/6/3
 * Time: 下午10:35
 * For:
 */


namespace app\api\validate;


class IDMustBePostiveInt extends  BaseValidate
{
    protected $rule=[
      'id'=>'require|isPositiveInteger'
    ];

    protected  function isPositiveInteger($value,$rule='',
        $data='',$field='')
    {
        if( is_numeric($value) && is_int($value+0) && ($value+0) >0 ){
            return true;
        }else{
            return $field.'必须是正整数';
        }
    }

}