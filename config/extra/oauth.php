<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/23 19:08
 * |  Mail: Overcome.wan@Gmail.com
 * |  Function: 开放平台配置中心
 * '------------------------------------------------------------------------------------------------------------------*/
return [
    'github'=>[
        'client_id' => '5e70ee2d904f655b0c31',
        'client_secret' => 'f95da36c53cea839a346fd76c787adbe2c884caa',
        'redirect_uri' => 'https://www.tinywan.com/api/OAuth/gitHubRedirectUri',
        'access_token_uri' => 'https://github.com/login/oauth/access_token'
    ],
    'qq'=>[
        'app_id' => '101452596',
        'app_key' => '751ef40924f54b59ca42064050f08292',
        'call_back_url' => 'https://www.tinywan.com/api/OAuth/qqRedirectUri'
    ],
];