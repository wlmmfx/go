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
use app\common\model\OpenUser;
use netease\YunXinIM;
use oauth\Qq;

class ChatRoomController extends BaseFrontendController
{
    const QQ_CALLBACK_URL = 'https://www.tinywan.com/im/chat_room/qqRedirectUri';

    private $_im;
    private $_appKey = '97437ca0ea2c1c333b90b9366f671743';
    private $_appSecret = 'e95a72e68607';

    public function _initialize()
    {
        parent::_initialize();
        $this->_im = new YunXinIM($this->_appKey, $this->_appSecret, 'curl');
    }

    public function testAction()
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
        $accId = "Tinywan_61";
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
        } else {
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
        $roomId = 22136841;
        $needOnlineUserCount = true;
        $info = $this->_im->getChatRoom($roomId, $needOnlineUserCount);
        $res = [
            'res' => 200,
            'msg' => [
                'total' => 1,
                'list' => $info
            ]
        ];
        return json($res);
    }

    /**
     * 获取所有聊天室
     * @return \think\response\Json
     */
    public function getAllChatRoom()
    {
        $roomInfo = ImRoom::where('valid', '=', 1)->limit(10)->select();
        // 遍历读取用户数据
        $list = [];
        foreach ($roomInfo as $room) {
            $list[] = [
                'creator' => $room->creator, //聊天室属主的账号accid
                'name' => $room->name, // 聊天室名称
                'status' => $room->valid,
                'announcement' => $room->announcement, // 公告
                'ext' => '',
                'roomid' => $room->room_id,
                'createtime' => $room->create_time,
                'broadcasturl' => $room->broadcasturl, // 直播地址
                'onlineusercount' => 12,
            ];
        }
        $res = [
            'res' => 200,
            'msg' => [
                'total' => 1,
                'list' => $list
            ]
        ];
        return json($res);
    }

    /**
     * 获取连接房间地址(请求聊天室地址)
     */
    public function requestRoomAddress()
    {
        if (!request()->isPost()) return "非Post 请求";
        $roomId = input('post.roomid');
        $accId = "Tinywan_61";
        $redis = messageRedis();
        $redis->set("IM-requestRoomAddress", json_encode($_POST));

        $info = $this->_im->requestRoomAddr($roomId, $accId);
        if ($info['code'] == 200) {
            $res = [
                'res' => 200,
                'errmsg' => '请求聊天室地址成功',
                'msg' => [
                    'addr' => $info['addr']
                ]
            ];
        } else {
            $res = [
                'res' => 500,
                'errmsg' => '请求聊天室地址失败',
                'msg' => [
                    'addr' => ''
                ]
            ];
        }

        return json($res);
    }

    /**
     * 主播页面
     */
    public function anchor()
    {
        return $this->fetch();
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
     * 注册
     */
    public function register()
    {
        return $this->fetch();
    }

    /**
     * 创建用户
     */
    public function createUser()
    {
        $account = input('post.username');
        $password = input('post.password');
        $data = [
            'username' => $account,
            'password' => $password,
        ];
        $redis = messageRedis();
        $redis->set("IM-createUser", json_encode($data));

        return json(['code' => 200, 'data' => $data]);
    }

    /**
     * 房间页面
     * @return mixed
     */
    public function room()
    {
        // 获取聊天室ID
        $roomid = input('get.roomid');
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

    /**
     * 登陆操作 ---------------------------------------------
     * @return mixed
     */
    public function qq()
    {
        $qq = new Qq(config('oauth.qq')['app_id'],config('oauth.qq')['app_key'],config('oauth.qq')['call_back_url']);
        return $qq->getAuthCode();
    }

    // QQ 回调
    public function qqRedirectUri()
    {
        $qqInstance = new Qq(config('oauth.qq')['app_id'],config('oauth.qq')['app_key'],config('oauth.qq')['call_back_url']);
        $qqInstance->setCallBackInfo();
        $openId = $qqInstance->getOpenId();
        $userInfo = $qqInstance->getUsrInfo();
        $userJsonRes = json_decode($userInfo, true);
        $condition['open_id'] = $openId;
        $checkUserInfo = OpenUser::where($condition)->find();
        if ($checkUserInfo) {
            session('open_user_id', $checkUserInfo['id']);
            session('open_user_username', $checkUserInfo['account']);
            return $this->redirect("/im/chat_room/roomList");
        } else {
            $user = OpenUser::create([
                'account' => $userJsonRes['nickname'],
                'open_id' => $openId,
                'password' => md5('123456'),
                'realname' => $userJsonRes['nickname'],
                'nickname' => $userJsonRes['nickname'],
                'avatar' => $userJsonRes['figureurl_2'],
                'company' => $userJsonRes['province'],
                'type' => 'QQ',
                'app_id' => get_rand_string(),
                'app_secret' => get_rand_string(40),
            ]);
            if ($user) {
                session('open_user_id', $user->id);
                session('open_user_username', $userJsonRes['nickname']);
                return $this->redirect("/im/chat_room/roomList");
            } else {
                return $this->redirect("/im/chat_room/roomList");
            }
        }
    }

    /**
     * 打印session
     * @return mixed
     */
    public function printSession()
    {
        halt(session('OAUTH_HTTP_REFERER'));
    }
}