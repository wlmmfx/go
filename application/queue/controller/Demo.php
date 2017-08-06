<?php

/**
 * Created for thinkphp5-study-line.
 * File: Demo.php
 * User: cools
 * Date: 2017/8/6 0006
 * Time: 14:02
 * Description :
 */
namespace app\queue\controller;

use think\Log;
use think\Queue;
use app\queue\consumer\HelloJob;
class Demo
{
    /**
     * push a new job into queue
     */
    public function test(  )
    {
        return "test";
    }

    public function index(  )
    {
        $consumer = HelloJob::class;    // app\queue\consumer\HelloJob
        $link = "http://www.baidu.com/backend/login/emailRegisterUrlValid?checkstr=11111111111&auth_key=909090909090";
        $payLoad = [
            'time' => time(),
            'mail' => "1722318623@qq.com",
            'url' => $link,
            ];
        $queue = 'helloJobQueue123';

        $pushed = Queue::push($consumer ,$payLoad ,$queue);
        if ($pushed) {
            Log::error("[2000000000000000000001]邮件队列发布结果：".$pushed);
            echo 'job Pushed.' . '<br/>' .'job payload :' . '<br/>' .var_export($payLoad,TRUE);
        }else{
            echo 'Oops, something wrong with your queue';
        }
    }
}