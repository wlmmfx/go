<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/11 21:29
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\socket\controller;


use GatewayClient\Gateway;
use think\View;

class Run
{
    /**
     * 构造函数
     * @access public
     */
    public function __construct()
    {
        //初始化各个GatewayWorker
        //初始化register
        new Register('text://0.0.0.0:1238');

        //初始化 bussinessWorker 进程
        $worker = new Buss();
        $worker->name = 'YourAppBusinessWorker';
        $worker->count = 4;
        $worker->registerAddress = '127.0.0.1:1238';
        //设置处理业务的类,此处制定Events的命名空间
        $worker->eventHandler = '\app\push\controller\Events';
        // 初始化 gateway 进程
        $gateway = new Gateway("websocket://0.0.0.0:8282");
        $gateway->name = 'YourAppGateway';
        $gateway->count = 4;
        $gateway->lanIp = '127.0.0.1';
        $gateway->startPort = 2900;
        $gateway->registerAddress = '127.0.0.1:1238';

        //运行所有Worker;
        \Workerman\Worker::runAll();
    }

    public function hello() {
        $uid = $_GET['uid'];
        session('uid', $uid);

        $view = new View();
        return $view->fetch();
    }

    public function BindClientId() {

        $client_id = $_POST['client_id'];
        // 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值
        Gateway::$registerAddress = '127.0.0.1:1238';

        $bindUid = session('uid');
        // 假设用户已经登录，用户uid和群组id在session中
        // client_id与uid绑定
        Gateway::bindUid($client_id, $bindUid);
        // 加入某个群组（可调用多次加入多个群组）
        // Gateway::joinGroup($client_id, $group_id);
    }

    public function AjaxSendMessage() {
        $message = $_POST['message'];
        // 设置GatewayWorker服务的Register服务ip和端口，请根据实际情况改成实际值
        Gateway::$registerAddress = '127.0.0.1:1238';

        GateWay::sendToAll($message);
    }
}