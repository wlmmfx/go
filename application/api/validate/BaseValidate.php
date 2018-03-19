<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/19 17:17
 * |  Mail: Overcome.wan@Gmail.com
 * |  Function: 基类验证器
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\validate;


use think\Validate;

class BaseValidate extends Validate
{
    /**
     * 正整数验证器
     * @param $value 验证数据
     * @param string $rule  验证规则
     * @param string $data  全部数据（数组）
     * @param string $field 字段名
     * @return bool|string
     */
    protected function isPositiveInteger($value, $rule='', $data='', $field='')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        return $field . '必须是正整数';
    }
}