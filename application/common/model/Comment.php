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

class Comment extends Model
{
    protected $pk = "comment_id";
    protected $table = "resty_comment";

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    //如果数据库没有创建create_time和更新时间create_time,则按照以下的做一个映射既可以
    protected $createTime  = "create_time";

    //插入的时候自动完成
    protected $insert = [
        "create_time"
    ];

    /**
     * 添加评论
     * @param $data
     * @return array
     */
    public function store($data)
    {
        $result = $this->validate(true)->save($data);
        if (false === $result) {
            // 验证失败 输出错误信息
            return ['valid' => 0, 'msg' => $this->getError()];
        }
        return ['valid' => 1, 'msg' => "添加成功"];
    }

}
