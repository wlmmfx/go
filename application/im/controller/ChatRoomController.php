<?php

/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/7 13:53
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\im\controller;

use app\common\controller\BaseFrontendController;
use app\common\model\ImRoom;
use netease\YunXinIM;

class ChatRoomController extends BaseFrontendController
{
    private $_im;
    private $_appKey = '97437ca0ea2c1c333b90b9366f671743';
    private $_appSecret = 'e95a72e68607';

    public function _initialize()
    {
        parent::_initialize();
        $this->_im = new YunXinIM($this->_appKey, $this->_appSecret, 'curl');
    }

    public function test()
    {
        return "123";
    }

    /**
     * 房间列表
     * @return mixed
     */
    public function roomList()
    {
        return $this->fetch();
    }

    /**
     * 创建聊天室
     * https://api.netease.im/nimserver/chatroom/create.action
     * array (size=2)
     * 'chatroom' =>
     * array (size=9)
     * 'roomid' => int 22196252
     * 'valid' => boolean true
     * 'announcement' => null
     * 'queuelevel' => int 0
     * 'muted' => boolean false
     * 'name' => string '天龙八部直播聊天室' (length=27)
     * 'broadcasturl' => null
     * 'ext' => string '' (length=0)
     * 'creator' => string 'tinywan_60' (length=10)
     * 'code' => int 200
     */
    public function createChatRoom()
    {
        $accId = "Tinywan_60";
        $name = "天龙八部3 直播聊天室";
        $info = $this->_im->createChatRoom($accId, $name);
        if ($info['code'] == 200) {
            //insert
            $res = ImRoom::create([
                'room_id' => $info['chatroom']['roomid'],
                'valid' => $info['chatroom']['valid'],
                'announcement' => $info['chatroom']['announcement'],
                'queuelevel' => $info['chatroom']['queuelevel'],
                'name' => $info['chatroom']['name'],
                'broadcasturl' => $info['chatroom']['broadcasturl'],
                'ext' => $info['chatroom']['ext'],
                'creator' => $info['chatroom']['creator'],
                'create_time' => time()
            ]);
            if ($res) {
                echo "创建聊天室成功";
            }
            // 4、注册通信ID
        }else{
            echo "接口失败";
        }
        return 1;
    }

    /**
     * 查询聊天室信息
     * https://api.netease.im/nimserver/chatroom/get.action
     */
    public function getChatRoom()
    {
        $roomId = 22272267;
        $needOnlineUserCount = true;
        $info = $this->_im->getChatRoom($roomId, $needOnlineUserCount);
        halt($info);

    }

    /**
     * Get 房间列表
     */
    public function homeList()
    {
        $list = [
            'creator' => 'Tinywan_002', //聊天室属主的账号accid
            'name' => '万少波', // 聊天室名称
            'status' => 1,
            'announcement' => '魔兽世界', // 公告
            'ext' => '',
            'roomid' => '3012',
            'createtime' => time(),
            'broadcasturl' => '', // 直播地址
            'onlineusercount' => 12,
        ];
        $res = [
            'res' => 200,
            'msg' => [
                'total' => 8,
                'list' => $list
            ]
        ];
        return json($res);
    }

    /**
     * 登陆
     * @return mixed
     */
    public function login()
    {
        return $this->fetch();
    }

    /**
     * 房间页面
     * @return mixed
     */
    public function room()
    {
        return $this->fetch();
    }

    /**
     * 房间管理
     * @return mixed
     */
    public function roomManage()
    {
        return $this->fetch();
    }
}