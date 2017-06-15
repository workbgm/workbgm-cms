<?php
namespace  app\api\service;
use app\api\model\ShopUser as ShopUserModel;
use app\common\exception\TokenException;

/**
 *  WORKBGM 开发平台
 * User: 吴渭明
 * Date: 2017/6/11
 * Time: 下午9:15
 * For:
 */
class ShopUserToken extends Token
{

    protected $code;
    protected $wxAppID;
    protected $wxAppSecret;
    protected $wxLoginUrl;

    /**
     * ShopUserToken constructor.
     */
    public function __construct($code)
    {
        $this->code=$code;
        $this->wxAppID=config('wx.app_id');
        $this->wxAppSecret=config('wx.app_secret');
        $this->wxLoginUrl=sprintf(config('wx.login_url'),
            $this->wxAppID,$this->wxAppSecret,$this->code);
    }

    public function get(){
        $result = curl_get($this->wxLoginUrl);
        $wxResult = json_decode($result,true);
        if(empty($wxResult)){
            throw new \think\Exception('获取session_key及OpenID时异常，微信内部错误');
        }else{
            $loginFail=array_key_exists('errcode',$wxResult);
            if($loginFail){
                $this->processLoginError($wxResult);
            }else{
                return $this->grantToken($wxResult);
            }
        }
    }

    private function grantToken($wxResult){
        $openid=$wxResult['openid'];
        $user = ShopUserModel::getByOpenID($openid);
        if($user){
            $uid = $user->id;
        }else{
            $uid = $this->newUser($openid);
        }
        $cachedValue=$this->prepareCachedValue($wxResult,$uid);
        $token = $this->saveToCache($cachedValue);
        return $token;
    }

    private function saveToCache($cachedValue){
        $key = self::generateToken();
        $value = json_encode($cachedValue);
        $expire_in = config('setting.token_expire_in');
        $request = cache($key,$value,$expire_in);
        if(!$request){
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        return $key;
    }

    private function prepareCachedValue($wxResult,$uid){
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        $cachedValue['scope'] = 16;
        return $cachedValue;
    }

    private function newUser($openid){
        $user = ShopUserModel::create([
           'openid' => $openid
        ]);
        return $user->id;
    }

    private function processLoginError($wxResult){
        throw new \app\common\exception\WeChatException([
           'msg'=>$wxResult['errmsg'],
            'errorCode'=> $wxResult['errcode']
        ]);
    }
}