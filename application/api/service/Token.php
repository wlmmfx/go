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


use app\common\library\enum\ScopeEnum;
use app\common\library\exception\ForbiddenException;
use app\common\library\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

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

    // 通用
    public static function getCurrentTokenVar($key)
    {
        // 获取头部的token
        $token = Request::instance()->header('token');
        $vars = Cache::get($token);
        // 缓存是否失效或者缓存有问题
        if (!$vars) {
            throw  new TokenException();
        } else {
            if (!is_array($vars)) $vars = json_decode($vars, true);
            if (array_key_exists($key, $vars)) {
                return $vars[$key];
            } else {
                throw new Exception('尝试获取Token变量不存在');
            }
        }
    }

    /**
     * 获取当前用户的Uid
     * @return mixed
     * @static
     */
    public static function getCurrentUid()
    {
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

    /**
     * App用户和管理员都可以访问接口
     * @return bool
     * @static
     * @throws ForbiddenException
     * @throws TokenException
     */
    public static function needPrimaryScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope == ScopeEnum::USER) {
                return true;
            } else {
                throw  new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }

    /**
     * 只有App用户才可以访问接口权限:
     * 1、验证token是否合法或者是否过期
     * 2、验证器验证只是token验证的一种方式
     * 3、另外一种方式是使用行为拦截token，根本不让非法token，进入控制器
     * @return bool
     * @static
     * @throws ForbiddenException
     * @throws TokenException
     */
    public static function needExclusiveScope()
    {
        $scope = self::getCurrentTokenVar('scope');
        if ($scope) {
            if ($scope == ScopeEnum::USER) {
                return true;
            } else {
                throw  new ForbiddenException();
            }
        } else {
            throw new TokenException();
        }
    }

    // 操作是否合法
    public  static function isValidateOperate($checkUID)
    {
        if (!$checkUID) {
            throw new Exception("必须传入一个UID");
        }

        $currentOperateId = self::getCurrentUid();
        if ($currentOperateId == $checkUID) {
            return true;
        }
        return false;
    }
}