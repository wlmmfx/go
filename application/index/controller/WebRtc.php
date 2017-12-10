<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/10 13:14
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\index\controller;


use app\common\controller\BaseFrontend;

class WebRtc extends BaseFrontend
{
    public function index()
    {
        return $this->fetch();
    }
}