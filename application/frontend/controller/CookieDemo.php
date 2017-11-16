<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/11/16 19:40
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm
 * '-------------------------------------------------------------------*/

namespace app\frontend\controller;


class CookieDemo
{
    /**
     * 设置一个Cookie
     */
    public function setCookieDemo1(){
        echo "1".__FUNCTION__;
        setcookie('Username','tinywan');
        setcookie('Age','24');
    }

    /**
     * 设置一个过期的Cookie
     */
    public function setCookieExpireDemo1(){
        echo "1".__FUNCTION__;
        setcookie('Apple',7878,time()+30);
        setcookie('Apple123',7878-60,time()+60);
        setcookie('Apple888',7878-3600,time()+3600);

        // 一周内自动登录
        setcookie('auth',true,time()+7*24*60*60);
        setcookie('autoLogin',true,strtotime("+7 days"));
    }

    /**
     * 设置Cookie的路径
     */
    public function setCookiePathDemo1(){
        echo "1".__FUNCTION__;
        setcookie('auth-root',true,time()+3600);
        // 在项目根目录和子目录是可以访问的，根目录以上不不可以的
        setcookie('root123',true,time()+3600,'/');
        // 指定的目录有效
        setcookie('www-home',true,time()+3600,'/api/');
    }

    /**
     * 设置Cookie的作用域，默认为本域名下
     * Function：如果想在二级域名下也使用相同的Cookie
     * www.tinywan.com 和 live.tinywan.com
     */
    public function setCookieDomain(){
        echo "1".__FUNCTION__;
        setcookie('Http-Cookie001','Http',time()+3600,'/','',false);
        setcookie('Https-Cookie002','Https-Values',time()+3600,'/','',true);
    }

    /**
     *
     *  通过header函数设置Cookie
     */
    public function headerCookieDemo1(){
//        header('Set-Cookie: set-age=26');
//        header('Set-Cookie: set-age=26; path=/domain/frontend');
//        header('Set-Cookie: set-age-expire=26;expires='.gmdate('D, d M Y H:i:s \G\M\T',time()+3600));
//        header('Set-Cookie: set-age-expire=26;expires='.gmdate('D, d M Y H:i:s \G\M\T',time()+3600).'; domain=.tinywan.com');
        header('Set-Cookie: set-name-https=Tinywan;secure;expires='.gmdate('D, d M Y H:i:s \G\M\T',time()+3600).'; domain=.tinywan.com');
        header('Set-Cookie: set-name-https=Tinywan;secure;expires='.gmdate('D, d M Y H:i:s \G\M\T',time()+3600).'; domain=.tinywan.com;httponly');
        echo 'headerCookieDemo1';
    }

    /**
     * 数组方式设置Cookie
     */
    public function headerCookieDemo2(){
        setcookie('UserTable[Name]','Tinywan',time()+3600);
        setcookie('UserTable[Age]','24',time()+3600);
        setcookie('UserTable[Email]','756684177@qq.com',time()+3600);
        echo 'headerCookieDemo1';
    }

    /**
     *
     * 通过JS操作Cookie的实现
     */
    public function jsOptionCookie()
    {

    }
    /**
     * 自动
     */
    public function setCookieExpireDemo2(){
        echo "1".__FUNCTION__;
        setcookie('Apple',7878,time()+30);
        setcookie('Apple123',7878-60,time()+60);
        setcookie('Apple888',7878-3600,time()+3600);
    }

    /**
     * 读取Cookie
     * Cookie数据保存在$_COOKIE这个预定义变量中
     */
    public function getCookieDemo1(){
        print_r($_COOKIE);
    }
}