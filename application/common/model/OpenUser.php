<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/9/10 16:43
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm
 * '-------------------------------------------------------------------*/

namespace app\common\model;

class OpenUser extends BaseModel
{
    protected $pk = "id";
    protected $table = "resty_open_user"; //完整的表名

    /**
     * 添加用户
     * @param $data
     * @return array
     */
    public function store($data)
    {
        $result = $this->save($data);
        if (false === $result) {
            // 验证失败 输出错误信息
            return ['valid' => 0, 'msg' => $this->getError()];
        }
        session('open_user.id', $this->pk);
        session('open_user.username', $result['account']);
        return ['valid' => 1, 'msg' => "注册成功"];
    }
}
