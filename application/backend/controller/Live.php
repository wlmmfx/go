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


use aliyun\oss\Oss;
use app\common\controller\BaseBackend;
use \FFMpeg\Coordinate\TimeCode;
use FFMpeg\Format\Video\X264;
use OSS\Core\OssException;
use think\Db;
use think\Log;
use think\Request;

class Live extends BaseBackend
{
    const AUTH_PRIVATEKEY = 'Tinywan123';
    const SHELL_SCRIPT_PATH = "/home/www/web/go-study-line/shell/ffmpeg/";
    const RESULT_FILE_PATH = "/home/www/ffmpeg-data/";
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
        $lives = Db::table("resty_live")
            ->alias('l')
            ->join('resty_file f', 'f.live_id = l.id')
            ->where('l.id', $liveId)
            ->field("l.id,l.liveStartTime,l.name,l.createTime,l.liveEndTime,l.recordStatus,f.path")
            ->order("f.id desc")
            ->paginate(12);
        $videos = Db::table('resty_stream_video')->order('createTime desc')->paginate(12);
        $this->assign('videos', $videos);
        $this->assign('lives', $lives);
        $this->assign('liveId', $liveId);
        return $this->fetch();
    }

    /**
     * 直播图片上传
     * @return mixed|\think\response\Json
     */
    public function liveImageUpload()
    {
        if (request()->isPost()) {
            $file = request()->file("video_file");
            if ($file) {
                $liveId = input('param.id');
                $startPath = ROOT_PATH . 'public' . DS . 'uploads/videos/' . $liveId;
                // 根据唯一标识目录创建，这里应该判断是否具有权限创建
                if (!is_dir($startPath)) mkdir($startPath, 0755, true);
                if (!is_dir($startPath . "/video")) mkdir($startPath . "/video", 0755, true);
                $savePath = $startPath . "/video";

                $info = $file->rule("uniqid")->move($savePath);
                if ($info) {
                    // 成功上传后 获取上传信息
                    $videoData = [
                        'pid' => 0,
                        'live_id' => $liveId,
                        'path' => $info->getSaveName(),
                        'min_path' => $info->getSaveName()
                    ];
                    //oss upload
                    $oss = Oss::Instance();
                    $bucket = config('aliyun_oss.BUCKET');
                    $ossbObject = 'data/' . $liveId . '/video';
                    try {
                        $oss->uploadDir($bucket, $ossbObject, $savePath . DS);
                        $videoData['oss_upload_status'] = 1;
                    } catch (OssException $e) {
                        $videoData['oss_upload_status'] = json_encode($e->getMessage());
                    }
                    //插入数据库
                    $insertRes = Db::table('resty_file')->insertGetId($videoData);
                    if ($insertRes) {
                        // 遍历删除原图和缩略图
                        $res = [
                            'code' => 200,
                            'msg' => '恭喜你，上传成功',
                            'data' => ['live_id' => $liveId, 'image_path' => $info->getSaveName()]
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
        $videos = db('stream_video_edit')->where('type', 2)->order('id desc')->paginate(6);
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
    public static function cutImageHandle($fileTmpPath, $cutImageName, $savePath, $cutImageTime)
    {
        $video = self::ffmpeg()->open($fileTmpPath);
        //截取视频图片 2s 时候截取
        $video->frame(TimeCode::fromSeconds($cutImageTime))
            ->save($savePath . DS . $cutImageName);
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
                $startPath = ROOT_PATH . 'public' . DS . 'uploads/videos/' . $id;
                // 根据唯一标识目录创建，这里应该判断是否具有权限创建
                if (!is_dir($startPath)) mkdir($startPath, 0755, true);
                if (!is_dir($startPath . "/video")) mkdir($startPath . "/video", 0755, true);
                $savePath = $startPath . "/video";
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
                    $fileName = pathinfo($fileTmpPath)['filename'];
                    $cutImageName = $fileName . '.jpg';
                    $screenshotName = self::cutImageHandle($fileTmpPath, $cutImageName, $savePath, $cutImageTime);

                    $videoData = [
                        'streamName' => $id,
                        'liveId' => $id,
                        'name' => $fileName,
                        'type' => 2, // 1.录像 2.上传 3.编辑
                        'fileName' => $fileName,
                        'fileTime' => date("Y-m-d H:i:s"),
                        'fileSize' => $fileSize,
                        'duration' => $duration,
                        'createTime' => date("Y-m-d H:i:s"),
                        'screenshot' => $screenshotName,
                        'desc' => $upload_desc,
                    ];

                    //oss upload
                    $oss = Oss::Instance();
                    $bucket = config('aliyun_oss.BUCKET');
                    $ossbObject = 'data/' . $id . '/video';
                    try {
                        $oss->uploadDir($bucket, $ossbObject, $savePath . DS);
                        $videoData['oss_upload_status'] = 1;
                    } catch (OssException $e) {
                        $videoData['oss_upload_status'] = json_encode($e->getMessage());
                    }
                    //插入数据库
                    $insertRes = Db::table('resty_stream_video_edit')->insertGetId($videoData);
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

    /**---------------------------------------------视频编辑开始-------------------------------------------------------*/

    /**
     * 初始化视频到resty_stream_video_edit表中
     * @return string
     */
    public function initStreamVideoEditTable()
    {
        $liveId = request()->get("liveId");
        if ($liveId == "") {
            return "请输入 $liveId, 你可以在URL地址栏后添加：?liveId=201710002";
        }
        # [1] 查询 LiveVideo 表
        $videolist = Db::table('resty_stream_video')->where('liveId', $liveId)->select();
        if ($videolist) {
            try {
                $differentCount = 0;
                $copyCount = count($videolist);
                # [2] 循环插入livevideo_copy 表
                for ($k = 0; $k < count($videolist); $k++) {
                    $findResult = Db::table('resty_stream_video_edit')
                        ->where('liveId', $videolist[$k]['liveId'])
                        ->where("fileName", $videolist[$k]['fileName'])
                        ->find();
                    # [3] continue本身并不跳出循环结构，只是放弃这一次循环,也就是说这个数据存在的话则不再重复插入哦
                    if (count($findResult) > 0) continue;
                    $data = [
                        'streamName' => $videolist[$k]['streamName'],
                        'liveId' => $videolist[$k]['liveId'],
                        'type' => $videolist[$k]['type'],
                        'state' => 0,
                        'name' => $videolist[$k]['name'],
                        'fileName' => $videolist[$k]['fileName'],
                        'fileTime' => $videolist[$k]['fileTime'],
                        'duration' => $videolist[$k]['duration'],
                        'fileSize' => $videolist[$k]['fileSize'],
                        'createTime' => $videolist[$k]['createTime'],
                        'pid' => -1,
                        'eidittype' => '0',
                        'editid' => '0',
                        'videoparts' => '',
                        'editresult' => '0',
                        'editmsg' => '',
                        'version' => $videolist[$k]['version']
                    ];
                    Db::table('resty_stream_video_edit')->insert($data);
                    $differentCount += 1;
                    Log::alert('[' . getCurrentDate() . ']:' . " resty_stream_video_edit differentCount = " . $differentCount);
                }
            } catch
            (\Exception $e) {
                Log::error('错误信息' . $e->getMessage());
                exit('执行错误!');
            }
            $copiedRecordCount = 'Diff:'.$differentCount . '/Total:' . $copyCount;
        }
        #  [4] 返回总条数和成功插入的数据的百分比和自增主键(id)的值
        return json(['code' => 200, 'msg' => $copiedRecordCount]);
    }

    /**
     * 素材编辑
     * @return mixed
     */
    public function videoEdit()
    {
        $Videos = Db::table('resty_stream_video_edit')->where('type', 2)->order('createTime desc')->paginate(12);
        $editVideos = Db::table('resty_stream_video_edit')->where('type', 3)->order('createTime desc')->paginate(12);
        $this->assign('videos', $Videos);
        $this->assign('editVideos', $editVideos);
        return $this->fetch();
    }

    /**
     * 根据视频 videoid 获取部分视频信息
     * @param $videoId
     * @return mixed
     */
    private function videoInfoByVideoId($id)
    {
        $video = Db::table('resty_stream_video_edit')->where('id', $id)->find();
        $result['liveId'] = $video['streamName'];
        $result['streamName'] = $video['streamName'];
        $result['name'] = $video["name"];
        $result['fileName'] = $video["fileName"];
        $result['duration'] = $video["duration"];
        $result['fileSize'] = $video["fileSize"];
        $result['version'] = $video["version"];
        return $result;
    }

    /**
     * 视频消息提示
     * @param $editCode
     * @return mixed|string
     */
    private function editResultMsg($editCode)
    {
        $msg = [
            '0' => 'success',
            '-9' => 'param is not all',
            '-8' => 'mv file file failed',
            '-7' => 'ffmpeg cut file failed',
            '-6' => 'move file error',
            '-5' => 'create thumbnail error',
            '-4' => 'ffmpeg slice fail',
            '-1' => 'unknown error'
        ];

        $result = $msg[$editCode];
        if ($result == null) return "unknown code " . $editCode;
        return $result;
    }

    /**
     * 素材剪切编辑
     * @return mixed
     */
    public function videoCutOperate()
    {
        if (!request()->isAjax()) return json(['status' => 403, 'msg' => "非Ajax请求"]);
        $starttime = request()->post("start_time");
        $endtime = request()->post("end_time");
        $new_video_id = request()->post("new_video_id");
        $new_video_name = request()->post("new_video_name");
        $origin_video_id = request()->post("origin_video_id");
        if (empty($starttime) && empty($endtime) && empty($new_video_name) && empty($origin_video_id)) {
            return json(['status' => 403, 'msg' => "参数不能为空"]);
        }
        #   根据LiveId获取视频信息
        $editid = $new_video_id;
        Log::info('[' . getCurrentDate() . ']:' . "[01]视频剪切操作参数]$origin_video_id ： " . $origin_video_id);
        $origVideoInfo = $this->videoInfoByVideoId($origin_video_id);
        $liveId = $origVideoInfo['liveId'];
        Log::info('[' . getCurrentDate() . ']:' . "[02]视频剪切操作参数 ： " . json_encode($origVideoInfo));
        $sourcefile = $origVideoInfo['name'];
        $version = $origVideoInfo['version'];
        $streamName = $origVideoInfo['version'];
        $shell_script = self::SHELL_SCRIPT_PATH . "check_oss_cut.sh";
        $cmdStr = "{$shell_script} {$liveId} {$sourcefile} {$starttime} {$endtime} {$editid} {$new_video_name} 0";
        // 根据版本号拼接视频文件名
        Log::info('[' . getCurrentDate() . ']:' . "[03]视频剪切操作Shell 脚本参数 ： " . $cmdStr);
        exec("{$cmdStr}", $results, $sysStatus);
        $shellResult = -1;
        #   如果命令执行错误，则exec 的第二个参数会返回shell 脚本的 echo 出的值，$results 返回结果为一个数组
        if (count($results) == 1) $shellResult = $results[0];
        $shellResultMsg = $this->editResultMsg($shellResult);
        #   ffmpeg 脚本是否执行成功
        if ($sysStatus != 0) {
            Log::error('[' . getCurrentDate() . ']:' . ' [04]视频剪切操作参数 system function exec() run shell failed  ,return code : ' . $sysStatus);
            $pid = $origin_video_id;
            $editresultcode = $shellResult;
            $editmsg = $shellResultMsg;
            $this->saveCutDataToDb($liveId, $sourcefile, $filesize = '0', $duration = '0', $pid, $editid, $editresultcode, $editmsg, $new_video_name, $version, $streamName);
            return json(['status' => 500, 'msg' => "shell error"]);
        }
        Log::info('[' . getCurrentDate() . ']:' . ' [04]视频剪切操作参数 system function exec() run shell success ,return code ：' . $sysStatus);

        $resultVideoPathFile = self::RESULT_FILE_PATH . $editid . '.mp4';
        $resultImagePathFile = self::RESULT_FILE_PATH . $editid . '.jpg';
        if ($version == 1) {
            $resultVideoPathFile = self::RESULT_FILE_PATH . $liveId . '-' . $editid . '.mp4';
            $resultImagePathFile = self::RESULT_FILE_PATH . $liveId . '-' . $editid . '.jpg';
        }

        #   根据返回的状态码提示消息
        $filename = $editid;
        $shellResult = $sysStatus;
        if (file_exists($resultVideoPathFile) && file_exists($resultImagePathFile)) {
            $filesize = filesize($resultVideoPathFile);
            $duration = self::getVideoDuration($resultVideoPathFile);
            $pid = $origin_video_id;
            $editresultcode = $shellResult;
            if ($shellResult == '0') $editresultcode = '1';
            $editmsg = $this->editResultMsg($shellResult);
            $this->saveCutDataToDb($liveId, $filename, $filesize, $duration, $pid, $editid, $editresultcode, $editmsg, $new_video_name, $version, $streamName);
            return json(['status' => 1, 'msg' => $editmsg]);
        }
        return json(['code' => 200, 'msg' => $origVideoInfo]);
    }

    /**
     * 剪切视频记录保存
     * @param $activityid
     * @param $filename
     * @param $filesize
     * @param $duration
     * @param $pid
     * @param $editid
     * @param $editresultcode
     * @param $editmsg
     * @param $editdesc
     * @param $version
     * @param $activityid2
     * @return bool
     */
    private function saveCutDataToDb($activityid, $filename, $filesize, $duration, $pid, $editid, $editresultcode, $editmsg, $editdesc, $version, $streamName)
    {
        Log::info('[' . getCurrentDate() . ']:' . "[04]剪切视频记录保存参数 activityid = " . $activityid . " filename = " . $filename . " filesize = " . $filesize . " duration = " . $duration . " pid = " . $pid .
            " editid = " . $editid . " editresultcode = " . $editresultcode . " editmsg = " . $editmsg . " editdesc = " . $editdesc);
        // 暂时把流名称存储在 deviceId 字段中去
        $data = [
            'liveId' => $activityid,
            'streamName' => $streamName,
            'type' => 3,
            'state' => 0,
            'name' => $editdesc,
            'fileName' => $editid,
            'fileTime' => getCurrentDate(),
            'duration' => $duration,
            'fileSize' => $filesize,
            'createTime' => getCurrentDate(),
            'pid' => $pid,
            'eidittype' => 1, // 1 剪切 2 合并
            'editid' => $editid,
            'videoparts' => '',
            'editresult' => $editresultcode,
            'editmsg' => $editmsg,
            'version' => 0
        ];
        /**
         * 标记为新平台版本视频剪切，
         * [1] 文件名需要拼接成和直播录像的格式一致：fileName = 4001494913517-1501638768
         * [2] 新版本剪切的版本字段设置为：version = 1 ，经过测试，如果不设置为1 则会出现子视频编辑会出现问题的
         */
        if ($version == 1) {
            $data["fileName"] = $streamName . '-' . $editid;
            $data["version"] = 1;
        }
        try {
            $insertId = Db::table('resty_stream_video_edit')->insertGetId($data);
            Log::info('[' . self::formatDate(time()) . ']:' . "[05]视频剪切记录保存结果 save success insertId = " . $insertId);
        } catch (\Exception $e) {
            Log::error('[' . self::formatDate(time()) . ']:' . '[05]视频剪切记录保存结果 save  fail' . $e->getMessage());
            return false;
        }
        return true;
    }

    /**
     *  Ajax 异步 (随后的ajax请求)
     * @param Request $request
     * @return \think\response\Json
     */
    public function videoCutResult(Request $request)
    {
        if (!$request->isPost()) return json(['status' => 403, 'msg' => "非POST请求"]);
        $editid = $request->post('new_video_id');
        $video_cut_source = $request->post('new_video_name');
        $videoInfo = $this->videoInfoByEditId($editid);
        if ($videoInfo == null) {
            $resultData = ['status' => -1];
        } elseif ($videoInfo["editresult"] != 1) {
            $resultData = ['status' => $videoInfo["editresult"], 'msg' => $videoInfo["editmsg"]];
        } else {
            $videoid = $videoInfo["id"];
            $liveId = $videoInfo["liveId"];
            $pid = $videoInfo["pid"];
            $desc = $videoInfo["name"];
            $fileName = $videoInfo["fileName"];
            if ($videoInfo["version"] == 1) {
                $playpath = $videoInfo["fileName"] . '.mp4';
                $image = $videoInfo["fileName"] . '.jpg';
            } else {
                $playpath = $videoInfo["fileName"] . '.mp4';
                $image = $videoInfo["fileName"] . '.jpg';
            }
            $resultData = [
                'status' => 1,
                'playpath' => $playpath,
                'id' => $videoid,
                'liveId' => $liveId,
                'fileName' => $fileName,
                'pid' => $pid,
                'desc' => $desc,
                'image' => $image
            ];
        }
        return json($resultData);
    }

    /**
     * 根据 $editid 查询该视频的信息
     * @param $editid
     * @return array|false|\PDOStatement|string|\think\Model
     */
    private function videoInfoByEditId($editid)
    {
        $videoInfo = Db::table('resty_stream_video_edit')->where('editid', $editid)->find();
        return $videoInfo;
    }

    /**
     * 通过FFmpeg 获取视频信息
     */
    public function getVideoInfoByFFmpeg()
    {
        $MP4Path = '/home/www/web/go-study-line/public/uploads/videos/201710002/video/59ef43871b3ee.mp4';
        $videoInfo = self::ffprobe()->format($MP4Path);
        $streamInfo = self::ffprobe()->streams($MP4Path);
        halt($videoInfo);
        halt($streamInfo);
    }

    /**
     * 通过FFmpeg 获取视频信息
     */
    public function cutVideoByFFmpeg()
    {
        $MP4Path = '/home/www/web/go-study-line/public/uploads/videos/Tinywan123.mp4';
        $video = self::ffmpeg()->open($MP4Path);
        //开始截取
        $video->filters()->clip(TimeCode::fromSeconds(3), TimeCode::fromSeconds(10));
        $video->save(new X264(), 'tinywan-30-40.mp4');
        var_dump($video);
    }

    /**
     * OSS 上传测试
     * @param $ossClient
     * @param $bucket
     */
    public function uploadDir()
    {
        $bucket = 'tinywan-create';
        Oss::createBucket($bucket);
        halt(Oss::createBucket($bucket));
        $bucket = config('aliyun_oss.BUCKET');
        $localDirectory = "/home/www/web/go-study-line/public/uploads/videos/201710002/video/";
        $ossbObject = 'data/201710002/video';
        $prefix = "data/201710002/video";
        printf(__FUNCTION__ . ": completeMultipartUpload OK\n");
    }
}