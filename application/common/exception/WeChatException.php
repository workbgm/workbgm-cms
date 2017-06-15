<?php
/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/6/5
 * Time: 下午3:47
 * For:
 */


namespace app\common\exception;


class WeChatException extends BaseException
{

    // HTTP状态码 404,200
    public $code =400;

    //错误具体信息
    public $msg = '微信服务器接口调用失败';

    // 自定义的错误码
    public $errorCode = 999;

}