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
    public function _initialize(Request $request = null)
    {
        //执行登录验证
        #$_SESSION["admin"]["admin_id"];
        if(!session('admin.admin_id')){
            $this->redirect("backend/login/login");
        }
        $instance = Request::instance();
        $con = $instance->controller();
        $action = $instance->action();
        $name = $con.'/'.$action;
        $auth = new Auth();
        $check = $auth->check($name,session('admin.admin_id'));
        // 公共权限
        $noCheck = config('auth_config')['open_auth'];
//        if(!$check){
//            if(!in_array($name,$noCheck)){
//                $this->error('没有权限',"backend/entry/index");
//            }
//        }
    }
}
