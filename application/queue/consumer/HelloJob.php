<?php

/**
 * Created for thinkphp5-study-line.
 * File: HelloJob.php
 * User: cools
 * Date: 2017/8/6 0006
 * Time: 14:03
 * Description :
 */
namespace app\queue\consumer;

use think\Exception;

class HelloJob
{
    public function fire( $job ,$payload)
    {
        $done = $this->consume($payload);
        if ($done) {
            $this->deleteJob($job);
        }else{
            $this->releaseJob($job);
        }
    }

    private function consume( $payload )
    {
        try {
            echo ' Job Processed!' . ' payload: '. PHP_EOL . var_export($payload,true) .PHP_EOL;
            return true;
        } catch (\Exception $e) {
            echo 'Hello Job Process Failed!' ;
            var_dump($e);
            return false;
        }
    }

    private function deleteJob( $job )
    {
        $job->delete();
    }

    private function releaseJob( $job )
    {
        $job->release(3);
    }
}