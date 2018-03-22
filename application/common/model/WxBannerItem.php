<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/22 11:23
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\common\model;


class WxBannerItem extends BaseModel
{
    protected $table = "resty_wx_banner_item";

    protected $hidden = [
        'id',
        'img_id',
        'delete_time',
        'update_time',
        'banner_id'
    ];

    // 一对一关联 WxImage 模型
    public function img(){
        return $this->belongsTo('WxImage','img_id','id');
    }
}