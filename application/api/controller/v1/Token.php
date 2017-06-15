<?php
/**
 *  WORKBGM 开发平台
 * User: 吴渭明
 * Date: 2017/6/11
 * Time: 下午9:07
 * For:
 */


namespace app\api\controller\v1;


use app\api\service\ShopUserToken;
use app\api\validate\TokenGet;

class Token
{
    public function getToken($code=''){
        (new TokenGet())->goCheck();
        $ut = new ShopUserToken($code);
        $token = $ut->get();
        return [
            'token'=>$token
        ];
    }
}