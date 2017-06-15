<?php
/**
 *  WORKBGM开发框架
 * User: 吴渭明
 * Date: 2017/6/5
 * Time: 下午3:47
 * For:
 */


namespace app\common\exception;


class ShopCategoryMissException extends BaseException
{

    // HTTP状态码 404,200
    public $code =404;

    //错误具体信息
    public $msg = '请求分类数据不存在';

    // 自定义的错误码
    public $errorCode = 50000;

}