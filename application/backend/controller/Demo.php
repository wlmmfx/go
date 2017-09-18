<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  DateTime: 2017/9/18 17:23
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/

namespace app\backend\controller;


use app\common\controller\BaseBackend;
use app\common\library\Auth;

class Demo extends BaseBackend
{
    public function index()
    {
        return '当前用户角色：' . $this->role;
    }

    public function getRole()
    {
        $auth = new Auth();
        halt($auth->getGroups('178'));
    }
}