<?php
/**
 *  WORKBGM 开发平台
 * User: 吴渭明
 * Date: 2017/6/12
 * Time: 上午8:46
 * For:
 */

return [
    'app_id' => 'wx3d1540a7ddd5d4cb',  //小程序appid
    'app_secret' => 'ceaf72116545992989f5663c7526a3c4', //小程序secret
    'login_url' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code', //code 换取 session_key
];