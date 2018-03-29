<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/27 22:34
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\common\model;


class WxOrder extends BaseModel
{
    protected $table = 'resty_wx_order';

    protected $hidden = [
        'delete_time',
        'create_time',
        'update_time'
    ];

    protected $autoWriteTimestamp = true;

    protected $insert = [
        "create_time"
    ];

    //更新自动完成
    protected $update = [
        "update_time"
    ];
}