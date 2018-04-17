<?php
/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/25
 * Time: 15:16
 */
return [
    'app_title'          => '小功能页面',
    'view_replace_str' => [
        "__STATIC__" => "/share",
        "__JS__" => "/share/js",
        "__CSS__" => "/share/css",
        "__IMAGES__" => "/share/images",
        "__ASSETS__" => "/share/assets",
        "__PHOTOS__" => "/share/photos",
        "__FACES__" => "/share/faces",
        "__COMMON__" => "/common"
    ],
    'template'               => [
        //全局配置方式开启
        'layout_on'     =>  true,
        'layout_name'   =>  'layout',
    ]

];