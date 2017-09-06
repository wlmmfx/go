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

namespace app\common\model;

use think\Model;

class OpenUser extends Model
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
