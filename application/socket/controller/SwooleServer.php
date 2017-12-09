<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  DateTime: 2017/9/18 15:46
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/

namespace app\socket\controller;


use Swoole\Server;
use think\Cache;
use think\Config;
use think\Db;
use think\Exception;
use think\Log;

class SwooleServer extends Server
{
    protected $redis;
    protected $userList = "UserOnlineList";
    protected $roomName = "defaultRoom";
    protected $roomListName = "RoomList";
    protected $error;
    protected $cache = true;
    protected $table = true;

    /**
     * 初始化的回调方法
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param \swoole_websocket_server $server
     */
    protected function initialize(\swoole_websocket_server $server)
    {
        $redis_config = Config::get("redis");
        $this->redis = new \Redis($redis_config);
        $roomList = $this->redis->smembers($this->roomListName);
        //主程序启动 清空所有聊天室在线用户
        if (!empty($roomList) && is_array($roomList)) {
            foreach ($roomList as $room) {
                $this->redis->delete("{$this->userList}_{$room}");
            }
        }
        //创建内存表
        $this->createTable();
    }

    /**
     * 创建链接时候的回调方法
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param \swoole_websocket_server $server
     * @param \swoole_http_request $request
     */
    protected function onOpen(\swoole_websocket_server $server, \swoole_http_request $request)
    {
        echo "server: success with fd{$request->fd}\n";
    }


    /**
     * 接收信息回调方法
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param \swoole_websocket_server $server
     * @param \swoole_websocket_frame $frame
     */
    protected function onMessage(\swoole_websocket_server $server, \swoole_websocket_frame $frame)
    {
        try {
            if (!empty($frame) && $frame->opcode == 1 && $frame->finish == 1) {
                $message = $this->checkMessage($frame->data);
                if (!$message) {
                    $this->serverPush($server, $frame->fd, $frame->data, 'message');
                }
                if (isset($message["type"])) {
                    switch ($message["type"]) {
                        case "login":
                            $this->login($server, $frame->fd, $message["message"], $message["room"]);
                            break;
                        case "message":
                            $this->serverPush($server, $frame->fd, $message["message"], 'message', $message["room"]);
                            break;
                        default:
                    }
                    $this->redis->sadd($this->roomListName, $message["room"]);
                }
            } else {
                throw new Exception("接收数据不完整");
            }
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }
        echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    }

    protected function onRequest(\swoole_websocket_server $server, \swoole_http_request $request, \swoole_http_response $response)
    {
        // 接收http请求从get获取message参数的值，给用户推送
        // 通过这个接口 可以对做自定义的管理推送
    }

    /**
     * 退出的回调方法
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param \swoole_websocket_server $server
     * @param $fd
     */
    protected function onClose(\swoole_websocket_server $server, $fd)
    {
        $this->logout($server, $fd);
        $server->close($fd);
        echo "client {$fd} closed\n";
    }


    /**
     * 推送同聊天室信息
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param \swoole_websocket_server $server
     * @param $frame_fd
     * @param string $message
     * @param string $message_type
     */
    protected function serverPush(\swoole_websocket_server $server, $frame_fd, $message = "", $message_type = "message")
    {
        $push_list = $this->getPushListByFd($frame_fd);
        $message = htmlspecialchars($message);
        $datetime = date('Y-m-d H:i:s', time());
        $user = $this->table->get($frame_fd);
        if (isset($user)) {
            unset($user["openid"]);
            foreach ($push_list as $fd) {
                if ($fd == $frame_fd) {
                    continue;
                }
                @$server->push($fd, json_encode([
                        'type' => $message_type,
                        'message' => $message,
                        'datetime' => $datetime,
                        'user' => $user,
                    ])
                );
            }
        }
    }

    /**
     * 获取同聊天室用户信息
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param $fd
     * @return array
     */
    protected function getPushListByFd($fd)
    {
        $room = $this->table->get($fd, "room");
        if (empty($room)) {
            return [];
        }
        return $this->redis->hget("{$this->userList}_{$room}");
    }

    /**
     * 检测信息并转换信息
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param $message
     * @return array|bool|mixed
     */
    protected function checkMessage($message)
    {
        $message = json_decode($message);
        $return_message = [];
        if (!is_array($message) && !is_object($message)) {
            $this->error = "接收的message数据格式不正确";
            return false;
        }
        if (is_object($message)) {
            foreach ($message as $item => $value) {
                $return_message[$item] = $value;
            }
        } else {
            $return_message = $message;
        }
        if (!isset($return_message["type"]) || !isset($return_message["message"])) {
            return false;
        } else {
            if (!isset($return_message["room"])) $return_message["room"] = $this->roomName;
            return $return_message;
        }
    }

    /**
     * 处理用户登录信息
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param \swoole_websocket_server $server
     * @param $frame_fd
     * @param string $message
     * @param $room
     * @return bool
     */
    protected function login(\swoole_websocket_server $server, $frame_fd, $message = "", $room)
    {
        $open_id = Rsa::instance()->decrypt($message);
        if (empty($open_id)) {
            return false;
        }
        $user_info = $this->getUserInfoByOpenId($open_id);
        $this->updateFrameFd($frame_fd, $user_info);
        $user_info["fd"] = $frame_fd;
        $user_info["room"] = $room;
        $this->updateFrameFd($frame_fd, $user_info);
        $this->createRoomUserList($server, $room, $frame_fd, $open_id);
        unset($user_info["openid"]);
        $server->push($frame_fd, json_encode(
                ['user' => $user_info, 'all' => $this->allUserByRoom($room), 'type' => 'openSuccess'])
        );
        $this->serverPush($server, $frame_fd, "欢迎{$user_info['name']}进入聊天室", 'open');

    }

    /**
     * 用户退出处理
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param \swoole_websocket_server $server
     * @param $fd
     */
    protected function logout(\swoole_websocket_server $server, $fd)
    {
        $user = $this->table->get($fd);
        if (isset($user)) {
            $this->serverPush($server, $fd, $user['name'] . "离开聊天室", 'close');
            $this->deleteRoomUserList($user["room"], $user["openid"]);
            $this->table->del($fd);
        }
    }

    /**
     * 添加至聊天室列表
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param $server
     * @param $room
     * @param $frame_fd
     * @param $openid
     */
    protected function createRoomUserList(\swoole_websocket_server $server, $room, $frame_fd, $openid)
    {
        try {
            $fd = $this->redis->hget("{$this->userList}_{$room}", $openid);
            if (isset($fd)) {
                $user = $this->table->get($fd);
                if (isset($user)) {
                    $this->serverPush($server, $fd, $user['name'] . "离开聊天室", 'close');
                    $this->table->del($fd);
                }
                $server->close($fd);

            }
            $this->redis->hset("{$this->userList}_{$room}", $openid, $frame_fd);
        } catch (Exception $e) {
            Log::error($e->getMessage());
        }

    }

    /**
     * 从在线用户列表中删除用户
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param $room
     * @param $openid
     */
    protected function deleteRoomUserList($room, $openid)
    {
        $this->redis->hdel("{$this->userList}_{$room}", $openid);
    }

    /**
     * 获取所有聊天室在线信息
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param $room
     * @return array
     */
    protected function allUserByRoom($room)
    {
        $user_list = $this->redis->hget("{$this->userList}_{$room}");
        $users = [];
        if (!empty($user_list)) {
            foreach ($user_list as $fd) {
                $user = $this->table->get($fd);
                if (!empty($user)) {
                    $users[] = $user;
                }
            }
        }
        return $users;

    }

    /**
     * 存在在线链接信息
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param $frame_fd
     * @param $user_info
     */
    protected function updateFrameFd($frame_fd, $user_info)
    {
        $this->table->set($frame_fd, $user_info);
    }


    /**
     * 获取微信用户
     * Power: Mikkle
     * Email：776329498@qq.com
     * @param $open_id
     * @return array|false|mixed|\PDOStatement|string|\think\Model
     */
    protected function getUserInfoByOpenId($open_id)
    {
        $user_info = Cache::get("WeFans:{$open_id}");
        if (empty($user_info) || $this->cache == false) {
            $user_info = Db::table("mk_we_fans")->where(["openid" => $open_id, "status" => 1])->field("openid,nickname_json,nickname,headimgurl as avatar")->find();
            if (!$user_info) return [];
            if (isset($user_info["nickname_json"]) && !empty(json_decode($user_info["nickname_json"]))) {
                $user_info["name"] = json_decode($user_info["nickname_json"]);
            } else {
                $user_info["name"] = $user_info["nickname"];
            }
            unset($user_info["nickname_json"]);
            unset($user_info["nickname"]);
            Cache::set("WeFans:{$open_id}", $user_info);
        }
        return $user_info;
    }


    /**
     * 创建内存表 存在在线用户
     * Power: Mikkle
     * Email：776329498@qq.com
     */
    protected function createTable()
    {
        $this->table = new \swoole_table(1024);
        $this->table->column('fd', \swoole_table::TYPE_INT);
        $this->table->column('openid', \swoole_table::TYPE_STRING, 100);
        $this->table->column('name', \swoole_table::TYPE_STRING, 255);
        $this->table->column('avatar', \swoole_table::TYPE_STRING, 255);
        $this->table->column('room', \swoole_table::TYPE_STRING, 100);
        $this->table->create();
    }
}