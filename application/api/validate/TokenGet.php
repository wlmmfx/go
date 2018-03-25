<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/25 17:01
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\validate;


class TokenGet extends BaseValidate
{
    protected $rule = [
        'code'=>'require|isNotEmpty'
    ];

    protected $message = [
        'code'=>'code 是必须的，不可以为空'
    ];
}