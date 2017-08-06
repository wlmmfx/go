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

use think\Queue;
use app\queue\consumer\HelloJob;
class Demo
{
    /**
     * push a new job into queue
     */
    public function index(  )
    {
        $consumer = HelloJob::class;
        $payload = ['time' => time()];
        $queue = 'helloJobQueue';

        $pushed = Queue::push($consumer ,$payload ,$queue);
        if ($pushed) {
            echo 'job Pushed.' . '<br/>' .'job payload :' . '<br/>' .var_export($payload,TRUE);
        }else{
            echo 'Oops, something wrong with your queue';
        }
    }
}