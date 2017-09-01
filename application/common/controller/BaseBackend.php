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

use app\common\library\Auth;
use think\Controller;

class BaseBackend extends Controller
{
    /**
     * 权限实例
     * @var
     */
    protected $auth;

    public function _initialize()
    {
        //执行登录验证
        #$_SESSION["admin"]["admin_id"];
        if (!session('admin.admin_id')) {
            $this->redirect("backend/login/login");
        }
        $controllerName = strtolower($this->request->controller());
        $actionName = strtolower($this->request->action());
        $checkAuth = $controllerName . '/' . $actionName;
        $this->auth = new Auth();
        $checkResult = $this->auth->check($checkAuth, session('admin.admin_id'));
        // public auth
        $openAuth = config('auth_config')['open_auth'];
        if (in_array($checkAuth, $openAuth)) return true;
        if (!$checkResult) {
            if (!in_array($checkAuth, $openAuth)) {
                $this->error('没有权限');
            }
        }
    }
}