<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/25 18:18
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\service;


class Token
{
    /**
     * 获取令牌key
     */
    public static function generateToken()
    {
        // 32 字符组成的无意义的字符串
        $randChar = get_rand_string(32);
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        // salt
        $tokenSalt = config('secure.token_salt');
        return md5($randChar . $timestamp . $tokenSalt);
    }
}