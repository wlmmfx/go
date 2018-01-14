<?php

/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/1/4 14:00
 * |  Mail: Overcome.wan@Gmail.com
 * |  Fun: Redis单例模式
 * |  单例模式元素:
 * |    1.它们拥有一个保存类的实例的静态成员变量 $_instance
 * |    2.它们必须拥有一个构造函数，并且必须被标记为 private
 * |    3.它们拥有一个访问这个实例的公共的静态方法 instance()
 * |    5.单例类不能再其它类中直接实例化，只能被其自身实例化。它不会创建实例副本，而是会向单例类内部存储的实例返回一个引用。
 * '------------------------------------------------------------------------------------------------------------------*/

namespace redis;

use think\Exception;

class BaseRedis
{

    /**
     * $_instance 必须声明为静态的私有变量
     * @var null
     */
    private static $_instance;

    /**
     * 构造函数声明为private,防止直接创建对象
     * BaseRedis constructor.
     */
    private function __construct()
    {
        echo 'I am Constructed';
    }

    /**
     * 单例方法,用于访问实例的公共的静态方法
     * 这个实例方法适合于开发者自由的连接到自己相连接的Redis数据库中去。列如：在项目中选择不同的Redis数据库,127.0.0.1
     * @static
     * @return \Redis
     * @throws Exception
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
            if (!class_exists('redis', false)) {
                throw new Exception("必须安装redis扩展，请检查扩展是否安装");
            }
            if (!(static::$_instance instanceof \Redis)) {
                static::$_instance = new \Redis();
            }
            return static::$_instance;
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), 100006);
        }
    }

    /**
     * 阻止用户复制对象实例
     */
    private function __clone()
    {
        // TODO: Implement __clone() method.
        trigger_error('Clone is not allow' ,E_USER_ERROR);
    }

    private function __wakeup()
    {
        // TODO: Implement __wakeup() method.
    }

    /**
     * 消息Redis实例
     * @return \Redis
     * @static
     * <pre>
     * $redis = BaseRedis::message();
     * $redis->set('key', 'value');
     * </pre>
     */
    public static function message()
    {
        try {
            $_connectSource = self::instance()->connect(config('redis.message')['host'], config('redis.message')['port']);
            if (config('redis.message')['auth']) {
                self::instance()->auth(config('redis.message')['auth']);
            }
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