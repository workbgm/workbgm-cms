<?php
/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/6/5
 * Time: 下午3:47
 * For:
 */


namespace app\common\exception;


class OrderException extends BaseException
{

    // HTTP状态码 404,200
    public $code =404;

    //错误具体信息
    public $msg = '订单不存在，请检查ID';

    // 自定义的错误码
    public $errorCode = 80000;

}