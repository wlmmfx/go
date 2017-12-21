<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/21 14:01
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\business\controller;


use app\common\controller\BaseFrontend;
use think\Db;

class User extends BaseFrontend
{
    // 我的主页
    public function home()
    {
        $userId = session('open_user_id');
        $userInfo = Db::table('resty_open_user')->where('id', $userId)->find();
        return $this->fetch('', [
            'user' => $userInfo
        ]);
    }

    // 用户中心
    public function index()
    {
        $userId = session('open_user_id');
        $userInfo = Db::table('resty_open_user')->where('id', $userId)->find();
        return $this->fetch('', [
            'user' => $userInfo
        ]);
    }

    // 消息
    public function message()
    {
        return $this->fetch();
    }

    public function login()
    {
        return $this->fetch();
    }

    public function reg()
    {
        if (request()->isPost()) {
            halt($_POST);
        }
        return $this->fetch();
    }

    /**
     * 退出
     */
    public function logout(){
        session(null);
        return $this->redirect("/");
    }

    /**
     * 登陆
     */
    public function setting()
    {
        return $this->fetch();
    }
}