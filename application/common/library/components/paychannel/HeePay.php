<?php

/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/6/1 22:41
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/
namespace  app\common\library\components\paychannel;

class HeePay
{
    //渠道配置
    public $channelId = 5;

    //银联网关
    const unionpayGateway = 20;

    public function test(){
        return 'test';
    }
}