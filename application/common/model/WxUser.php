<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/25 17:07
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\common\model;


class WxUser extends BaseModel
{
    protected $resultSetType = 'collection';

    public function address()
    {
        return $this->hasOne('WxUserAddress', 'user_id', 'id');
    }

    public static function getByOpenId($openId)
    {
        return self::where('openid', '=', $openId)->find();
    }
}

