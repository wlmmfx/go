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
//Route::rule(':version/user/:id','api/:version.User/read');
//约束变量的规则进行路由匹配
//Route::rule(':version/faker/:sign/[:limit]','api/:version.Faker/read');

//路由地址=>流地址路由 这里修改要和阿里云数据回调地址一样哦
//Route::rule('api/createTestAddress','api/Stream/createTestAddress');