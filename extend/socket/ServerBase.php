<?php

/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/5 16:25
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/
abstract class ServerBase
{
    protected $swoole;
    protected $serverType;
    protected $sockType;
    protected $mode;
    protected $host = '0.0.0.0';
    protected $port = 9501;
    protected $option = [];

    /**
     * 架构函数
     * @access public
     */
    public function __construct()
    {
        // 实例化 Swoole 服务
        switch ($this->serverType) {
            case 'socket':
                $this->swoole = new swoole_websocket_server($this->host, $this->port);
                $eventList = ['Open', 'Message', 'Close', 'HandShake', 'Request'];
                break;
            case 'http':
                $this->swoole = new \swoole_http_server($this->host, $this->port);
                $eventList = ['Request'];
                break;
            default:
                $this->swoole = new \swoole_server($this->host, $this->port, $this->mode, $this->sockType);
                $eventList = ['Start', 'ManagerStart', 'ManagerStop', 'PipeMessage', 'Task', 'Packet', 'Finish', 'Receive', 'Connect', 'Close', 'Timer', 'WorkerStart', 'WorkerStop', 'Shutdown', 'WorkerError'];
        }
        // 设置参数
        if (!empty($this->option)) {
            $this->swoole->set($this->option);
        }
        // 初始化
        $this->init();
        // 设置回调
        foreach ($eventList as $event) {
            if (method_exists($this, 'on' . $event)) {
                $this->swoole->on($event, [$this, 'on' . $event]);
            }
        }
    }

    protected function init()
    {
    }

    public function start()
    {
        $this->swoole->start();
    }

    public function stop()
    {
        $this->swoole->stop();
    }

    /**
     * 魔术方法 有不存在的操作的时候执行
     * @access public
     * @param string $method 方法名
     * @param array $args 参数
     * @return mixed
     */
    public function __call($method, $args)
    {
        call_user_func_array([$this->swoole, $method], $args);
    }
}