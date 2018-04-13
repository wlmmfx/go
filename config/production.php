<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/9/2 8:12
 * |  Mail: Overcome.wan@Gmail.com
 * |  Function: 正式环境
 * '-------------------------------------------------------------------*/
return [

    // +----------------------------------------------------------------------
    // | 应用设置
    // +----------------------------------------------------------------------

    "app_address" => "正式环境",
    "web_home" => "www.tinywan.com",

    // +----------------------------------------------------------------------
    // | 数据库设置
    // +----------------------------------------------------------------------

    "database" => [
        // 数据库类型
        'type' => 'mysql',
        // 数据库连接DSN配置
        'dsn' => '',
        // 服务器地址
        'hostname' => '172.19.230.35',
        // 数据库名
        'database' => 'resty',
        // 数据库用户名
        'username' => 'www',
        // 数据库密码
        'password' => 'www_resty_test_123456',
        // 数据库连接端口
        'hostport' => '',
        // 数据库连接参数
        'params' => [],
        // 数据库编码默认采用utf8
        'charset' => 'utf8',
        // 数据库表前缀
        'prefix' => 'resty_',
        // 数据库调试模式
        'debug' => true,
        // 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
        'deploy' => 0,
        // 数据库读写是否分离 主从式有效
        'rw_separate' => false,
        // 读写分离后 主服务器数量
        'master_num' => 1,
        // 指定从服务器序号
        'slave_no' => '',
        // 是否严格检查字段是否存在
        'fields_strict' => true,
        // 数据集返回类型
        'resultset_type' => 'array',
        // 自动写入时间戳字段
        'auto_timestamp' => false,
        // 时间字段取出后的默认时间格式
        'datetime_format' => 'Y-m-d H:i:s',
        // 是否需要进行SQL性能分析
        'sql_explain' => false,
        // Builder类
        'builder' => '',
        // Query类
        'query' => '\\think\\db\\Query',
        // Log 日志
        'log_table' => 'resty_logs'
    ],

    // +----------------------------------------------------------------------
    // | 会话设置
    // +----------------------------------------------------------------------

    'session' => [
        'id' => '',
        // SESSION_ID的提交变量,解决flash上传跨域
        'var_session_id' => '',
        // SESSION 前缀
        'prefix' => 'think',
        // 驱动方式 支持redis memcache memcached
        'type' => '',
        // 是否自动开启 SESSION
        'auto_start' => true,
        'httponly' => true,
        'secure' => false,

        'prefix' => 'module',
        'type' => 'redis',
        'host' => '172.19.230.35',
        'port' => '63789',
        'password' => 'MMiTUXQLfWmycmSG3YSTAgtEMFAVFFnQ91r7QuC5Q38qribjE1nit6Jckes9oHaiCrWVvpUNVgM08SH7b8V61A==',
        'select' => 0,
        'expire' => '17200',
        'timeout' => '0',
        'persistent' => true,
        'session_name' => 'RESTY_PHPSESSID:',
        'record' => '1200'
    ],

    // +----------------------------------------------------------------------
    // | 日志设置
    // +----------------------------------------------------------------------

    'log' => [
        // 日志记录方式，内置 file socket 支持扩展
        'type' => 'test',
        // 日志保存目录
        'path' => LOG_PATH,
        // 日志记录级别
        'level' => [],
        // error和sql日志单独记录
        'apart_level' => ['error', 'sql']
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
            'type' => 'redis',
            // 服务器地址
            'host' => '172.19.230.35',
            'port' => '63789',
            'password' => 'MMiTUXQLfWmycmSG3YSTAgtEMFAVFFnQ91r7QuC5Q38qribjE1nit6Jckes9oHaiCrWVvpUNVgM08SH7b8V61A==',
            // 缓存前缀
            'prefix' => 'REDIS_CACHE:',
            // 缓存有效期 0表示永久缓存
            'expire' => 604800,
        ],
        // 文件缓存
        'file' => [
            // 驱动方式
            'type' => 'file',
            // 设置不同的缓存保存目录
            'path' => RUNTIME_PATH . 'file/',
            // 缓存前缀
            'prefix' => 'FILE_CACHE:',
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
            'prefix' => 'REDIS_CACHE:',
            // 缓存有效期 0表示永久缓存
            'expire' => 604800,
        ],
    ],
];