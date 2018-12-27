<?php
/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/25
 * Time: 15:16
 */
return [
    'app_title'          => 'Tinywan杂货摊',
    //    // 是否开启路由
    'url_route_on'           => true,
    // 是否强制使用路由
    'url_route_must'         => false,
    // 视图输出字符串内容替换
    'view_replace_str' => [
        "__COMMON__" => "/common",
        "__STATIC__" => "/source",
        "__PLUG__" => "/source/plug",
    ],
    'template'               => [
        // 模板引擎类型 支持 php think 支持扩展
        'type'         => 'Think',
        // 视图基础目录，配置目录为所有模块的视图起始目录
        'view_base'    => '',
        // 当前模板的视图目录 留空为自动获取
        'view_path'    => '',
        // 模板后缀
        'view_suffix'  => 'html',
        // 模板文件名分隔符
        'view_depr'    => DS,
        // 模板引擎普通标签开始标记
        'tpl_begin'    => '{',
        // 模板引擎普通标签结束标记
        'tpl_end'      => '}',
        // 标签库标签开始标记
        'taglib_begin' => '{',
        // 标签库标签结束标记
        'taglib_end'   => '}',
        //全局配置方式开启
        'layout_on'     =>  true,
        'layout_name'   =>  'layout'
    ],
];