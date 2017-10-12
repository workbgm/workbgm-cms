<?php
/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/6/3
 * Time: 下午9:23
 * For:
 */


namespace app\api\validate;


use app\common\exception\ParameterException;

class Image extends BaseValidate
{
    protected  $rule=[
        'from'=>'require|fromCheck',
    ];

    protected $message = [
        'from.fromCheck' => '图片信息不全!'
        ];

    protected  function fromCheck($value,$rule='',
                                          $data='',$field=''){
        if($value==1){ //本地
            if(empty($data['url2'])){
                return false;
            }
        }else if($value==2){ //网络
            if(empty($data['url1'])) {
              return false;
            }
        }
        return true;
    }
}