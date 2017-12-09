<?php
return [
    'app_status' => 'office',    //home office  通过这里可以切换配置文件home.php 和office.php的配置文件
    'app_author' => 'Tinywan',
    'app_email' => '756684177@qq.com',
    'app_debug' => true,
    // 开启应用Trace调试
    'app_trace' => false,
    'trace' => [
        // 内置Html Console 支持扩展
        'type' => 'console',
    ],
    // 默认的空控制器名
    'empty_controller' => 'Error',
    // 入口自动绑定模块
    //'auto_bind_module'       => true,
    // 是否开启路由
    'url_route_on' => true,
    // 设置路由配置文件列表
    'route_config_file' => ['frontend', 'backend', 'blog', 'live'],
    // 域名根，如thinkphp.cn
    'url_domain_root' => 'tinywan.com',
    // 是否强制使用路由
    // 'url_route_must'         => false
    // 默认模块名
    'default_module' => 'blog',
    // 是否自动转换URL中的控制器和操作名
    'url_convert' => false,
    'captcha' => [
        // 验证码字符集合
        'codeSet' => '12345',
        //	使用中文验证码
//        'useZh' => true,
        //中文验证码字符串
//        'zhSet'  => '服务器端程序在性能问题上应该有何种基本思路',
        // 验证码字体大小(px)
        'fontSize' => 30,
        // 是否画混淆曲线
        'useCurve' => true,
        // 验证码图片高度
        'imageH' => 50,
        // 验证码图片宽度
        'imageW' => 300,
        // 验证码位数
        'length' => 3,
        'bg' => [243, 251, 254],
        // 验证成功后是否重置
        'reset' => true
    ],
    //独立配置文件数据库配置文件
    'extra_config_list' => ['database', 'session'],
    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl' => APP_PATH . 'common' . DS . 'view' . DS . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl' => APP_PATH . 'common' . DS . 'view' . DS . 'tpl' . DS . 'dispatch_jump.tpl',

    // 异常页面的模板文件
    'exception_tmpl' => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',
    'http_exception_template' => [
        // 定义404错误的模板文件地址
        404 => APP_PATH . 'common' . DS . 'view' . DS . 'tpl' . DS . '404.html',
        // 还可以定义其它的HTTP status
        401 => APP_PATH . '401.html',
    ],

    // 是否开启请求缓存 true自动缓存 支持设置请求缓存规则
    'request_cache' => false,
    // 请求缓存有效期
    'request_cache_expire' => 60,
    // 全局请求缓存排除规则
    'request_cache_except' => [],

    'log' => [
        'type' => 'socket',
        'host' => '120.26.93.84',
        //日志强制记录到配置的client_id
        'force_client_ids' => ['tinywan123456'],
        //限制允许读取日志的client_id
        'allow_client_ids' => ['tinywan123456'],
    ],

    // +----------------------------------------------------------------------
    // | 同时使用多个缓存类型
    // +----------------------------------------------------------------------
    'cache' => [
        // 使用复合缓存类型
        'type' => 'complex',
        // 默认使用的缓存
        'default' => [
            // 驱动方式
            'type' => 'File',
            // 缓存保存目录
            'path' => CACHE_PATH,
            // 缓存前缀
            'prefix' => 'FileCache',
            // 缓存有效期 0表示永久缓存
            'expire' => 0,
        ],
        // 文件缓存
        'file' => [
            // 驱动方式
            'type' => 'file',
            // 设置不同的缓存保存目录
            'path' => RUNTIME_PATH . 'file/',
            // 缓存前缀
            'prefix' => 'FileCache:',
            // 缓存有效期 0表示永久缓存
            'expire' => 0,
        ],
        // redis缓存
        'redis' => [
            // 驱动方式
            'type' => 'redis',
            // 服务器地址
            'host' => '172.19.230.35',
            'port' => '63789',
            'password' => 'MMiTUXQLfWmycmSG3YSTAgtEMFAVFFnQ91r7QuC5Q38qribjE1nit6Jckes9oHaiCrWVvpUNVgM08SH7b8V61A==',
            // 缓存前缀
            'prefix' => 'RedisCache:',
            // 缓存有效期 0表示永久缓存
            'expire' => 604800,
        ],
    ],
];

