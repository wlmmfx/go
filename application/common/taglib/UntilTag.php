<?php

/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/20 9:10
 * |  Mail: Overcome.wan@Gmail.com
 * |  Function: tablib标签库自定义方法详解
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\common\taglib;


use think\template\TagLib;

class UntilTag extends TagLib
{
    protected $tags = [
        'breadcrumb' => ['attr' => 'name', 'close' => 0]
    ];

    /**
     * {UntilTag:breadcrumb name='个人中心/修改密码' /}
     * @param $tag
     * @param $content
     * @return string
     */
    public function tagBreadcrumb($tag, $content)
    {
        $tags = '';
        if (isset($tag['name']) && !empty($tag['name'])) {
            $tags = explode('/', $tag['name']);
        }
        $parseStr = '<nav class="breadcrumb"><i class="Hui-iconfont"></i><a class="maincolor" href="{:url(" rel="external nofollow" index")}">首页</a>';
        if (!empty($tags)) {
            foreach ($tags as $vo) {
                $parseStr .= "<span class='c-666 en'>></span><span class='c-666'>{$vo}</span>";
            }
        }
        $parseStr .= '</nav>';
        return $parseStr;
    }
}