<?php

/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/8/28 14:42
 * |  Mail: Overcome.wan@Gmail.com
 * '-------------------------------------------------------------------*/

namespace app\common\controller;

use aliyun\oss\OssInstance;
use app\common\model\TaskList;
use FFMpeg\FFMpeg;
use FFMpeg\FFProbe;
use think\Controller;
use think\Log;

class Base extends Controller
{
    // 任务实例
    public $task_db;

    // 自定义状态码
    static $return_code = [
        '200' => '操作成功',
        '301' => '网站已被永久移动到新位置',
        '302' => '网站已被临时移动到新位置',
        '401' => '身份验证错误,此页要求授权',
        '403' => '(禁止)服务器拒绝请求',
        '404' => '服务器找不到请求的网页',
        '500' => '服务器遇到错误，无法完成请求',
        // 扩展状态码
        '2001' => '添加成功',
        '5001' => '添加失败',
        '2002' => '更新成功',
        '5002' => '更新失败',
        '2003' => '删除成功',
        '5003' => '删除失败',
        '2004' => '用户账号被禁用'
    ];


    /**
     * 初始化操作
     * @return bool
     */
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->task_db = new TaskList();
    }

    /**
     * 添加任务队列
     * @return bool
     */
    public function addTaskList($data)
    {
        return $this->task_db->addTaskList($data);
    }

    /**
     * json格式返回状态码模板
     * @param string $code
     * @param string $msg
     * @param array $data
     * @return array
     */
    public static function returnCode($code = '', $msg = '', $data = [])
    {
        $return_data = [
            'code' => '500',
            'msg' => '未定义消息',
            'data' => $code == 1001 ? $data : []
        ];
        if (empty($code)) return $return_data;
        $return_data['code'] = $code;
        if (!empty($msg)) {
            $return_data['msg'] = $msg;
        } else if (isset(self::$return_code[$code])) {
            $return_data['msg'] = self::$return_code[$code];
        }
        return json($return_data);
    }

    public function rmdirs($dir)
    {
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->rmdirs($fullpath);
                }
            }
        }
        closedir($dh);
        if (rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }

    /** 格式化时间
     * @param $time
     * @return false|string
     * @static
     */
    public static function formatDate($time)
    {
        return date('Y-m-d H:i:s', $time);
    }

    /**
     * FFmpeg  static Instance
     * @return FFMpeg
     * @static
     */
    protected static function ffmpeg()
    {
        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries' => config("ffmpeg")["FFMPEG_BINARIES_PATH"],
            'ffprobe.binaries' => config("ffmpeg")["FFPROBE_BINARIES_PATH"]
        ]);
        return $ffmpeg;
    }

    /**
     * FFProbe static Instance
     * @return FFProbe
     * @static
     */
    protected static function ffprobe()
    {
        $ffprobe = FFProbe::create([
            'ffmpeg.binaries' => config("ffmpeg")["FFMPEG_BINARIES_PATH"],
            'ffprobe.binaries' => config("ffmpeg")["FFPROBE_BINARIES_PATH"]
        ]);
        return $ffprobe;
    }

    /**
     * 获取视频时长
     * @param $file_path
     * @return mixed
     */
    protected static function getVideoDuration($file_path)
    {
        return self::ffprobe()->format($file_path)->get("duration");
    }

    /**
     * 获取视频大小
     * @param $file_path
     * @return mixed
     * @static
     */
    protected static function getVideoSize($file_path)
    {
        return self::ffprobe()->format($file_path)->get("size");
    }

    /**
     * OSS 实例
     */
    protected static function oss()
    {
        $instance = OssInstance::Instance();
        if(false == $instance){
            Log::error(getCurrentDate().'-------------OSS配置文件错误');
            return false;
        }
        return $instance;
    }
}