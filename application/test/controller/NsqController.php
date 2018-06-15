<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/5/28 19:58
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\test\controller;


use think\App;

class NsqController
{
    public function pub()
    {
        $nsqAddr = array(
            "nsqadmin.tinywan.com:4150",
            "nsqadmin.tinywan.com:4151"
        );

        $nsq = new \Nsq();
        if( true == $nsq->connectNsqd($nsqAddr)) {
            for($i = 0; $i < 10; $i++){
                $nsq->publish("test", "Hi PHP-Nsq ".time());
            }
            $nsq->closeNsqdConnection();

            // Deferred publish
            //function : deferredPublish(string topic,string message, int millisecond);
            //millisecond default : [0 < millisecond < 3600000]

            $deferred = new \Nsq();
            if( true == $deferred->connectNsqd($nsqAddr)){
                for($i = 0; $i < 20; $i++){
                    $deferred->deferredPublish("test", "message daly", 3000);
                }
                $deferred->closeNsqdConnection();
            }
        }
    }

    public function sub()
    {
        $nsq_lookupd = new \NsqLookupd("nsqadmin.tinywan.com:4160"); //the nsqlookupd http addr

        $nsq = new \Nsq();
        $config = [
            "topic" => "test",
            "channel" => "struggle",
            "rdy" => 2,                //optional , default 1
            "connect_num" => 1,        //optional , default 1
            "retry_delay_time" => 5000,  //optional, default 0 , if run callback failed, after 5000 msec, message will be retried
            "auto_finish" => true, //default true
        ];

        $nsq->subscribe($nsq_lookupd, $config, function($msg,$bev){
            //var_dump($msg);
        });
    }

    public function redis()
    {
        $redis = messageRedis();
        var_dump($redis->keys("*"));
    }

    public function app123()
    {
        $class = \app\common\library\components\paychannel\HeePay::class; // app\common\library\components\paychannel\HeePay
        $pay = App::invokeClass($class);
        var_dump($pay->channelId);
        var_dump($pay->test());
    }
}