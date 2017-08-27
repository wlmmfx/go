<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/8/26 14:42
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm
 * '-------------------------------------------------------------------*/

namespace app\frontend\controller;

use redis\MsgRedis;
use think\Controller;

class WebsocketClient extends Controller
{
    /**
     *  http://test.thinkphp5-line.com/frontend/websocket_client/index
     * @return string
     */
    public function index()
    {
        $this->view->engine->layout(false);
        return $this->fetch('index');
    }

    /**
     * http://test.thinkphp5-line.com/frontend/websocket_client/chatroom
     * 在线聊天室
     * @return mixed
     */
    public function chatRoom()
    {
        return $this->fetch();
    }

    public function chatRoomRedis()
    {
        $liveId = "L000112";
        $redis = MsgRedis::increaseTotalViewNum($liveId);
        var_dump($redis);
    }
}