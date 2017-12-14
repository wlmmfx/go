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
use OSS\OssClient;
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
     * 上传单个文件
     * @return string
     */
    public function uploadFile()
    {
        $bucket = config('aliyun_oss.BUCKET');
        $filePath = ROOT_PATH . 'public' . DS . 'uploads/' . "5791827221668267.webm";
        $fileName = "56799999999999999.webm";
        $object = 'WebRTC789';
        $res = Oss::uploadFile($bucket, $filePath, $fileName, $object);
        if ($res['code']) return "success";
        return $res['msg'];
    }

    /**
     * 上传本地目录
     * @return string
     */
    public function uploadDir()
    {
        $bucket = config('aliyun_oss.BUCKET');
        $prefix = 'WebRTC789/web';
        $localDirectory = ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'web';
        Log::error($localDirectory);
        $res = Oss::uploadDir($bucket, $prefix, $localDirectory);
        if ($res['code']) return "success";
        return $res['msg'];
    }

    /**
     * OSS 文件下载到服务器，然后再读取文件下载到客户端
     */
    public function fileDownload()
    {
        $bucket = config('aliyun_oss.BUCKET');
        $object = 'WebRTC/31731102727331063.webm';
        Log::error($object);
        $oss = Oss::Instance();
        $filepath = ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . '888888888.webm';
        $options = [
            $oss::OSS_FILE_DOWNLOAD => $filepath
        ];
        $res = $oss->getObject($bucket, $object, $options);
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($filepath));
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control:must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);
    }

    /**
     * OSS 文件直接下载到客户端
     */
    public function fileDownload2()
    {
        $outfile = '2323232323.webm';
        $sourceFile = 'http://tinywan-oss.oss-cn-shanghai.aliyuncs.com/WebRTC/31731102727331063.webm';
        header("Cache-Control:");
        header("Cache-Control: public");
        //设置输出浏览器格式
        header("Content-Type: application/octet-stream");
        header("Content-Disposition: attachment; filename=" . $outfile);
        header("Accept-Ranges: bytes");
        ob_end_clean();
        readfile($sourceFile);
    }

    /**
     * Html5 Ajax多图片可预览上传图片=====================================================================================
     * @return mixed
     */
    public function uploadImage()
    {
        if (request()->isPost()) {
            Log::error('-----11111111-------' . $_FILES['FileData']['name']);
            $file_path = ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'uploads/' . $_FILES['FileData']['name'];
            $returnMsg = "{status:true}";
            move_uploaded_file($_FILES["FileData"]["tmp_name"], $file_path);
            return $returnMsg;
        }
        return $this->fetch();
    }

    /**
     * Html5 轻松解决跨域无刷新上传图片====================================================================================
     * Help: https://mengkang.net/375.html
     * @return string
     */
    public function html5UploadImageHandle()
    {
        if (request()->isPost()) {
            header('Access-Control-Allow-Origin:*');
            Log::error('-----11111111-------' . json_encode($_FILES));
            $fileName = 'test' . rand(0000, 99999) . '.jpg';
            $destination = ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'uploads/'.$fileName;
            if (!move_uploaded_file($_FILES["fileToUpload"]['tmp_name'], $destination)) {
                return json(array('info' => '上传失败'));
            } else {
                $imgUrl = "https://www.tinywan.com/uploads/".$fileName;
                return json(array('url' => $imgUrl));
            }
        }
        return $this->fetch();
    }
}





