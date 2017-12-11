<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/10 13:14
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\index\controller;


use aliyun\oss\Oss;
use app\common\controller\BaseFrontend;
use OSS\Core\OssException;
use think\Db;
use think\Log;

class WebRtc extends BaseFrontend
{
    public function index()
    {
        $this->assign('uid', 13669361192);
        return $this->fetch();
    }

    /**
     * 录制Mp4格式
     * @return mixed
     */
    public function recordMp4()
    {
        $this->assign('uid', 13669361192);
        return $this->fetch();
    }

    /**
 * 上传视频文件
 * $_FILES = {"video-blob":{"name":"blob","type":"video\/webm","tmp_name":"\/tmp\/phpLGMdIl","error":0,"size":3874663}}
 */
    public function recordUploadToServer()
    {
        foreach (['video', 'audio'] as $type) {
            if (isset($_FILES["${type}-blob"])) {
                $name = $_FILES["${type}-blob"]['name'];
                $fileSize = $_FILES["${type}-blob"]['size'];

                $fileName = request()->post("${type}-filename");
                $uid = request()->post("${type}-uid");
                $filePath = ROOT_PATH . 'public' . DS . 'uploads/' . $fileName;
                if (move_uploaded_file($_FILES["${type}-blob"]["tmp_name"], $filePath)) {
                    $videoData = [
                        'streamName' => time(),
                        'liveId' => $uid,
                        'name' => $name,
                        'type' => 4, // 1.录像 2.上传 3.编辑 4.WebRTC
                        'fileName' => $fileName,
                        'fileTime' => date("Y-m-d H:i:s"),
                        'fileSize' => $fileSize,
                        'duration' => 1231,
                        'createTime' => date("Y-m-d H:i:s"),
                        'desc' => 'WebRTC',
                    ];
                    $oss = Oss::Instance();
                    $bucket = config('aliyun_oss.BUCKET');
                    // 单文件上传这里必须指定文件名
                    $object = 'WebRTC/' . $fileName;
                    try {
                        $oss->uploadFile($bucket, $object, $filePath);
                        $videoData['oss_upload_status'] = 1;
                    } catch (OssException $e) {
                        $res = [
                            'code' => 500,
                            'msg' => $e->getMessage() . "\n"
                        ];
                        return $res;
                    }
                    //插入数据库
                    $insertRes = Db::table('resty_stream_video_edit')->insertGetId($videoData);
                    if ($insertRes) {
                        $res = [
                            'code' => 200,
                            'msg' => '上传成功，插入数据库成功'
                        ];
                    } else {
                        $res = [
                            'code' => 500,
                            'msg' => '上传成功，插入数据库失败'
                        ];
                    }
                } else {
                    $res = [
                        'code' => 500,
                        'msg' => 'problem moving uploaded file'
                    ];
                }
                Log::debug("==============" . json_encode($res));
                return json($res);
            }
        }
    }

    /**
     * 上传视频文件
     * $_FILES = {"video-blob":{"name":"blob","type":"video\/webm","tmp_name":"\/tmp\/phpLGMdIl","error":0,"size":3874663}}
     */
    public function recordUploadToServerDemo()
    {
        foreach (['video', 'audio'] as $type) {
            if (isset($_FILES["${type}-blob"])) {
                Log::error("------FILES-------" . json_encode($_FILES));
                $name = $_FILES["${type}-blob"]['name'];
                $fileSize = $_FILES["${type}-blob"]['size'];

                $fileName = $_POST["${type}-filename"];
                $uid = $_POST["${type}-uid"];
                $filePath = ROOT_PATH . 'public' . DS . 'uploads/' . $fileName;
                Log::error("------recordUploadToServer-------" . $fileName);
                if (move_uploaded_file($_FILES["${type}-blob"]["tmp_name"], $filePath)) {
                    $videoData = [
                        'streamName' => time(),
                        'liveId' => $uid,
                        'name' => $name,
                        'type' => 4, // 1.录像 2.上传 3.编辑 4.WebRTC
                        'fileName' => $fileName,
                        'fileTime' => date("Y-m-d H:i:s"),
                        'fileSize' => $fileSize,
                        'duration' => 1231,
                        'createTime' => date("Y-m-d H:i:s"),
                        'desc' => 'WebRTC',
                    ];
                    $oss = Oss::Instance();
                    $bucket = config('aliyun_oss.BUCKET');
                    // 单文件上传这里必须指定文件名
                    $object = 'WebRTC/' . $fileName;
                    try {
                        $oss->uploadFile($bucket, $object, $filePath);
                        $videoData['oss_upload_status'] = 1;
                    } catch (OssException $e) {
                        $res = [
                            'code' => 500,
                            'msg' => $e->getMessage() . "\n"
                        ];
                        return $res;
                    }
                    //插入数据库
                    $insertRes = Db::table('resty_stream_video_edit')->insertGetId($videoData);
                    if ($insertRes) {
                        $res = [
                            'code' => 200,
                            'msg' => '上传成功，插入数据库成功'
                        ];
                    } else {
                        $res = [
                            'code' => 500,
                            'msg' => '上传成功，插入数据库失败'
                        ];
                    }
                } else {
                    $res = [
                        'code' => 500,
                        'msg' => 'problem moving uploaded file'
                    ];
                }
                Log::debug("==============" . json_encode($res));
                return json($res);
            }
        }
    }

    /**
     *
     */
    public function recordUploadToServer2()
    {
        $filePath = ROOT_PATH . 'public' . DS . 'uploads/' . "7327288799239044.webm";
        $oss = Oss::Instance();
        $bucket = config('aliyun_oss.BUCKET');
        // object名称 这里一定要指定 object名称 存储的文件名 错误：$object = 'WebRTC/';
        $object = 'WebRTC/water_logo.png';
        try {
            $oss->uploadFile($bucket, $object, $filePath);
        } catch (OssException $e) {
            printf(__FUNCTION__ . ": FAILED\n");
            printf($e->getMessage() . "\n");
            return;
        }
        print(__FUNCTION__ . ": OK" . "\n");
    }
}