<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  DateTime: 2017/11/7 13:46
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/

namespace app\common\validate;


use think\Validate;

class Vod extends Validate
{
    // 添加自动以规则验证
    protected $rule = [
        'name'  =>  'require|min:4',
        'hls_url' =>  'require',
        'live_id' =>  'require',
        'cid' =>  'require',
        'image_url' =>  'require',
        'content' =>  'require'
    ];

    // 自定义验证信息
    protected $message  =   [
        'name.require' => '视频名称不能为空',
        'name.min' => '视频名称不能小于4个字符',
        'hls_url.require' => '播放地址(HLS)不能为空',
        'live_id.require' => '所属活动ID不能为空',
        'cid.require' => '所属栏目ID不能为空',
        'image_url.require' => '图片地址不能为空',
        'content.require' => '视频内容不能为空',
    ];

    // 验证场景
    protected $scene = [
        'add'   =>  ['name','email'],
        'edit'  =>  ['email'],
    ];

    // 自定义验证规则,验证播放地址的格式
    protected function checkFormat($value, $rule, $data)
    {
        return $rule == $value ? true : '请输入合格的播放地址,目前只支持m3u8和MP4格式';
    }
}