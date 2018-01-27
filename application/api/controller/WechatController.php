<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/1/23 18:03
 * |  Mail: Overcome.wan@Gmail.com
 * |  Function: 微信小程序接口
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\controller;


use app\common\controller\BaseController;

class WechatController extends BaseController
{
    public function index(){
        return json(['code'=>200,'msg'=>'success']);
    }
}