<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/9/4 22:31
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm
 * '-------------------------------------------------------------------*/
namespace aliyun\oss;

class Test
{
    public static function oss(){
         $send_email_expire_time = config('aliyun_oss.accessKeyId');
         echo $send_email_expire_time;
    }
}