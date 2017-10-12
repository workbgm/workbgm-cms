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
use app\api\validate\AppTokenGet;
use app\api\validate\TokenGet;
use app\common\exception\ParameterException;
use app\api\service\Token as TokenService;
use app\api\service\AppToken;

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

    /**
     * 第三方应用获取令牌
     * @url /app_token?
     * @POST ac=:ac se=:secret
     */
    public function getAppToken($ac='', $se='')
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: GET');
        (new AppTokenGet())->goCheck();
        $app = new AppToken();
        $token = $app->get($ac, $se);
        return [
            'token' => $token
        ];
    }

    public function verifyToken($token='')
    {
        if(!$token){
            throw new ParameterException([
                'token不允许为空'
            ]);
        }
        $valid = TokenService::verifyToken($token);
        return [
            'isValid' => $valid
        ];
    }
}