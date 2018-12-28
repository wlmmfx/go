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
Route::get('/', 'sources/Index/index');
Route::get('ss/about', 'sources/Index/about');
Route::get('ss/resources', 'sources/Index/resources');
Route::get('ss/image_to_text', 'sources/Index/imageChangeTextContent');
Route::get('ps/:id', 'sources/Index/detail', [], ['id' => '\d+']);
