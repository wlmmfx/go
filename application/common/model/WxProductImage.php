<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/26 9:25
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\common\model;


class WxProductImage extends BaseModel
{
    protected $table = 'resty_wx_product_image';

    protected $hidden = [
        'delete_time',
        'product_id',
        'img_id'
    ];

    /**
     * 一对一关系
     * @return \think\model\relation\BelongsTo
     */
    public function imgUrl()
    {
        return $this->belongsTo('WxImage', 'img_id', 'id');
    }
}