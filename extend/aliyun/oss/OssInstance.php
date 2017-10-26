<?php

/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/9/4 22:28
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm
 * '-------------------------------------------------------------------*/

namespace aliyun\oss;

use OSS\Core\OssException;
use OSS\OssClient;

class OssInstance
{
    /**
     * 类对象实例数组,共有静态变量
     * @var null
     */
    private static $_oss_instance;

    /**
     * 私有化构造函数，防止类外实例化
     */
    private function __construct()
    {
    }

    /**
     * 单例方法,用于访问实例的公共的静态方法
     * @return bool|null|OssClient
     * @static
     */
    public static function Instance()
    {
        if (is_object(self::$_oss_instance)) return self::$_oss_instance;
        try {
            self::$_oss_instance = new OssClient(config('aliyun_oss.ACCESSKEYID'), config('aliyun_oss.ACCESSKEYSECRET'), config('aliyun_oss.ENDPOINT_INTERNAL'));
        } catch (OssException $e) {
            return false;
        }
        return self::$_oss_instance;
    }

    /**
     * 私有化克隆函数，防止类外克隆对象
     */
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }
}