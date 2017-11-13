<?php
/*
  +------------------------------------------------------------------------+
  | BaseRedis Class                                                      |
  +------------------------------------------------------------------------+
  | Copyright (c) 2015-2016 Amai Team (http://www.amailive.com/)       |
  +------------------------------------------------------------------------+
  Authors: Tinywan <ovecome.wan@gmail.com>
  +------------------------------------------------------------------------+
*/

namespace redis;

class BaseRedis
{

    /**
     * 类对象实例数组,共有静态变量
     * @var null
     */
    private static $_instance;

    /**
     * 私有化构造函数，防止类外实例化
     * BaseRedis constructor.
     */
    private function __construct()
    {
    }

    /**
     *  单例方法,用于访问实例的公共的静态方法
     *  这个实例方法适合于开发者自由的连接到自己相连接的Redis数据库中去。列如：在项目中选择不同的Redis数据库,127.0.0.1
     * @return \Redis
     * @static
     * eg:
     * <pre>
     * $redis = BaseRedis::Instance();
     * $redis->connect('127.0.0.1', '6379');
     * $redis->auth('tinywanredis');
     * $redis->set('name','value');
     * </pre>
     */
    public static function instance()
    {
        try {
            if (!(static::$_instance instanceof \Redis)) {
                static::$_instance = new \Redis();
            }
            return static::$_instance;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * 保存Session实例
     * @return \Redis
     * @static
     * <pre>
     * $redis = BaseRedis::SessionInstance();
     * $redis->set('key', 'value');
     * </pre>
     */
    public static function session()
    {
        try {
            $_connectSource = self::instance()->connect(self::ConfigFile()->redis->redis->path, self::ConfigFile()->redis->redis->port);
            if ($_connectSource === FALSE) return FALSE;
            return static::$_instance;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     *  LocationInstance  实例
     * @return \Redis
     * @static
     */
    public static function location()
    {
        try {
            $_connectSource = self::instance()->connect('127.0.0.1', '6379');
            if ($_connectSource === FALSE) return FALSE;
            return static::$_instance;
        } catch (\Exception $e) {
            return false;
        }
    }

}