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

// 定义路由规则 并设置60秒的缓存
Route::get('/', 'blog/Index/index');
// 文章详情路由
Route::get('bd/:id', 'blog/Index/detail', [], ['id' => '\d+']);
//Route::group(['prefix' => 'blog/Index/', 'ext' => 'html'], function () {
//    Route::get(':category/[:page]$', 'category');
//    Route::get('bd/:id', 'detail');
//    Route::get(':year/:month$', 'archive');
//    Route::get('login', 'login');
//    Route::post('login', 'checkLogin');
//    Route::post('search', 'search');
//    Route::put('comment', 'comment');
//}, [], ['id' => '\d+', 'category' => '^[A-Za-z]\w+', 'year' => '\d{4}', 'month' => '\d{2}']);
//Route::miss('/', 'GET');