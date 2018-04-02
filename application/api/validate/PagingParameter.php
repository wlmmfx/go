<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/4/2 15:20
 * |  Mail: Overcome.wan@Gmail.com
 * |  Desc: 描述信息
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\validate;


class PagingParameter extends BaseValidate
{
    protected $rule = [
        'page'=>'isPositiveInteger',
        'size'=>'isPositiveInteger'
    ];

    protected $message= [
        'page'=>'分页参数必须是正整数',
        'size'=>'分页参数必须是正整数'
    ];
}