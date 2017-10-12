<?php
/**
 *  WORKBGM 开发平台
 * User: 吴渭明
 * Date: 2017/6/9
 * Time: 下午2:28
 * For:
 */


namespace app\api\validate;


use app\common\exception\ParameterException;

class OrderPlace extends  BaseValidate
{
    protected $rule = [
        'products' => 'checkProducts'
    ];

    protected $singleRule = [
        'product_id' => 'require|isPositiveInteger',
        'count' => 'require|isPositiveInteger',
    ];

    protected function checkProducts($values){
        if(empty($values)){
            throw new ParameterException([
                'msg' => '商品列表不能为空'
            ]);
        }

        if(!is_array($values)){
            throw  new ParameterException([
                'msg' => '商品参数不正确'
            ]);
        }

        foreach ($values as $value){
            $this->checkProduct($value);
        }

        return true;
    }

    private function checkProduct($value)
    {
        $validate = new BaseValidate($this->singleRule);
        $result = $validate->check($value);
        if(!$result){
            throw new ParameterException([
                'msg' => '商品列表参数错误',
            ]);
        }
    }

}