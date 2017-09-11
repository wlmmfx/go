<?php

/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/8/28 14:42
 * |  Mail: Overcome.wan@Gmail.com
 * '-------------------------------------------------------------------*/

namespace app\common\controller;

use think\Cache;
use think\Cookie;
use think\Db;
use think\Session;

class BaseFrontend extends Base
{
    protected $uuid;
    protected $member_info;
    /**
     * 检测是否登录
     * Power by Mikkle
     * QQ:776329498
     * @return bool
     */
    public function checkLoginGlobal()
    {
        $check_success = false;
        switch ($this->loginType) {
            case 1;
            case "session";
                $this->uuid = Session::get('uuid', 'Global');
                $this->member_info = Session::get('member_info', 'Global');
                if ($this->uuid && $this->member_info) {
                    $check_success = true;
                }
                break;
            case 2;
            case "cache";
                $session_id_check = Cookie::get("session_id");
                $this->uuid = Cache::get("uuid_{$session_id_check}");
                $this->member_info = Cache::get("member_info_{$session_id_check}");
                if ($this->uuid && $this->member_info) {
                    $check_success = true;
                }
                //刷新 缓存有效期
                Cache::set("uuid_{$session_id_check}", $this->uuid);
                Cache::set("member_info_{$session_id_check}", $this->member_info);
                break;
            case 3:
            case "redis":
                break;
            default:
                0;
        }
        return $check_success;
    }

    /**
     * 设置全局登录
     * #User: Mikkle
     * #Email:776329498@qq.com
     * #Date:
     */
    public function setLoginGlobal($member_info = [], $login_code = 0)
    {
        $set_success = false ;
        if ($member_info) {
            switch ($this->loginType) {
                case 1:
                case "session":
                    Session::set('member_info', $member_info, 'Global');
                    Session::set('uuid', $member_info['uuid'], 'Global');
                    if ((Session::has("uuid", "Global"))) {
                        $set_success = true;
                    }
                    break;
                case 2:
                case "cache":
                    $session_id = $this->create_uuid("SN");
                    Cookie::set("session_id", $session_id);
                    Cache::set("member_info_$session_id", $member_info);
                    Cache::set("uuid_$session_id", $member_info['uuid']);
                    $session_id_check = Cookie::get("session_id");
                    if ((Cache::get("uuid_{$session_id_check}"))) {
                        $set_success = true;
                    }
                    break;
                case 3:case "redis":
                break;
            }
        }
        if (!$set_success) return false;
        //保存登录记录
        $this->saveLoginInfo($member_info['uuid'],$login_code);
        return true;
    }

    /**
     * 全局退出
     * @return bool
     */
    protected function logoutGlobal(){
        switch ($this->loginType) {
            case 1:
            case "session":
                Session::delete('uuid', 'Global');
                Session::delete('member_info', 'Global');
                break;
            case 2:
            case "cache":
                $session_id_check = Cookie::get("session_id");
                Cache::rm("uuid_{$session_id_check}");
                Cache::rm("member_info_{$session_id_check}");
                Cookie::delete("session_id");
                break;
            case 3:
            case "redis":
                break;
        }
        $this->member_info = null;
        $this->uuid = null;
        return true;
    }

    /**
     * 初始化用户信息
     */
    public function _initialize()
    {
        $userInfo = Db::table('resty_open_user')->where('id', session('open_user_id'))->find();
        $this->assign('userInfo', $userInfo);
    }

}