<?php

/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/1/11 19:57
 * |  Mail: Overcome.wan@Gmail.com
 * |  Fun: 总直播接口
 * '------------------------------------------------------------------------------------------------------------------*/

namespace live;


class LiveStream
{
    private $name;
    private $description;
    private $publishUrls;
    private $playUrls;
    private $status;
    private $lastModified;

    /**
     * 流创建地址
     * @param $domainName
     * @param $appName
     * @param $expireTime
     * @param $authKeyStatus
     * @param $serviceProvider 1：Tinywan私有云，2：阿里云，3：七牛云，4：腾讯云，5：华为云，6：美团云
     * @return array|null
     */
    public static function createPushFlowAddress($domainName, $appName, $expireTime, $authKeyStatus, $serviceProvider)
    {
        switch ($serviceProvider) {
            case 1:
                $responseInfo = PrivateCloudLive::createPushFlowAddress($sourceName = 'live.tinywan.com', $domainName, $appName, $expireTime, $authKeyStatus, $cdn = 'live.tinywan.com');
                break;
            case 2:
                $responseInfo = AliLive::createPushFlowAddress($sourceName = 'stream.tinywan.com', $domainName, $appName, $expireTime, $authKeyStatus, $cdn = 'live.tinywan.com');
                break;
            case 3:
                $responseInfo = QiNiuLive::createPushFlowAddress($sourceName = 1, $domainName, $appName, $expireTime, $authKeyStatus, $cdn = 1);
                break;
            case 4:
                $responseInfo = TencentLive::createPushFlowAddress($sourceName = 1, $domainName, $appName, $expireTime, $authKeyStatus, $cdn = 1);
                break;
            default:
                $responseInfo = 123;
        }
        return $responseInfo;
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
    protected function getAuthPushUrl($sourceName, $domainName, $appName, $streamName, $startTime, $expireTime, $cdn)
    {
        $auth_key = 'tinywan123';
        $timestatmp = strtotime(date('Y-m-d H:i:s', strtotime($startTime . "+" . $expireTime . " minute ")));
        $rtmp_auth_md5 = md5("/" . $appName . "/" . $streamName . "-" . $timestatmp . "-0-0-" . $auth_key);
        $hls_auth_md5 = md5("/" . $appName . "/" . $streamName . ".m3u8-" . $timestatmp . "-0-0-" . $auth_key);

        $authUrl['push_flow_address'] = "rtmp://$sourceName/$appName/$streamName?vhost=$domainName&auth_key=" . $timestatmp . "-0-0-" . $rtmp_auth_md5;
        $authUrl['play_rtmp_address'] = "rtmp://$domainName/$appName/$streamName?auth_key=" . $timestatmp . "-0-0-" . $rtmp_auth_md5;
        $authUrl['play_m3u8_address'] = "http://$cdn/$appName/$streamName.m3u8?auth_key=" . $timestatmp . "-0-0-" . $hls_auth_md5;
        $authUrl['hash_value'] = 'startTime = ' . $startTime . ' expireTime = ' . $expireTime . ' timestatmp = ' . $timestatmp . "uri = /" . $appName . "/" . $streamName . ".m3u8-" . $timestatmp . "-0-0-" . $auth_key;
        return $authUrl;
    }

    /**
     * 获取auth_key
     */
    protected function getAuthKey($appName, $streamName, $expireTime)
    {
        $redis = messageRedis();
        $private_key = $redis->get("private_key");
        $timestatmp = strtotime(date('Y-m-d H:i:s', strtotime("+" . $expireTime . "minute")));
        $uri = "/" . $appName . '/' . $streamName;
        $rand = 0;
        $uid = 0;
        $hash_value = md5($uri . '-' . $timestatmp . '-' . $rand . '-' . $uid . '-' . $private_key);
        $auth_key = $timestatmp . '-' . $rand . '-' . $uid . '-' . $hash_value;
        return $auth_key;
    }

    /**
     * 公开推流地址（未授权）
     * @param $domainName
     * @param $appName
     * @param $streamName
     * @return string
     */
    protected function getOpenPushUrl($sourceName, $domainName, $appName, $streamName, $cdn)
    {
        $authUrl['push_flow_address'] = "rtmp://$sourceName/$appName/$streamName";
        $authUrl['play_rtmp_address'] = "rtmp://$domainName/$appName/$streamName";
        $authUrl['play_m3u8_address'] = "http://$cdn/$appName/$streamName.m3u8";
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

    /**
     * 根据流名称获取直播流信息
     */
    public static function getLiveStreamNameStatus($streamName)
    {
        $url = "https://live.tinywan.com/stat";
        $output = curl_request($url);
        $outputs = self::FromXml($output);
        // 是否有流
        $totalInfo = [];
        if ($outputs['server']['application'][3]['live']['nclients'] == 0) {
            $totalInfo['status'] = 0;
            $totalInfo['message'] = '没有打流1';
            return $totalInfo;
        }
        $streamInfo = $outputs['server']['application'][3]['live']['stream'];

        // 单个流
        if (isset($streamInfo['name']) && ($streamInfo['name'] == $streamName)) {
            $totalInfo['status'] = 1;
            $totalInfo['message'] = '正在打流1';
            $totalInfo['dataList']['name'] = $streamName;
            $totalInfo['dataList']['bw_in'] = $streamInfo['bw_in'];
            $totalInfo['dataList']['bw_out'] = $streamInfo['bw_out'];
        } elseif (isset($streamInfo['name']) && ($streamInfo['name'] != $streamName)) {
            $totalInfo['status'] = 0;
            $totalInfo['message'] = '没有打流3';
            $totalInfo['dataList']['name'] = $streamName;
            $totalInfo['dataList']['bw_in'] = 0;
            $totalInfo['dataList']['bw_out'] = 0;
        } else {
            $streamArr = [];
            foreach ($streamInfo as $value) {
                $streamArr[] = $value['name'];
            }
            if (in_array($streamName, $streamArr)) {
                $totalInfo['status'] = 1;
                $totalInfo['message'] = '正在打流2';
                $totalInfo['dataList']['name'] = $streamName;
            } else {
                $totalInfo['status'] = 0;
                $totalInfo['message'] = '没有打流3';
                $totalInfo['dataList']['name'] = $streamName;
                $totalInfo['dataList']['bw_in'] = 0;
                $totalInfo['dataList']['bw_out'] = 0;
            }
        }
        return $totalInfo;
    }

    /**
     * 获取录像直播视频状态
     * @param $streamName
     * @return mixed
     * @static
     */
    public static function getRecordLiveStreamNameStatus($streamName)
    {
        $url = "https://live.tinywan.com/stat";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        // 使用curl_exec()之前跳过ssl检查项
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $output = curl_exec($ch);
        curl_close($ch);
        $outputs = self::FromXml($output);
        // 单个
        if ($outputs['server']['application'][3]['live']['nclients'] == 0) {
            $totalInfo['status'] = 0;
            $totalInfo['message'] = '没有打流';
            return $totalInfo;
        }
        $streamInfo = $outputs['server']['application'][3]['live']['stream'];
        // 一个流
        if (array_key_exists('name', $streamInfo) == 1) {
            if ($streamInfo['name'] == $streamName) {
                $totalInfo['status'] = 1;
                $totalInfo['message'] = '正在打流';
                $totalInfo['dataList']['name'] = $streamInfo['name'];
                $totalInfo['dataList']['bw_in'] = $streamInfo['bw_in'];
                $totalInfo['dataList']['bw_out'] = $streamInfo['bw_out'];
            } else {
                $totalInfo['status'] = 0;
                $totalInfo['message'] = '没有打流';
                $totalInfo['dataList']['name'] = $streamName;
                $totalInfo['dataList']['bw_in'] = 0;
                $totalInfo['dataList']['bw_out'] = 0;
            }
        } else { // 多个流
            $streamNameArr = array();
            //存放所有的设备号到一个数组中
            foreach ($streamInfo as $key => $val) {
                $streamNameArr[] = $val['name'];
            }
            //判断该设备是否在这个数组中，真：获取这个设备的所有打流信息
            if (in_array($streamName, $streamNameArr)) {
                $totalInfo['status'] = 1;
                $totalInfo['message'] = '正在打流';
                foreach ($streamInfo as $val) {
                    if ($val['name'] == $streamName) {
                        $totalInfo['dataList']['name'] = $val['name'];
                        $totalInfo['dataList']['bw_in'] = $val['bw_in'];
                        $totalInfo['dataList']['bw_out'] = $val['bw_out'];
                    }
                }
            } else {
                $totalInfo['status'] = 0;
                $totalInfo['message'] = '没有打流';
                $totalInfo['dataList']['name'] = $streamName;
                $totalInfo['dataList']['bw_in'] = 0;
                $totalInfo['dataList']['bw_out'] = 0;
            }
        }
        return $totalInfo;
    }

    /**
     * XML格式转换
     * @param $xml
     * @return mixed
     */
    public static function FromXml($xml)
    {
        if (!$xml) {
            $totalInfo['status'] = 0;
            $totalInfo['message'] = '没有打流';
            $totalInfo['dataList'] = null;
            return $totalInfo;
        }
        //将XML转为array
        $values = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $values;
    }
}