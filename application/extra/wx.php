<?php
/**
 *  WORKBGM 开发平台
 * User: 吴渭明
 * Date: 2017/6/12
 * Time: 上午8:46
 * For:
 */

return [
    /*小程序相关*/
    'app_id' => 'wx3d1540a7ddd5d4cb',  //小程序appid
    'app_secret' => '55a2a4a418f7d11f25356c0a471ecfc3', //小程序secret
    /*小程序支付相关  佔時放在WxPayConfig文件內*/
//    'MCHID' => '1307100201', //商户号
//    'KEY' => '8934e7d15453e97507ef794cf7b0519d', //商户支付密钥
//    'SSLCERT_PATH' => 'C:/Program/www/well.zipscloud.com/cert/szx/apiclient_cert.pem',
//    'SSLKEY_PATH' => 'C:/Program/www/well.zipscloud.com/cert/szx/apiclient_key.pem',
    'login_url' => 'https://api.weixin.qq.com/sns/jscode2session?appid=%s&secret=%s&js_code=%s&grant_type=authorization_code', //code 换取 session_key

    // 微信获取access_token的url地址
    'access_token_url' => "https://api.weixin.qq.com/cgi-bin/token?" .
        "grant_type=client_credential&appid=%s&secret=%s",
];