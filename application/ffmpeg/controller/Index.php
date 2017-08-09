<?php

/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/24
 * Time: 22:40
 */

namespace app\ffmpeg\controller;

use think\Controller;

class Index extends Controller
{
    /*
     * 视图输出测试
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * @return mixed
     * 视频编码首页
     */
    public function viewIndex()
    {
        $this->assign([
            'video_cut' => '视频剪切',
            'video_concat' => '视频合并',
            'name' => 'ThinkPHP',
            'email' => 'thinkphp@qq.com'
        ]);
        return $this->fetch();
    }

    /**
     * @return \think\response\View
     * 视频剪切
     * 思路图：
     * 【1】cut.html 通过各种途径获取到视频的基本信息，提交参数到videoCutOperation方法
     * 【2】videoCutOperation方法 根据视频ID从数据库获取视频的基本信息
     * 【3】shell 脚本 检查、输入参数、、目录、是否oss下载成功、视频剪切、截图、上传至阿里云oss
     * 【4】shell 脚本执行失败写入数据库记录,同时提示编辑页面信息
     * 【5】shell 脚本执行成功，则判断文件是否存在，获取视频时长、大小等信息，通知当前处理页面是否执行成功，插入数据库记录
     */
    public function videoCut()
    {
        $this->assign([
            'title' => 'MisssYang 视频剪切 12345',
            'video_path' => MEDIA_PATH . 'test_mp4_640k.mp4'
        ]);
        return $this->fetch();
    }

    /**
     * @return mixed
     * 视频合并
     */
    public function videoConcat()
    {
        $this->assign([
            'title' => ' MisssYang 视频合并 12345 1231'
        ]);
        return $this->fetch();
    }

    public function MisssYang()
    {
        $this->assign([
            'title' => ' MisssYang 视频合并 12345 1231'
        ]);
        return $this->fetch();
    }
}