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

Route::post(':version/address',':version.Address/createOrUpdateAddress');

return [
    '__pattern__' => [
        'name' => '\w+',
    ],

];
