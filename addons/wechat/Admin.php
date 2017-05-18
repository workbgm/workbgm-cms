<?php
namespace addons\wechat;
use app\common\controller\AddonsBase;
/**
 *  M-PHP开发框架
 * User: 吴渭明
 * Date: 2017/4/1
 * Time: 16:48
 * For:
 */

/**
 * 后台访问控制类
 * Class Admin
 * @package Addons\wechat
 */
class Admin extends  AddonsBase
{
    public function Test(){
        return $this->fetch();
    }


}