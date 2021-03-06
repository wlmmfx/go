<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  DateTime: 2017/9/21 14:45
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/
use think\Route;

// 接口
Route::rule('read/:id','api/User/read');
Route::rule('api/index','api/wechat/index');
//Route::rule(':version/user/:id','api/:version.User/read');
//约束变量的规则进行路由匹配
//Route::rule(':version/faker/:sign/[:limit]','api/:version.Faker/read');

//路由地址=>流地址路由 这里修改要和阿里云数据回调地址一样哦
//Route::rule('api/createTestAddress','api/Stream/createTestAddress');

/**
 * ---------------------------------------微信小程序---------------------------------------------------------------------
 */
//Banner
Route::get("api/:version/banner/:id","api/:version.Banner/getBanner");

// Theme
Route::get("api/:version/theme","api/:version.Theme/getSimpleList");
Route::get("api/:version/theme/:id","api/:version.Theme/getComplexOne");

// Product
Route::get("api/:version/product/by_category","api/:version.Product/getAllInCategory");
Route::get("api/:version/product/:id","api/:version.Product/getOne",[],['id'=>'\d+']);
Route::get("api/:version/product/recent","api/:version.Product/getRecent");

// Product Group
//Route::group('api/:version/product',function (){
//    Route::get("/by_category","api/:version.Product/getAllInCategory");
//    Route::get("/:id","api/:version.Product/getOne",[],['id'=>'\d+']);
//    Route::get("/recent","api/:version.Product/getRecent");
//});

// Category
Route::get("api/:version/category/all","api/:version.Category/getAllCategories");

// Token
Route::post("api/:version/token/user","api/:version.Token/getToken");

// Address
Route::post("api/:version/address","api/:version.Address/createOrUpdateAddress");

// Order
Route::post("api/:version/order","api/:version.Order/placeOrder");
Route::get("api/:version/order/by_user","api/:version.Order/getSummaryByUser");
Route::get("api/:version/order/:id","api/:version.Order/getDetail",[],['id'=>'\d+']);

// Pay
Route::post("api/:version/pay/pre_order","api/:version.Pay/getPreOrder");
Route::post("api/:version/pay/notify","api/:version.Pay/receiveNotify");

// Ocr
Route::get("api/:version/ocr/baiDuBasicAccurate","api/:version.OcrApi/baiDuBasicAccurate");

/**
 * ---------------------------------------支付宝支付---------------------------------------------------------------------
 */
Route::rule("api/notify","api/Pay/notifyUrl");
Route::rule("api/return","api/Pay/returnUrl");