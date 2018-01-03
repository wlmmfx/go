<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/1/3 16:56
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\test\controller;


use think\Controller;

class ImageDemo extends Controller
{
    // imagepng — 以 PNG 格式将图像输出到浏览器或文件
    public function imagepngDemo01()
    {
        // gd_info — 取得当前安装的 GD 库的信息
        var_dump(gd_info());
        $filePath = ROOT_PATH . 'public/common/images/video-demo.png';

        // 以文件方式打开
        $size_info1 = getimagesize($filePath); //getimagesize — 取得图像大小
        // 以字符串格式打开
        $data = file_get_contents($filePath);
        $size_info2 = getimagesizefromstring($data);
        var_dump($size_info1);
        halt($size_info2);
        $img = imagecreatefrompng($filePath);
        imagepng($img);
    }
}