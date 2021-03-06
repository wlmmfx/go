<?php

/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/1/11 19:57
 * |  Mail: Overcome.wan@Gmail.com
 * |  Mail: Tinywan 私有云 专用服务器直播接口
 * '------------------------------------------------------------------------------------------------------------------*/

namespace live;


use think\Log;

class PrivateCloudLive
{
    /**
     * Tinywan专用 流创建地址
     * @param $sourceName
     * @param $domainName
     * @param $appName
     * @param $expireTime
     * @param $authKeyStatus
     * @param string $cdn
     * @return array
     */
    public static function createPushFlowAddress($sourceName, $domainName, $appName, $expireTime, $authKeyStatus, $cdn)
    {
        $streamName = '136' . time();
        $startTime = date('Y-m-d H:i:s', time());
        if ($authKeyStatus == 1) {
            $authUrl = self::getAuthPushUrl($sourceName, $domainName, $appName, $streamName, $startTime, $expireTime, $cdn);
            $responseResult = [
                'domainName' => $domainName,
                'appName' => $appName,
                'streamName' => $streamName,
                'push_flow_address' => $authUrl['push_flow_address'],
                'play_rtmp_address' => $authUrl['play_rtmp_address'],
                'play_flv_address' => $authUrl['play_flv_address'],
                'play_m3u8_address' => $authUrl['play_m3u8_address'],
                'hash_value' => $authUrl['hash_value'],
                'startTime' => $startTime,
                'expireTime' => $expireTime,
                'createTime' => getCurrentDate()
            ];
            return $responseResult;
        }
        $pushUrl = self::getOpenPushUrl($sourceName, $domainName, $appName, $streamName, $cdn);
        $responseResult = [
            'domainName' => $domainName,
            'appName' => $appName,
            'streamName' => $streamName,
            'push_flow_address' => $pushUrl['push_flow_address'],
            'play_rtmp_address' => $pushUrl['play_rtmp_address'],
            'play_flv_address' => $pushUrl['play_flv_address'],
            'play_m3u8_address' => $pushUrl['play_m3u8_address'],
            'expireTime' => $expireTime,
            'createTime' => getCurrentDate()
        ];
        return $responseResult;
    }

    /**
     * Auth 签名推流地址（授权）
     * url：rtmp://192.62.182.199/live/4001493201083?vhost=10.51.10.172&auth_key=1507083010-0-0-149455501042df7c8c6f5b3fba30c6dda331a6224a
     * uri：/live/4001494568753.m3u8-9191954935-0-0-Tinywan123
     * @param $sourceName
     * @param $domainName
     * @param $appName
     * @param $streamName
     * @param $startTime
     * @param $expireTime
     * @param $cdn
     * @return mixed
     */
    public static function getAuthPushUrl($sourceName, $domainName, $appName, $streamName, $startTime, $expireTime, $cdn)
    {
        $redis = messageRedis();
        $authKey = $redis->get("PRIVATE_CLOUD_LIVE_PRIVATE_KEY");
        $rand = 0;
        $uid = 0;
        $timestatmp = strtotime(date('Y-m-d H:i:s', strtotime($startTime . "+" . $expireTime . " minute ")));

        $rtmp_auth_md5 = md5("/" . $appName . "/" . $streamName . "-" . $timestatmp . "-".$rand."-".$uid."-" . $authKey);
        $hls_auth_md5 = md5("/" . $appName . "/" . $streamName . ".m3u8-" . $timestatmp .  "-".$rand."-".$uid."-" . $authKey);
        $flv_auth_md5 = md5("/" . $appName . "/" . $streamName . ".flv-" . $timestatmp .  "-".$rand."-".$uid."-" . $authKey);

        $authUrl['push_flow_address'] = "rtmp://$sourceName/$appName/$streamName?vhost=$domainName&auth_key=" . $timestatmp . "-".$rand."-".$uid."-" . $rtmp_auth_md5;
        $authUrl['play_rtmp_address'] = "rtmp://$domainName/$appName/$streamName?auth_key=" . $timestatmp .  "-".$rand."-".$uid."-" . $rtmp_auth_md5;
        $authUrl['play_m3u8_address'] = "http://$cdn/$appName/$streamName.m3u8?auth_key=" . $timestatmp .  "-".$rand."-".$uid."-" . $hls_auth_md5;
        $authUrl['play_flv_address'] = "http://$cdn/$appName/$streamName.flv?auth_key=" . $timestatmp .  "-".$rand."-".$uid."-" . $flv_auth_md5;
        $authUrl['hash_value'] = 'startTime = ' . $startTime . ' expireTime = ' . $expireTime . ' timestatmp = ' . $timestatmp . "uri = /" . $appName . "/" . $streamName . ".m3u8-" . $timestatmp .  "-".$rand."-".$uid."-" . $authKey;
        return $authUrl;
    }

    /**
     * 公开推流地址（未授权）
     * @param $domainName
     * @param $appName
     * @param $streamName
     * @return string
     */
    protected static function getOpenPushUrl($sourceName, $domainName, $appName, $streamName, $cdn)
    {
        $authUrl['push_flow_address'] = "rtmp://$sourceName/$appName/$streamName";
        $authUrl['play_rtmp_address'] = "rtmp://$domainName/$appName/$streamName";
        $authUrl['play_m3u8_address'] = "http://$cdn/$appName/$streamName.m3u8";
        $authUrl['play_flv_address'] = "http://$cdn/$appName/$streamName.flv";
        return $authUrl;
    }

    /**
     * 禁止直播流推送API(剔流处)
     * @param string $AppName
     * @param string $StreamName
     * @param $requestResumeTime
     * @return mixed
     */
    public function setForbidLiveStream($AppName = 'live', $StreamName = '4001482388912', $requestResumeTime)
    {
        $redis = messageRedis();
        $drop_url = $redis->hGet('StreamDropUrl:' . $StreamName, 'drop_url');
        $url = "http://{$drop_url}/controllortnoc/drop/client?app={$AppName}&name={$StreamName}";
        /*
         * 哈哈，我这里想出了一个办法，就是禁播的话直接给他权限不就得了吗？不是有个过期时间吗？这不就是过期事件吗？不过我在这里给数据库添加一个字段
         * pushStream = 1 权限被禁掉了
         */
        $ch = curl_init() or die (curl_error());
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 360);
        $response = curl_exec($ch);
        curl_close($ch);
        $redis->hMset('SIGNTOKEN:' . $StreamName, [
            'pushStream' => 0,
            'controllortnoc' => json_encode($response)
        ]);
        //添加推流黑名单
        $redis->zAdd('BlackList', time(), $StreamName);
        $ResumeTime = strtotime($requestResumeTime) - time();
        //这里执行一个定时任务，定时执行任务php通知消息
        $result = $redis->multi()
            ->select(0)
            ->setex('40001:' . $StreamName, $ResumeTime, $drop_url)
            ->exec();
        return $result;
    }

    /**
     * 【08】恢复直播流推送API(剔流处),公共方法调取使用
     * @return bool|void setAMaiForbidLiveStream
     */
    public function setResumeLiveStream($StreamName)
    {
        $redis = messageRedis();
        $result = $redis->hSet('SIGNTOKEN:' . $StreamName, 'pushStream', 0);
        //移除推流黑名单
        $redis->zRem('BlackList', $StreamName);
        return $result;
    }


}