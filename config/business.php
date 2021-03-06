<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/21 14:28
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/
use think\Route;

// 定义路由规则 并设置60秒的缓存
Route::get('/business', 'business/Index/index');
Route::get('bhls', 'business/media/hls');
Route::get('bp/:id', 'business/Index/detail', [], ['id' => '\d+']);
Route::get('bu/:id', 'business/user/home', [], ['id' => '\d+']);