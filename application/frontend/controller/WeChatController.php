<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  DateTime: 2017/11/21 15:22
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/

namespace app\frontend\controller;


use app\common\controller\BaseFrontendController;
use EasyWeChat\Foundation\Application;
use think\Log;

class WeChatController extends BaseFrontendController
{
    public function index()
    {
        echo ROOT_PATH . 'public' . DS;
        halt(config('easywechat'));
        return '111';
    }

    /**
     * 来设置消息处理函数
     */
    public function setMessageHandler()
    {
        $server = self::easyWeChatApp()->server;
        $server->setMessageHandler(function ($message) {
            return "您好！欢迎关注我!";
        });
        $response = $server->serve();
        $response->send();
        return $response;
    }

    /**
     * profile
     */
    public function profile()
    {
        $oauth = self::easyWeChatApp()->oauth;
        // 未登录
        if (empty($_SESSION['wechat_user'])) {
            $_SESSION['target_url'] = 'https://www.tinywan.com/frontend/we_chat/profile';
            return $oauth->redirect();
        }
        // 已经登录过
        $user = $_SESSION['wechat_user'];
        halt($user);
    }

    /**
     * 回调地址
     */
    public function oauth_callback()
    {
        $oauth = self::easyWeChatApp()->oauth;
        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();
        $_SESSION['wechat_user'] = $user->toArray();
        $targetUrl = empty($_SESSION['target_url']) ? '/' : $_SESSION['target_url'];
        header('location:' . $targetUrl); // 跳转到 user/profile
    }

    /**
     * 获取用户信息
     */
    public function getUserInfo()
    {
        $user = self::easyWeChatApp()->oauth->user();
        halt($user);
    }
}