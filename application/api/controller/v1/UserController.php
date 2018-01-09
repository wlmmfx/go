<?php

/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/16 21:06
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\controller\v1;

use app\common\model\OpenUser as UserModel;

class UserController
{
    // 获取用户信息
    public function read($id=0){
        $user = UserModel::get($id);
        if ($user) {
            return json($user);
        } else {
            return json(['error' => '用户不存在'], 404);
        }
    }
}