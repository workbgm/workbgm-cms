<?php
/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/6/6
 * Time: 下午9:27
 * For:
 */


namespace app\common\exception;


class ParameterException extends BaseException
{
    // HTTP状态码 404,200
    public $code =400;

    //错误具体信息
    public $msg = '参数错误';

    // 自定义的错误码
    public $errorCode = 10000;
}