<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  Date: 2017/1/20
 * |  Time: 16:25
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/

namespace app\backend\controller;

use redis\BaseRedis;
use think\Controller;
use think\Session;
use think\session\driver\Redis;
use think\View;

class LinuxShell extends Controller
{
    /**
     * Linux 系统管理
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 内存管理
     */
    public function internalStorage()
    {
        return $this->fetch();
    }

    /**
     * WEB内存管理
     */
    public function webInternalStorage()
    {
        //  监控的服务器IP
        $moniServerIp = "127.0.0.1";
        $moniServerSshUsername = "www";
        $moniServerSshPassword = "123456";
        return $this->fetch("webInternalStorage", [
            "moniServerIp" => $moniServerIp,
            "moniServerSshUsername" => $moniServerSshUsername,
            "moniServerSshPassword" => $moniServerSshPassword,
        ]);
    }

    /**
     * 通过用户名与密码连接函数
     */
    public function connectShellByUserPassword()
    {
        $ip = "127.0.0.1";
        $username = "tinywan";
        $password = "111";
        // 连接服务器
        $connection = ssh2_connect($ip, 22);
        if (ssh2_auth_password($connection, $username, $password)) {
            echo "Authentication Successful! ";
        } else {
            die("Authentication Failed...");
        }
        //执行远程服务器上的命令并取返回值
        $stream = ssh2_exec($connection, 'cat /proc/meminfo | grep "MemFree:"');
        stream_set_blocking($stream, true);
        echo stream_get_contents($stream);
    }

    /**
     * 通过用户名与密码连接函数
     */
    public function connectShellTest()
    {
        $ip = "127.0.0.1";
        $username = "www";
        $password = "123456";
        // 连接服务器
        $connection = ssh2_connect($ip, 22);
        if (!ssh2_auth_password($connection, $username, $password)) return 0;
        //执行远程服务器上的命令并取返回值
        $stream = ssh2_exec($connection, " cat /proc/meminfo | grep 'MemFree:' | awk '{print $2}'");
        stream_set_blocking($stream, true);
        return stream_get_contents($stream);
    }

    public function test()
    {
        echo $this->connectShellTest();
    }

}