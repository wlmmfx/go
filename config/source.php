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
Route::get('/', 'source/Index/index');
Route::get('hls', 'source/media/hls');
//Route::get('im', 'business/IM/userLogin');
Route::get('p/:id', 'source/Index/detail', [], ['id' => '\d+']);
Route::get('u/:id', 'source/user/home', [], ['id' => '\d+']);