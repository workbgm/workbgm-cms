<?php
/**
 *  WORKBGM 开发平台
 * User: 吴渭明
 * Date: 2017/6/12
 * Time: 下午9:46
 * For:
 */


namespace app\api\service;


use app\common\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;
use app\common\enum\ScopeEnum;
use app\common\exception\ForbiddenException;
use app\api\service\Token as TokenService;

class Token
{
    public static function generateToken(){
        //32个字符组成一组随机字符串
        $randChars = getRandChar(32);
        //用三组字符串，进行MD5加密
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        //salt
        $salt = config('secure.token_salt');
        return md5($randChars.$timestamp.$salt);
    }

    public static function getCurrentTokenVar($key){
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);
        if(!$vars){
            throw  new TokenException();
        }else{
            if(!is_array($vars))
            {
                $vars=json_decode($vars,true);
            }
            if(array_key_exists($key,$vars)){
                return $vars[$key];
            }else{
                throw new Exception('尝试获取的Token变量并不存在');
            }
        }
    }

    public static function getCurrentUid(){
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    public static function needPrimaryScope(){
        $scope = self::getCurrentTokenVar('scope');
        if($scope){
            if($scope>=ScopeEnum::User){
                return true;
            }else{
                throw  new ForbiddenException();
            }
        }else{
            throw  new TokenException();
        }
    }

    public static function needExclusiveScope(){
        $scope = self::getCurrentTokenVar('scope');
        if($scope){
            if($scope==ScopeEnum::User){
                return true;
            }else{
                throw  new ForbiddenException();
            }
        }else{
            throw  new TokenException();
        }
    }

    public static function isValidOperate($checkedUID){
        if(!$checkedUID){
            throw new Exception('检查UID时必须传入一个被检查的UID');
        }
        $currentOperateUID = self::getCurrentUid();
        if($currentOperateUID == $checkedUID){
            return true;
        }
        return false;
    }

    public static function verifyToken($token)
    {
        $exist = Cache::get($token);
        if($exist){
            return true;
        }
        else{
            return false;
        }
    }
}