<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/1/12 22:29
 * |  Mail: Overcome.wan@Gmail.com
 * |  Fun: 设计模式---装饰器模式
 * |  目的: 将一个类的接口转换为兼容的接口。一个适配器允许类一起工作，通常不会因为不兼容的接口，通过在使用原始接口的同时向客户端提供接口。
 * '------------------------------------------------------------------------------------------------------------------*/

namespace live;


interface Live
{
    /**
     * 创建一个推流地址
     * @param $sourceName
     * @param $domainName
     * @param $appName
     * @param $expireTime
     * @param $authKeyStatus
     * @param $cdn
     * @return mixed
     */
    public static function createPushFlowAddress($sourceName, $domainName, $appName, $expireTime, $authKeyStatus, $cdn);

    /**
     * Auth 签名推流地址（授权）
     * @param $sourceName
     * @param $domainName
     * @param $appName
     * @param $streamName
     * @param $startTime
     * @param $expireTime
     * @param $cdn
     * @return mixed
     */
    public static function getAuthPushUrl($sourceName, $domainName, $appName, $streamName, $startTime, $expireTime, $cdn);
}