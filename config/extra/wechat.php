<?php
/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/23
 * Time: 22:36
 * WP_ 表示小程序
 * ****************************微信配置信息**********************************
 */
return [
    "APP_ID" => 'wx94c43716d8a91f3f',
    "APP_SECRET" => 'd4624c36b6795d1d99dcf0547af5443d',
    "WP_APP_ID" => 'wx1612e7b0e35e8679',
    "WP_APP_SECRET" => '06f0580a6742752246ccd54f6b65ff78',
    "WP_LOGIN_URL" => "https://api.weixin.qq.com/sns/jscode2session?" .
        "appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",
    "WP_NOTIFY_URL" => 'https://www.tinywan.com/api/v1/',
];