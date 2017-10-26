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
use think\Log;

class Oss
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
     * 创建存储空间
     * @param string $bucket 存储空间名称
     * @return null
     */
    public static function createBucket($bucket)
    {
        try {
            static::$_oss_instance->createBucket($bucket);
        } catch (OssException $e) {
            Log::error('OSS--创建存储空间异常-------------------'.json_encode($e->getMessage()));
            return false;
        }
        return true;
    }

    /**
     * 上传文件
     * @param string $bucket 存储空间名称
     * @return null
     */
    public static function putObject($bucket, $object, $content)
    {
        try {
            static::$_oss_instance->putObject($bucket, $object, $content);
        } catch (OssException $e) {
            Log::error('OSS--上传文件-------------------'.json_encode($e->getMessage()));
            return false;
        }
        return true;
    }

    /**
     * 上传文件
     * @param string $bucket 存储空间名称
     * @return null
     */
    public static function uploadDir($bucket, $prefix, $localDirectory)
    {
        try {
            static::$_oss_instance->uploadDir($bucket, $prefix, $localDirectory);
        } catch (OssException $e) {
            Log::error('OSS--上传文件-------------------'.json_encode($e->getMessage()));
            return false;
        }
        return true;
    }

    /**
     * 下载文件
     * @param string $bucket 存储空间名称
     * @return null
     */
    public static function getObject($bucket, $object)
    {
        try {
            static::$_oss_instance->getObject($bucket, $object);
        } catch (OssException $e) {
            Log::error('OSS--下载文件-------------------'.json_encode($e->getMessage()));
            return false;
        }
        return true;
    }

    /**
     * 删除操作
     * @param string $bucket 存储空间名称
     * @param string $object 获取的对象
     * @return null
     */
    public static function deleteObject($bucket, $object)
    {
        try {
            static::$_oss_instance->deleteObject($bucket, $object);
        } catch (OssException $e) {
            Log::error('OSS--删除操作-------------------'.json_encode($e->getMessage()));
            return false;
        }
        return true;
    }

    /**
     * 私有化克隆函数，防止类外克隆对象
     */
    private function __clone()
    {
        // TODO: Implement __clone() method.
    }
}