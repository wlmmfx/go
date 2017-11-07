<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  DateTime: 2017/11/7 13:46
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/

namespace app\common\validate;


use think\Validate;

class Live extends Validate
{
    // 定义表单验证规则
    protected $rule = [
        'name'  =>  'require|min:4',
        'cate_id' =>  'require',
        'liveStartTime' =>  'require',
        'liveEndTime' =>  'require'
    ];

    // 自定义验证信息
    protected $message  =   [
        'name.require' => '活动名称不能为空',
        'name.min' => '活动名称不能小于4个字符',
        'liveStartTime.require' => '开始时间不能为空',
        'liveEndTime.require' => '结束时间不能为空'
    ];

    // 验证场景
    protected $scene = [

    ];
}