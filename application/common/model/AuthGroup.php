<?php

namespace app\common\model;

use think\Model;

class AuthGroup extends Model
{
    protected $pk = "id";
    protected $table = "resty_auth_group"; //完整的表名

    public function store($data)
    {
        $result = $this->validate(false)->save($data);
        if (false === $result) {
            // 验证失败 输出错误信息
            return ['valid' => 0, 'msg' => $this->getError()];
        }
        return ['valid' => 1, 'msg' => "添加成功"];
    }
}
