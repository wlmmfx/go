<?php
/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/27
 * Time: 22:01
 */
namespace app\frontend\controller;
use think\Controller;

class Web extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function workerMan()
    {
        $this->view->engine->layout(false);
        return $this->fetch();
    }

    /**
     * 内存管理
     */
    public function internalManage()
    {        //  监控的服务器IP
        $moniServerIp = "127.0.0.1";
        $moniServerSshUsername = "www";
        $moniServerSshPassword = "www123456";
        $this->view->engine->layout(false);
        return $this->fetch("internalManage",[
            "moniServerIp"=>$moniServerIp,
            "moniServerSshUsername"=>$moniServerSshUsername,
            "moniServerSshPassword"=>$moniServerSshPassword,
        ]);
    }
}