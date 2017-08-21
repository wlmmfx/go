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

    public function login()
    {
        $callback = function($a,$b) {
            return $a + $b;
        };
        echo $callback(120,120);
    }

    public function loginTicket($tax)
    {
        $total = 0.0;
        $callback = function($quantity,$product) use ($tax , &$total){
            //constant 返回常量的值
            //__class__返回类名
            $price = constant(__CLASS__."::PRICE_".strtoupper($product));
            $total += ($price * $quantity)*($tax+1.0);
        };
        //array_walk() 函数对数组中的每个元素应用用户自定义函数。在函数中，数组的键名和键值是参数
        array_walk($this->products,$callback);
        //回调匿名函数
        return round($total,2);
    }
}