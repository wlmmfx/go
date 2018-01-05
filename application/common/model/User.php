<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/1/5 10:00
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\common\model;

use think\Model;

class User extends Model
{
    protected $table = 'resty_open_user';
    // 如果你希望定义不带前缀的数据表名，可以使用name属性来定义模型的名称。
    //protected $name = 'user';

    // 定义模型的读取器,获取格式化后的时间
    protected function getCreateTimeAttr($value)
    {
        return date('Y-m-d H:i:s', $value);
    }

    /**
     * 如果你的获取器方法需要根据其它字段的值来组合，可以给获取器方法添加第二个参数
     * @param $value
     * @param $data
     * @return string
     */
    protected function getUserTitleAttr($value,$data)
    {
        return $data['open_id'] . ':' . $data['account'];
    }

    /**
     * 获取APP信息
     * @param $value
     * @param $data
     * @return string
     */
    protected function getUserAppAttr($value,$data)
    {
        return $data['app_id'] . ':' . $data['app_secret'];
    }

}