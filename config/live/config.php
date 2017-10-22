<?php
/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/25
 * Time: 15:16
 */
return [
    'app_title'          => '个人博客',
    'view_replace_str' => [
        "__STATIC__" => "/live",
        "__JS__" => "/live/js",
        "__CSS__" => "/live/css",
        "__IMAGES__" => "/live/images",
        "__PICTURE__" => "/live/picture",
        "__COMMON__" => "/common"
    ],
    'template'               => [
        //全局配置方式开启
        'layout_on'     =>  true,
        'layout_name'   =>  'layout',
    ]
];