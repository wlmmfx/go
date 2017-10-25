<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  DateTime: 2017/9/28 15:44
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/

namespace app\backend\controller;


use aliyun\oss\OssInstance;
use app\common\controller\BaseBackend;
use \FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFMpeg;
use OSS\Core\OssException;
use think\Db;
use think\Log;

class Live extends BaseBackend
{
    const AUTH_PRIVATEKEY = 'Tinywan123';
    protected $db;

    public function _initialize()
    {
        parent::_initialize();
        $this->db = new \app\common\model\Live();
    }

    /**
     * 获取推流地址auth_key
     * @param string $sourceName
     * @param string $domainName
     * @param string $appName
     * @param $streamName
     * @param $startTime
     * @param $expireTime
     * @param string $cdn
     * @return mixed
     */
    public static function getPushUrlAuthKey($sourceName = 'live.tinywan.com', $domainName = 'live.tinywan.com', $appName = 'live', $streamName, $startTime, $expireTime, $cdn = 'live.tinywan.com')
    {
        $auth_key = self::AUTH_PRIVATEKEY;
        $timestatmp = strtotime(date('Y-m-d H:i:s', strtotime($startTime . "+" . $expireTime . " minute ")));
        $rtmp_auth_md5 = md5("/" . $appName . "/" . $streamName . "-" . $timestatmp . "-0-0-" . $auth_key);
        $hls_auth_md5 = md5("/" . $appName . "/" . $streamName . ".m3u8-" . $timestatmp . "-0-0-" . $auth_key);

        $authUrl['push_flow_address'] = "rtmp://$sourceName/$appName/$streamName?vhost=$domainName&auth_key=" . $timestatmp . "-0-0-" . $rtmp_auth_md5;
        $authUrl['play_rtmp_address'] = "rtmp://$domainName/$appName/$streamName?auth_key=" . $timestatmp . "-0-0-" . $rtmp_auth_md5;
        $authUrl['play_m3u8_address'] = "http://$cdn/$appName/$streamName.m3u8?auth_key=" . $timestatmp . "-0-0-" . $hls_auth_md5;
        $authUrl['hash_value'] = 'startTime = ' . $startTime . ' expireTime = ' . $expireTime . ' timestatmp = ' . $timestatmp . "uri = /" . $appName . "/" . $streamName . ".m3u8-" . $timestatmp . "-0-0-" . $auth_key;
        return $authUrl;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 活动管理
     * @return mixed
     */
    public function liveIndex()
    {
        $lives = Db::table("resty_live")
            ->alias('l')
            ->join('resty_category c', 'c.id = l.cate_id')
            ->field("l.id,l.liveStartTime,l.name,l.createTime,l.liveEndTime,l.recordStatus,c.name as c_name")
            ->order("l.createTime desc,l.liveStartTime desc")
            ->paginate(4);
        $categorys = db('category')->where('pid', 116)->order('id desc')->select();
        $this->assign('lives', $lives);
        $this->assign('categorys', $categorys);
        return $this->fetch();
    }

    /**
     * 保存
     */
    public function liveStore()
    {
        if (request()->isPost()) {
            $streamName = '400' . time();
            $startTime = date('Y-m-d H:i:s', time());
            $res = $this->db->store(input('post.'));
            if ($res["valid"]) {
                $this->success($res["msg"], "backend/live/liveIndex");
                exit;
            } else {
                $this->error($res["msg"]);
                exit;
            }
        }
    }

    /**
     * 活动详细信息
     */
    public function liveDetail()
    {
        $liveId = input('param.id');
        $videos = Db::table('resty_stream_video')->order('createTime desc')->paginate(12);
        $this->assign('videos', $videos);
        return $this->fetch();
    }

    /**
     * 视频管理
     */
    public function videoManage()
    {
        $videos = Db::table('resty_stream_video')->order('createTime desc')->paginate(12);
        $this->assign('videos', $videos);
        return $this->fetch();
    }

    /**
     * 素材管理，上传视频管理
     */
    public function uploadVideoManage()
    {
        $videos = db('stream_video')->where('type',2)->order('id desc')->paginate(6);
        $this->assign('videos', $videos);
        return $this->fetch();
    }

    /**
     * 预览观看
     */
    public function liveView()
    {
        $id = input('param.id');
        $this->assign('live', Db::table('resty_live')->where('id', $id)->find());
        return $this->fetch();
    }

    /**
     * 录像列表
     * @return mixed
     */
    public function indexRecord()
    {
        if (request()->isPost()) {
            $liveStartTime = input('post.liveStartTime');
            halt($liveStartTime);
        }
        $liveId = input('param.id');
        $videos = Db::table('resty_stream_video')->where('streamName', $liveId)->order('createTime desc')->paginate(12);
        $this->assign('videos', $videos);
        return $this->fetch();
    }

    /**
     * 录像操作
     */
    public function recordHandle()
    {
        if ($this->request->isAjax()) {
            $data = input('post.');
            $res = $this->db->recordHandle($data);
            if ($res['valid']) {
                return json(['code' => 200, 'msg' => $res["msg"]]);
            }
            return json(['code' => 500, 'msg' => $res["msg"]]);
        }
        return json(['code' => 401, 'msg' => "Not Forbidden"]);
    }

    /**
     * 视频上传管理
     */
    public function videoUploadFrom()
    {
        if (request()->isPost()) {
            $file = request()->file("myfile");
            if ($file) {
                $data = input('post.*');
                $info = $file->rule("uniqid")->move(ROOT_PATH . 'public' . DS . 'uploads/videos');
                if ($info) {
                    // 成功上传后 获取上传信息
                    $res = [
                        'code' => 200,
                        'msg' => 'success',
                        'fileId' => $info->getSaveName()
                    ];
                } else {
                    $res = [
                        'code' => 500,
                        'msg' => $file->getError()
                    ];
                }
                return json($res);
            }
            return json(['code' => 500, 'msg' => "upload file name error"]);
        }
        //临时关闭当前模板的布局功能
        $this->view->engine->layout(false);
        return $this->fetch();
    }

    /**
     * 截图操作
     */
    public static function cutImageHandle($fileTmpPath, $cutImageName, $savePath,$cutImageTime)
    {
        $video = self::ffmpeg()->open($fileTmpPath);
        //截取视频图片 2s 时候截取
        $video->frame(TimeCode::fromSeconds($cutImageTime))
            ->save($savePath . DS. $cutImageName);
        return $cutImageName;
    }

    /**
     * 素材视频图片上传
     * @return mixed|\think\response\Json
     */
    public function liveVideoUploadFrom()
    {
        if (request()->isPost()) {
            $file = request()->file("video_file");
            if ($file) {
                $id = input('post.id');
                $upload_desc = input('post.upload_desc');
                $cutImageTime = input('post.cut_image_time');
                $startPath = ROOT_PATH . 'public' . DS . 'uploads/videos/'. $id ;
                // 根据唯一标识目录创建，这里应该判断是否具有权限创建
                if (!is_dir($startPath)) mkdir($startPath,0755, true);
                if (!is_dir($startPath. "/video")) mkdir($startPath. "/video",0755, true);
                $savePath = $startPath. "/video";
                // 检查目录是否可写
                //检测上传文件

                $info = $file->rule("uniqid")->move($savePath);
                if ($info) {
                    // 成功上传后 获取上传信息
                    $fileTmpPath = $savePath . DS . $info->getSaveName();
                    $baseName = $info->getFilename();
                    $ext = $info->getExtension();
                    $fileSize = self::getVideoSize($fileTmpPath);
                    $duration = self::getVideoDuration($fileTmpPath);
                    $cutImageName = pathinfo($fileTmpPath)['filename'] . '.jpg';
                    $screenshotName = self::cutImageHandle($fileTmpPath, $cutImageName, $savePath,$cutImageTime);

                    $videoData = [
                        'streamName' => $id,
                        'channelId' => $id,
                        'name' => $baseName,
                        'type' => 2, // 1.录像 2.上传 3.编辑
                        'fileName' => $baseName,
                        'fileTime' => date("Y-m-d H:i:s"),
                        'fileSize' => $fileSize,
                        'duration' => $duration,
                        'createTime' => date("Y-m-d H:i:s"),
                        'screenshot' => $screenshotName,
                        'desc' => $upload_desc,
                    ];

                    //oss upload
                    $oss = OssInstance::Instance();
                    $bucket = config('aliyun_oss.BUCKET');
                    $ossbObject = 'data/' . $id.'/video';
                    try {
                        $oss->uploadDir($bucket, $ossbObject, $savePath.DS);
                        $videoData['oss_upload_status'] = 1;
                    } catch (OssException $e) {
                        $videoData['oss_upload_status'] = json_encode($e->getMessage());
                    }
                    //插入数据库
                    $insertRes = Db::table('resty_stream_video')->insertGetId($videoData);
                    if ($insertRes) {
                        // 遍历删除原图和缩略图
                        $res = [
                            'code' => 200,
                            'msg' => '恭喜你，上传成功',
                            'data' => [
                                'Id' => $id,
                                'Extension' => $ext,
                                'Filename' => $baseName,
                                'format_name' => self::ffprobe()->format($fileTmpPath)->get("format_name"),
                                'format_long_name' => self::ffprobe()->format($fileTmpPath)->get("format_long_name"),
                                'bit_rate' => self::ffprobe()->format($fileTmpPath)->get("bit_rate"),
                                'Size' => trans_byte($fileSize),
                                'Duration' => gmstrftime('%H:%M:%S', $duration),
                                'width' => self::ffprobe()->streams($fileTmpPath)->videos()->first()->get('width'),
                                'height' => self::ffprobe()->streams($fileTmpPath)->videos()->first()->get('height'),
                                'codec_type' => self::ffprobe()->streams($fileTmpPath)->videos()->first()->get('codec_type'),
                                'codec_long_name' => self::ffprobe()->streams($fileTmpPath)->videos()->first()->get('codec_long_name'),
                            ]
                        ];
                        $this->rmdirs($savePath);
                    } else {
                        $res = [
                            'code' => 500,
                            'msg' => '上传成功，插入数据库失败',
                            'data' => null
                        ];
                    }
                } else {
                    $res = [
                        'code' => 500,
                        'msg' => $file->getError()
                    ];
                }
                return json($res);
            }
            return json(['code' => 500, 'msg' => "upload file name error"]);
        }
        $id = input('param.id');
        $this->assign('live', Db::table('resty_live')->where('id', $id)->find());
        return $this->fetch();
    }

    /**
     * 通过FFmpeg 获取视频信息
     */
    public function getVideoInfoByFFmpeg(){
        $MP4Path = '/home/www/web/go-study-line/public/uploads/videos/201710002/video/59ef43871b3ee.mp4';
        $videoInfo = self::ffprobe()->format($MP4Path);
        $streamInfo = self::ffprobe()->streams($MP4Path);
        halt($videoInfo);
        halt($streamInfo);
    }
}