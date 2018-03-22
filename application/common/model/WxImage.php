<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/22 13:13
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\common\model;


class WxImage extends BaseModel
{
    protected $table = 'resty_wx_image';
    protected $hidden = [
        'id',
        'delete_time',
        'update_time',
        'from'
    ];

    public function getUrlAttr($value,$data){
        return $this->prefixImgUrl($value,$data);
    }
}