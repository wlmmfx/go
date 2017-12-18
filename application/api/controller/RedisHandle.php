<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/18 17:45
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\controller;


use Clue\React\Redis\Client;
use React\EventLoop\Factory;

class RedisHandle
{
    public function incr(){
        $loop = Factory::create();
        $factory = new \Clue\React\Redis\Factory();
        $factory->createClient('redis://172.19.230.35:63789')->then(function (Client $client) {
            $client->auth('MMiTUXQLfWmycmSG3YSTAgtEMFAVFFnQ91r7QuC5Q38qribjE1nit6Jckes9oHaiCrWVvpUNVgM08SH7b8V61A==');
            $client->incr('test123');
            $client->get('test123')->then(function ($result) {
                var_dump($result);
            });
            $client->end();
        }, function (\Exception $e) {
            echo "an error occurred while trying to connect (or authenticate) client";
        });
        $loop->run();
    }
}