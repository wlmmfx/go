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

class RedisHandleController
{
    /**
     * demo1
     */
    public function incr()
    {
        $loop = Factory::create();
        $factory = new \Clue\React\Redis\Factory($loop);
        $factory->createClient('redis://172.19.230.35:6379')->then(function (Client $client) use ($loop) {
            $client->set('greeting', 'Hello world');
            $client->append('greeting', '!');
            $client->get('greeting')->then(function ($greeting) {
                // Hello world!
                echo $greeting . PHP_EOL;
            });

            $client->incr('invocation')->then(function ($n) {
                echo 'This is invocation #' . $n . PHP_EOL;
            });
            // end connection once all pending requests have been resolved
            $client->end();
        });
        $loop->run();

    }

    /**
     * 发布
     */
    public function publish()
    {
        $loop = Factory::create();
        $factory = new \Clue\React\Redis\Factory($loop);
        $channel = isset($argv[1]) ? $argv[1] : 'channel';
        $message = isset($argv[2]) ? $argv[2] : 'message';
        $factory->createClient('redis://172.19.230.35:6379')->then(function (Client $client) use ($channel, $message) {
            $client->publish($channel, $message)->then(function ($received) {
                echo 'successfully published. Received by ' . $received . PHP_EOL;
            });
            $client->end();
        });
        $loop->run();
    }

    /**
     * 订阅
     */
    public function subscribe()
    {
        $loop = Factory::create();
        $factory = new \Clue\React\Redis\Factory($loop);
        $channel = isset($argv[1]) ? $argv[1] : 'channel';
        $factory->createClient('redis://172.19.230.35:6379')->then(function (Client $client) use ($channel) {
            $client->subscribe($channel)->then(function () {
                echo 'Now subscribed to channel ' . PHP_EOL;
            });
            $client->on('message', function ($channel, $message) {
                echo 'Message on ' . $channel . ': ' . $message . PHP_EOL;
            });
        });
        $loop->run();
    }
}