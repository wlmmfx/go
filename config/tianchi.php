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
Route::rule('tc/main', 'tianchi/Index/uploadImage');
Route::rule('tc/wechat', 'tianchi/Index/index');
Route::rule('tc/read', 'tianchi/Index/customerList');
Route::rule('tc/switch', 'tianchi/Index/switchStreamAddress');
