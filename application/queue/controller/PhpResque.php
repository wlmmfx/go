<?php
/**
 * Created by PhpStorm.
 * User: Tinywan
 * Date: 2017/8/7
 * Time: 9:01
 * Mail: Overcome.wan@Gmail.com
 */

namespace app\queue\controller;


use app\queue\consumer\MyJob;

class PhpResque
{
    public function index()
    {
        return "php-resque index".__METHOD__;
    }

    /**
     * 将Job插入队列
     */
    public function addQueue(){

        /**
         * [1] 给定一个由冒号分隔的主机/端口组合，将其设置为Resque将要处理的redis服务器。
         *
         * @param mixed $server Host/port combination separated by a colon, or
         *                      a nested array of servers with host/port pairs.
         * @param int $database
         */
        \Resque::setBackend('localhost:6379',0);

        /**
         * [2] 类名称
         */
        $consumer = MyJob::class;

        /**
         * [3] 参数
         */
        $args = array(
            'time' => time(),
            'array' => array(
                'test' => 'test',
            ),
        );

        /**
         * [4] 创建新作业并将其保存到指定的队列中
         *
         * @param string $queue 将作业放入队列的名称。
         * @param string $class 包含执行作业的代码的类的名称。
         * @param array $args 在执行作业时应该传递的任何可选参数。
         * @param boolean $trackStatus 设置为true以能够监视作业的状态。
         *
         * @return string
         */
        $jobId = \Resque::enqueue('default', $consumer, $args, true);
        echo "Queued job ".$jobId."\n\n";
    }
}