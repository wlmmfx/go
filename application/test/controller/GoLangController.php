<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/23 9:10
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\test\controller;

use Spiral\Goridge;

class GoLangController
{
    public function test(){
        $rpc1 = new Goridge\RPC(new Goridge\SocketRelay("127.0.0.1", 6001));
        echo $rpc1->call("App.Hi", "Hello RPC");
    }
}