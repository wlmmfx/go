<?php

/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/15 16:22
 * |  Mail: Overcome.wan@Gmail.com
 * |  Function: 本地化调试输出到文件
 * |  Des: 以日志扩展为例，我们来扩展一个日志驱动满足项目的实际需求，该日志驱动会把指定的日志信息发送到指定邮箱，驱动类实现如下
 * '------------------------------------------------------------------------------------------------------------------*/

namespace drive\log;


class Email
{
    /**
     * @var array
     */
    protected $config = [
        'time_format' => 'time',
        'email_addr' => '756684177@qq.com',
        'send_level' => ['error'],
    ];

    /*
     * 实例化并传入参数
     */
    public function __construct($config = [])
    {
        if (is_array($config)) {
            $this->config = array_merge($config);
        }
    }

    /**
     * 日志写入接口
     * @param array $log 日志信息
     * @return bool
     */
    public function save(array $log = [])
    {
        $now = date($this->config['time_format']);
        $info = '';
        $server = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : '0.0.0.0';
        $remote = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        $method = isset($_SERVER["REQUEST_METHOD"]) ? $_SERVER["REQUEST_METHOD"] : '0.0.0.0';
        $uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        foreach ($log as $type => $val) {
            if (in_array($type, $this->config['send_level'])) {
                foreach ($val as $msg) {
                    if (!is_string()) {
                        $msg = var_dump($msg, true);
                    }
                    $info .= '[' . $type . ']' . $msg . '<br/>';
                }
            }
        }
        // error_log 发送一个错误信息到Web服务器的错误日志，一个TCP的端口或者是一个文件.
        return error_log("[{$now}] {$server} {$remote} {$method} {$uri}<div>{$info}</div>", 1, $this->config['email_addr'], "Subject: Log-{$now}");
    }
}