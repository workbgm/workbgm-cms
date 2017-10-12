<?php
/**
 *  WORKBGM 开发平台
 * User: 吴渭明
 * Date: 2017/6/19
 * Time: 23:33
 * For:
 */


namespace app\api\controller;


use app\api\service\Token as TokenService;
use think\Controller;

class BaseController extends Controller
{
    protected function checkPrimaryScope(){
         TokenService::needPrimaryScope();
    }

    protected function checkExclusiveScope(){
         TokenService::needExclusiveScope();
    }

    protected function checkSuperScope()
    {
        TokenService::needSuperScope();
    }
}