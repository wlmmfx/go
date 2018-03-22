<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/22 22:47
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\common\model;


class WxCategory extends BaseModel
{
    protected $hidden = [
        'delete_time',
        'update_time'
    ];
    public function img(){
        return $this->belongsTo('WxImage','topic_img_id','id');
    }
}