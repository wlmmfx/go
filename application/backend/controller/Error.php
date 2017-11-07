<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  DateTime: 2017/11/7 10:00
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/

namespace app\backend\controller;


class Error
{
    /**
     * 拦截具体的某一个方法
     * @return string
     */
    public function index()
    {
        return 'index 方法不存在';
    }

    /**
     * 拦截所有方法，需要给空控制器定义一个空操作（_empty）方法
     * @param $method
     * @return string
     */
//    public function _empty($method)
//    {
//        return '当前操作名：' . $method.'不存在，已经跳转到Error空控制器操作了';
//    }
}