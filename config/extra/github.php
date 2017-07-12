<?php
/**
 * Created by PhpStorm.
 * User: Tinywan
 * Date: 2017/7/12
 * Time: 14:06
 * 调用Github登录API接入到WordPress:https://www.iteblog.com/archives/1314.html
 * Mail: Overcome.wan@Gmail.com
 */
return [
    //向https://github.com/login/oauth/authorize发送get请求，获取code信息
    "OAUTH_URL" => 'https://github.com/login/oauth/authorize',
    "ACCESS_TOKEN_URL" => 'https://github.com/login/oauth/access_token',
    "USER_INFO_URL" => 'https://api.github.com/user',
    //这个参数是必须的，这就是我们在第一步注册应用程序之后获取到的Client ID
    "CLIENT_ID" => '5e70ee2d904f655b0c31',
    //这个参数是必须的，是我们在第一步注册应用程序之后获取到的Client Secret
    "CLIENT_SECRET" => 'd190c915d36b5feff7ceeb017ce35ab92e7cb38c',
    //该参数可选，当我们从Github获取到code码之后跳转到我们自己网站的URL
    "AUTHORIZATION_CALLBACK_URL" => 'http://www.tinywan.xyz:8086/frontend/index/redirect_uri',
    "HOMEPAGE_URL" => 'd190c915d36b5feff7ceeb017ce35ab92e7cb38c'
];