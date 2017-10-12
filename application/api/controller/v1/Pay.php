<?php
/**
 *  WORKBGM 开发平台
 * User: 吴渭明
 * Date: 2017/6/21
 * Time: 10:23
 * For:
 */


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePostiveInt;
use app\api\service\Pay as PayService;
use think\Exception;

class Pay extends BaseController
{
    protected $beforeActionList=[
        'checkExclusiveScope' => ['only'=>'getPreOrder']
    ];

    public function getPreOrder($id=''){
        (new IDMustBePostiveInt())->goCheck();
        $pay = new PayService($id);
        return $pay->pay();
    }

    public function redirectNotify()
    {
        $notify = new WxNotify();
        $notify->handle();
    }

    public function notifyConcurrency()
    {
        $notify = new WxNotify();
        $notify->handle();
    }

    public function receiveNotify()
    {
            $notify = new WxNotify();
            $notify->handle();

    }
}