<?php
/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/6/5
 * Time: 下午3:47
 * For:
 */


namespace app\common\exception;


class SuccessMessage
{

    // HTTP状态码 404,200
    public $code =201;

    //错误具体信息
    public $msg = 'ok';

    // 自定义的错误码
    public $errorCode = 0;

}