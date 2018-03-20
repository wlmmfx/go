<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// [ 应用入口文件 ]

// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');

// 定义配置文件目录和应用目录同级
define('CONF_PATH', __DIR__ . '/../config/');
// 定义多媒体存放路径
define('CLI_PATH', __DIR__ . '/../cli/');
// 日志文件路径
define('LOG_PATH', __DIR__ . '/../log/');

// 绑定模块
#define("BIND_MODULE","backend");
#define("BIND_MODULE","backend/index");
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
