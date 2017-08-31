<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  Date: 2017/1/20
 * |  Time: 16:25
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/
namespace app\backend\controller;

use think\Controller;
use think\Request;

class Common extends Controller
{
    //
    public function _initialize()
    {
        //执行登录验证
        #$_SESSION["admin"]["admin_id"];
        if(!session('admin.admin_id')){
            $this->redirect("backend/login/login");
        }
        $controllerName = strtolower($this->request->controller());
        $actionName = strtolower($this->request->action());
        $checkAuth = $controllerName . '/' . $actionName;
        $authInstance = new Auth();
        $check = $authInstance->check($checkAuth,session('admin.admin_id'));
        // public auth
        $openAuth = config('auth_config')['open_auth'];
        if(in_array($checkAuth,$openAuth)) return true;
        if(!$check){
            if(!in_array($name,$noCheck)){
                $this->error('没有权限');
            }
        }
    }
}
