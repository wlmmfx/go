<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/8/26 14:42
 * |  Mail: Overcome.wan@Gmail.com
 * '-------------------------------------------------------------------*/

namespace app\frontend\controller;

use app\common\controller\BaseFrontend;
use redis\MsgRedis;
use think\Log;

class WebsocketClient extends BaseFrontend
{
    const SERVER_USER_NAME = "www";
    const SERVER_AUTH = "12312";
    const SERVER_IP = "www.tinywan.com";
    const WS_SERVER_PORT = "8081";
    const SHELL_SCRIPT_PATH = "/home/www/web/go-study-line/shell/auto-install/";
    /**
     * 获取基本信息
     */
    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
    }

    /**
     *  http://test.thinkphp5-line.com/frontend/websocket_client/index
     * @return string
     */
    public function test()
    {
        halt(config());
    }

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
        $this->assign('wsServerIP',self::SERVER_IP);
        $this->assign('wsServerPort',self::WS_SERVER_PORT);
        return $this->fetch();
    }

    public function chatRoomRedis()
    {
        $liveId = "L000112";
        $redis = MsgRedis::increaseTotalViewNum($liveId);
        var_dump($redis);
    }

    public function runShell()
    {
        $servers = '192.168.1.1 192.168.1.2 192.168.1.3 192.168.1.4';
        $pwds = 'www123 www456 www678';
        $shell_script = self::SHELL_SCRIPT_PATH . "init.sh";
        $cmdStr = "{$shell_script} {$servers} {$pwds}";
        Log::error('[' . self::formatDate(time()) . ']:' . "Shell 脚本参数 ： " . $cmdStr);
        exec("{$cmdStr} >> /home/www/web/go-study-line/shell/auto-install/shell.log 2>&1 ", $results, $status );
        if($status !== 0){
            return "脚本执行错误";
        }
        return "正在执行中....";
    }
}