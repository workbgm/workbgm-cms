<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

Route::get(':version/banner/:id','api/:version.Banner/getBanner');

Route::get(':version/theme',':version.Theme/getSimpleList');

Route::get(':version/theme/:id',':version.Theme/getComplexOne');

Route::get(':version/product/recent',':version.Product/getRecent');

Route::get(':version/product/:id',':version.Product/getOne',[],['id'=>'\d+']);

Route::get(':version/shopcategory/all',':version.ShopCategory/getAllShopCategories');

Route::get(':version/product/by_category',':version.Product/getAllInCategory');

Route::post(':version/token/user',':version.Token/getToken');

Route::post(':version/token/app', ':version.Token/getAppToken');

Route::post(':version/token/verify', ':version.Token/verifyToken');

Route::post(':version/address',':version.Address/createOrUpdateAddress');

Route::get(':version/address', ':version.Address/getUserAddress');

Route::post(':version/order',':version.Order/placeOrder');

Route::get(':version/order/paginate', ':version.Order/getSummary');

Route::get(':version/order/:id',':version.Order/getDetail',[],['id'=>'\d+']);

Route::get(':version/order/by_user',':version.Order/getSummaryByUser');

Route::put(':version/order/delivery', ':version.Order/delivery');

Route::post(':version/pay/pre_order',':version.Pay/getPreOrder');

Route::post(':version/pay/notify',':version.Pay/receiveNotify');



//上传表
Route::post(':version/ssap_table',':version.Ssap/ssap_table');
//获取要下载的图片列表
Route::get(':version/ssap_file',':version.Ssap/get_ssap_file');
//接收图片
Route::post(':version/ssap_file',':version.SsapUpload/upload');
//获取最新版本
Route::get(':version/ssap_version',':version.Ssap/get_ssap_version');
//心跳
Route::post(':version/ssap_heartbeat',':version.Ssap/post_ssap_heartbeat');
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
];
