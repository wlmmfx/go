<?php
return [
    // +----------------------------------------------------------------------
    // | 应用设置
    // | development 生产环境：切换配置文件 development.php
    // | production 正式环境：切换配置文件 production.php
    // +----------------------------------------------------------------------

    'app_status' => 'production',
    //'app_status' => 'development',
    'app_author' => 'Tinywan',
    'app_email' => '756684177@qq.com',
    'app_debug' => false,
    // 开启应用Trace调试
    'app_trace' => false,
    // 控制器类后缀
    'controller_suffix'      => true,
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
    // 路由使用完整匹配
    'route_complete_match'   => true,
    // 设置路由配置文件列表,必须指定呀！否则路由失败
    'route_config_file' => ['frontend', 'backend', 'blog', 'live', 'api', 'business','tianchi','im','share'],
    // 域名根，如thinkphp.cn
    'url_domain_root' => 'tinywan.com',
    // 是否强制使用路由
    // 'url_route_must'         => false
    // 默认模块名
    'default_module' => 'sources',
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
    //'extra_config_list' => ['database', 'session'],

    // 默认跳转页面对应的模板文件
    'dispatch_success_tmpl' => APP_PATH . 'common' . DS . 'view' . DS . 'tpl' . DS . 'dispatch_jump.tpl',
    'dispatch_error_tmpl' => APP_PATH . 'common' . DS . 'view' . DS . 'tpl' . DS . 'dispatch_jump.tpl',

    // 异常页面的模板文件
    'exception_tmpl' => THINK_PATH . 'tpl' . DS . 'think_exception.tpl',

    // [自定义] 异常处理handle类 留空使用 \think\exception\Handle
    //'exception_handle'       => 'app\lib\exception\ExceptionHandler',
    //'exception_handle'       => 'app\common\library\exception\ExceptionHandler',
    //'exception_handle'       => '',

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

];

