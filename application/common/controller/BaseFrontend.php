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

use think\Controller;
use think\Db;

class BaseFrontend extends Controller
{
    /**
     * 初始化用户信息
     */
    public function _initialize()
    {
        $userInfo = Db::table('resty_open_user')->where('id', session('open_user_id'))->find();
        $this->assign('userInfo', $userInfo);
    }
}