<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/21 17:15
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\common\model;


class WxBanner extends BaseModel
{
    protected $table = "resty_wx_banner";

    protected $hidden = [
        'delete_time',
        'update_time'
    ];

    // 查询具体的 banner 信息，使用关联模型
    public function items()
    {
        return $this->hasMany('WxBannerItem', 'banner_id', 'id');
    }

    // 根据bannerId 获取banner 信息
    public static function getBannerById($id)
    {
        $banner = self::with(['items','items.img'])->find($id);
        return $banner;
    }
}