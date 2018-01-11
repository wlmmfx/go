<?php

/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/13 13:58
 * |  Mail: Overcome.wan@Gmail.com
 * |  Functuion: 腾讯云
 * '------------------------------------------------------------------------------------------------------------------*/

namespace live;


class TencentLive
{
    /**
     * 创建一个推流地址
     * @param $domainName
     * @param $appName
     * @param $expireTime
     * @param $authKeyStatus
     * @return array
     */
    public static function createPushFlowAddress($domainName, $appName, $expireTime, $authKeyStatus)
    {
        $sourceName = config('aliyun_api.CENTER_STREAM_ADDRESS');
        $streamName = '800' . time();
        $startTime = date('Y-m-d H:i:s', time());
        if ($authKeyStatus == 1) {
            $domainName = config('aliyun_api.ALI_AUTH_DOMAIN');
            $authUrl = self::getAuthPushUrl($sourceName, $domainName, $appName, $streamName, $startTime, $expireTime);
            $responseResult = [
                'domainName' => $domainName,
                'appName' => $appName,
                'streamName' => $streamName,
                'push_address' => $authUrl['push_flow_address'],
                'rtmp_address' => $authUrl['play_rtmp_address'],
                'flv_address' => $authUrl['play_flv_address'],
                'm3u8_address' => $authUrl['play_m3u8_address'],
                'startTime' => $startTime,
                'expireTime' => $expireTime,
                'createTime' => getCurrentDate()
            ];
            return $responseResult;
        }
        $authentication_url = self::getOpenPushUrl($sourceName, $domainName, $appName, $streamName);
        $responseResult = [
            'domainName' => $domainName,
            'appName' => $appName,
            'streamName' => $streamName,
            'push_address' => $authentication_url,
            'rtmp_address' => "rtmp://$domainName/$appName/$streamName",
            'flv_address' => "http://$domainName/$appName/$streamName.flv",
            'm3u8_address' => "http://$domainName/$appName/$streamName.m3u8",
            'expireTime' => $expireTime,
            'createTime' => getCurrentDate()
        ];
        return $responseResult;
    }

    /**
     * 禁止直播流推送
     * @param $DomainName 您的推流域名
     * @param $AppName 应用名称
     * @param $StreamName 流名称
     * @param $ResumeTime 恢复流的时间 UTC时间 格式：2015-12-01T17:37:00Z
     * @return mixed
     */
    public static function setAliForbidLiveStream($DomainName, $AppName, $StreamName, $ResumeTime)
    {
        $Action = 'ForbidLiveStream';
        $LiveStreamType = 'publisher';
        $accessKeyId =  config('aliyun_api.ACCESSKEYID');
        $cdn_server_address = 'https://cdn.aliyuncs.com';
        $parameters = [
            "Format" => "JSON",
            "Version" => "2014-11-11",
            "AccessKeyId" => $accessKeyId,
            "SignatureVersion" => "1.0",
            "SignatureMethod" => "HMAC-SHA1",
            "SignatureNonce" => self::uuid(),
            "TimeStamp" => gmdate('c'),
        ];
        //这几个参数应该是输入的 这里写死了
        $parameters['Action'] = $Action;
        $parameters['DomainName'] = $DomainName;
        $parameters['AppName'] = $AppName;
        $parameters['StreamName'] = $StreamName;
        $parameters['LiveStreamType'] = $LiveStreamType;
        $parameters['ResumeTime'] = $ResumeTime;

        //添加签名信息
        $parameters['Signature'] = self::getSign($parameters);
        //拼接url
        $url = $cdn_server_address . "/?" . http_build_query($parameters);
        $responseResult = json_decode(file_get_contents($url), true);
        return $responseResult;
    }

    /**
     * 恢复直播流推送
     * Help documentation：https://help.aliyun.com/document_detail/35414.html
     * Action：ResumeLiveStream
     * @param $DomainName
     * @param $AppName
     * @param $StreamName
     * @return array
     */
    public static function setAliResumeLiveStream($DomainName, $AppName, $StreamName)
    {
        $Action = 'ResumeLiveStream';
        $LiveStreamType = 'publisher';
        $accessKeyId = config('aliyun_api.ACCESSKEYID');
        $cdn_server_address = 'https://cdn.aliyuncs.com';
        $parameters = [
            "Format" => "JSON",
            "Version" => "2014-11-11",
            "AccessKeyId" => $accessKeyId,
            "SignatureVersion" => "1.0",
            "SignatureMethod" => "HMAC-SHA1",
            "SignatureNonce" => self::uuid(),
            "TimeStamp" => gmdate('c'),
        ];
        //这几个参数应该是输入的 这里写死了
        $parameters['Action'] = $Action;
        $parameters['DomainName'] = $DomainName;
        $parameters['AppName'] = $AppName;
        $parameters['StreamName'] = $StreamName;
        $parameters['LiveStreamType'] = $LiveStreamType;

        //添加签名信息
        $parameters['Signature'] = self::getSign($parameters);
        //拼接url
        $url = $cdn_server_address . "/?" . http_build_query($parameters);
        $responseResult = json_decode(file_get_contents($url), true);
        if (empty($responseResult)) return false;
        return $responseResult;
    }

    /**
     * 查看一段时间内某个域名（或域名下某应用）的推流记录
     * @param $DomainName
     * @param $AppName
     * @param $StreamName
     * @param $StartTime
     * @param $EndTime
     * @return array|null
     * @static
     */
    public static function DescribeLiveStreamsPublishList($DomainName, $AppName, $StreamName, $StartTime, $EndTime)
    {
        $Action = 'DescribeLiveStreamsPublishList';
        $result = self::aliYunMethod($Action, $DomainName, $AppName, $StreamName, $StartTime, $EndTime);
        $PublishInfo = json_decode(file_get_contents($result), true)['PublishInfo'];
        $LiveStreamPublishInfo = [];
        if (empty($PublishInfo['LiveStreamPublishInfo'])) return null;
        foreach ($PublishInfo['LiveStreamPublishInfo'] as $val) {
            $LiveStreamPublishInfo[] = [
                'EdgeNodeAddr' => $val['EdgeNodeAddr'],
                'PublishTime' => self::utcToPRC($val['PublishTime']),
                'StreamName' => $val['StreamName'],
                'ClientAddr' => $val['ClientAddr'],
                'PublishUrl' => $val['PublishUrl'],
                'StreamUrl' => $val['StreamUrl'],
                'StopTime' => self::utcToPRC($val['StopTime']),
                'DomainName' => $val['DomainName'],
                'AppName' => $val['AppName'],
            ];
        }
        return $LiveStreamPublishInfo;
    }

    /**
     * 获取鉴权签名字符串
     * @param $sourceName
     * @param $domainName
     * @param $appName
     * @param $streamName
     * @param $startTime
     * @param $expireTime
     * @return mixed
     */
    public function getAuthPushUrl($sourceName, $domainName, $appName, $streamName, $startTime, $expireTime)
    {
        $auth_key = config('aliyun_api.ALI_AUTH_PRIVATEKEY');
        $auth_timestatmp = strtotime(date('Y-m-d H:i:s', strtotime($startTime . "+" . $expireTime . " minute ")));
        $rtmp_auth_md5 = md5("/" . $appName . "/" . $streamName . "-" . $auth_timestatmp . "-0-0-" . $auth_key);
        $hls_auth_md5 = md5("/" . $appName . "/" . $streamName . ".m3u8-" . $auth_timestatmp . "-0-0-" . $auth_key);

        $authUrl['push_flow_address'] = "rtmp://$sourceName/$appName/$streamName?vhost=$domainName&auth_key=" . $auth_timestatmp . "-0-0-" . $rtmp_auth_md5;
        $authUrl['play_rtmp_address'] = "rtmp://$domainName/$appName/$streamName?auth_key=" . $auth_timestatmp . "-0-0-" . $rtmp_auth_md5;
        $authUrl['play_m3u8_address'] = "http://$domainName/$appName/$streamName.m3u8?auth_key=" . $auth_timestatmp . "-0-0-" . $hls_auth_md5;
        return $authUrl;
    }

    /**
     * 获取公开推流Url
     * @param $sourceName
     * @param $domainName
     * @param $appName
     * @param $streamName
     * @return string
     */
    public static function getOpenPushUrl($sourceName, $domainName, $appName, $streamName)
    {
        return "rtmp://{$sourceName}/$appName/$streamName?vhost=$domainName";
    }

    /**
     * 公共调用接口方法
     * @param $Action
     * @param $DomainName
     * @param $AppName
     * @param $StreamName
     * @param $StartTime
     * @param $EndTime
     * @return string
     * @static
     */
    public static function aliYunMethod($Action, $DomainName, $AppName, $StreamName, $StartTime, $EndTime)
    {
        $accessKeyId = config('aliyun_api.ACCESSKEYID');
        $cdn_server_address = config('aliyun_api.CDN_SERVER_ADDRESS');
        $parameters = [
            "Format" => "JSON",
            "Version" => "2014-11-11",
            "AccessKeyId" => $accessKeyId,
            "SignatureVersion" => "1.0",
            "SignatureMethod" => "HMAC-SHA1",
            "SignatureNonce" => self::uuid(),
            "TimeStamp" => gmdate('c'),
        ];
        //操作接口名，系统规定参数，取值：RefreshObjectCaches
        $parameters['Action'] = $Action;
        $parameters['DomainName'] = $DomainName;
        if ($AppName != null) $parameters['AppName'] = $AppName;
        if ($StreamName != null) $parameters['StreamName'] = $StreamName;
        if ($StartTime != null) $parameters['StartTime'] = $StartTime;
        if ($EndTime != null) $parameters['EndTime'] = $EndTime;

        //添加签名信息
        $parameters['Signature'] = self::getSign($parameters);
        //拼接url
        $url = $cdn_server_address . "/?" . http_build_query($parameters);
        return $url;
    }

    /**
     * 获取签名
     * @param $parameters
     * @return string
     */
    private static function getSign($parameters)
    {
        $accessKeySecret = config('aliyun_api.ACCESSKEYSECRET');
        //构造用来计算签名信息的字符串
        $stringToSign = self::det_stringToSign($parameters);
        //计算签名信息
        $signature = base64_encode(hash_hmac("SHA1", $stringToSign, $accessKeySecret . '&', true));
        return $signature;
    }

    /**
     * 计算构造用于签名的字符串
     * @param $parameters 参数
     * @return string  $stringToSign 用于计算签名的字符串
     */
    private static function det_stringToSign($parameters)
    {
        ksort($parameters);
        $cannibalizedQueryString = "";
        foreach ($parameters as $key => $value) {
            $cannibalizedQueryString = $cannibalizedQueryString . "&" . self::percent_encode($key) . "=" . self::percent_encode($value);
        }
        $cannibalizedQueryString = substr($cannibalizedQueryString, 1);
        $stringToSign = "GET&%2F&" . self::percent_encode($cannibalizedQueryString);
        return $stringToSign;
    }

    /**
     * 使用请求参数构造规范化的请求字符串
     * @param $str 输入的字符串
     * @return string 规范后的字符串
     * @static
     */
    private static function percent_encode($str)
    {
        return urlencode(mb_convert_encoding($str, 'utf-8', 'gb2312'));
    }

    /**
     * 生成UUID
     * @return mixed
     * @static
     */
    private static function uuid()
    {
        return preg_replace(
            '~^(.{8})(.{4})(.{4})(.{4})(.{12})$~',
            '\1-\2-\3-\4-\5',
            md5(uniqid('', true))
        );
    }

    /**
     * UTc时间转换为北京时间
     * @param $utcTime
     * @return false|string
     * @static
     */
    private static function utcToPRC($utcTime)
    {
        date_default_timezone_set('PRC');
        return date('Y-m-d H:i:s', strtotime($utcTime));
    }

    /**
     * 北京时间转换为UTc时间 3位 格式为：2015-12-01T17:36:00Z
     * @param $prcTime
     * @return false|string
     * @static
     */
    private static function prcToUtc($prcTime)
    {
        return gmdate("Y-m-d\TH:i:s\Z", strtotime($prcTime));
    }

}