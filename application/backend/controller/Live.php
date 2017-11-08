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
use think\exception\HttpException;
use think\Log;
use think\Request;

class Live extends BaseBackend
{
    const AUTH_PRIVATEKEY = 'Tinywan123';
    const SHELL_SCRIPT_PATH = "/home/www/web/go-study-line/shell/ffmpeg/";
    const RESULT_FILE_PATH = "/home/www/ffmpeg-data/";
    protected $db;
    protected $vod_db;

    public function _initialize()
    {
        parent::_initialize();
        $this->db = new \app\common\model\Live();
        $this->vod_db = new \app\common\model\Vod();
    }

    /**
     * 当我们访问了一个不存在的操作方法，就会触发空操作检查
     * @param $method
     * @return string
     */
//    public function _empty($method)
//    {
//        return '当前操作名:'.$method;
//    }

    /**
     * 测试请求缓存
     * @return string
     */
    public function hello()
    {
        // 抛出404异常
        throw new HttpException(404, '页面异常');
        //return '当前请求时间:' . date('y-m-d H:i:s', request()->time());
        //return $this->fetch();
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
     * 视频管理界面
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
                $id = input('post.id', '201710008');
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

    /**
     * 点播管理
     */
    public function vodManage()
    {
        if (request()->isPost()) {
            $data = input('post.');
            // 验证表单数据
            $res = $this->vod_db->store($data);
            if ($res["valid"]) {
                $this->success($res["msg"], "backend/live/vodManage");
                exit;
            } else {
                $this->success($res["msg"]);
            }
        }
        $vods = Db::table("resty_vod")
            ->alias('v')
            ->join('resty_vod_tag vt', 'v.id = vt.vod_id')
            ->join('resty_category c', 'c.id = v.cid')
            ->field('v.id,v.create_time,v.name,v.hls_url,v.image_url,v.content,v.download_data,c.name as cName')
            ->order('v.create_time desc')
            ->paginate(6);
        $this->assign('categorys', db('category')->where('pid',116)->order('id desc')->select());
        $this->assign('vods', $vods);
        $this->assign('lives', db('live')->where('deleted',0)->select());
        $this->assign('tags', db('tag')->where(['deleted'=>0,'cid'=>110])->select());
        return $this->fetch();
    }

    /**
     * 点播编辑
     */
    public function vodEdit($id)
    {
        if (request()->isPost()) {
            $data = input('post.');
            $res = $this->vod_db->edit($data);
            if ($res["valid"]) {
                $this->success($res["msg"], "backend/live/vodManage");
                exit;
            } else {
                return json(['code' => 500, 'msg' => $res["msg"]]);
            }
        }
        $vod = Db::table("resty_vod")
            ->alias('v')
            ->join('resty_category c', 'c.id = v.cid')
            ->join('resty_vod_tag vt', 'v.id = vt.vod_id')
            ->join('resty_tag t', 't.id = vt.tag_id')
            ->where('v.id',$id)
            ->field('v.id,v.live_id as liveId,v.create_time,v.name,v.hls_url,v.image_url,v.content,v.download_data,c.name as cName,c.id as cId,t.name as tName,t.id as tId')
            ->order('v.create_time desc')
            ->find();
        $categorys = db('category')->where('pid',116)->order('id desc')->select();
        $this->assign('tags', db('tag')->where(['deleted'=>0,'cid'=>110])->select());
        $this->assign('categorys', $categorys);
        $this->assign('vod',$vod);
        $this->assign('lives', db('live')->where('deleted',0)->select());
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
            $copiedRecordCount = 'Diff:' . $differentCount . '/Total:' . $copyCount;
        }
        #  [4] 返回总条数和成功插入的数据的百分比和自增主键(id)的值
        return json(['code' => 200, 'msg' => $copiedRecordCount]);
    }

    /**
     * 素材剪切
     * @return mixed
     */
    public function videoCut()
    {
        $Videos = Db::table('resty_stream_video_edit')->where('type', 2)->order('createTime desc')->paginate(12);
        $editVideos = Db::table('resty_stream_video_edit')->where('type', 3)->order('createTime desc')->paginate(12);
        $this->assign('videos', $Videos);
        $this->assign('editVideos', $editVideos);
        return $this->fetch();
    }

    /**
     * 素材合并
     * @return mixed
     */
    public function videoConcat()
    {
        $Videos = Db::table('resty_stream_video_edit')->where('type', 2)->order('createTime desc')->paginate(12);
        $editVideos = Db::table('resty_stream_video_edit')->where('type', 3)->order('createTime desc')->paginate(12);
        $this->assign('videos', $Videos);
        $this->assign('editVideos', $editVideos);
        return $this->fetch();
    }

    /**
     * 素材编辑列表
     * @return mixed
     */
    public function videoEditList()
    {
        $editVideos = Db::table('resty_stream_video_edit')->where(['type' => 3, 'deleted' => 0])->order('createTime desc')->paginate(6);
        $this->assign('editVideos', $editVideos);
        return $this->fetch();
    }

    /**
     * 根据视频 videoid 获取部分视频信息
     * @param $id
     * @return mixed
     */
    private function videoInfoByVideoId($id)
    {
        $video = Db::table('resty_stream_video_edit')->where('id', $id)->find();
        $result['id'] = $video['id'];
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
            '-5' => '截取缩略图失败，请检查视频开始、结束、视频截图时间',
            '-4' => 'Rename file error, Disk is full',
            '-3' => 'FFmpeg cut/concat Video Fail',
            '-2' => 'Oss File Download Fail',
            '-1' => 'API Sign Error , Please task_id',
        ];

        $result = $msg[$editCode];
        if ($result == null) return "unknown code " . $editCode;
        return $result;
    }

    /**
     * 【原始视频v1.0】素材剪切编辑
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
        #   ffmpeg 脚本是否执行成功
        if ($sysStatus != 0) {
            Log::error('[' . getCurrentDate() . ']:' . ' [04]视频剪切操作参数 system function exec() run shell failed  ,return code : ' . $sysStatus);
            $pid = $origin_video_id;
            $editresultcode = $shellResult;
            $editmsg = $this->editResultMsg($shellResult);
            $this->saveCutDataToDb($liveId, $sourcefile, $filesize = '0', $duration = '0', $pid, $editid, $editresultcode, $editmsg, $new_video_name, $version, $streamName);
            return json(['status' => 500, 'msg' => $editmsg]);
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
            return json(['status' => 200, 'msg' => $editmsg]);
        }
        return json(['code' => 500, 'msg' => $origVideoInfo]);
    }


    /**
     * 删除编辑视频
     */
    public function editVideoDel()
    {
        if ($this->request->isAjax()) {
            $id = input('post.id');
            $res = Db::table('resty_stream_video_edit')->where('id', $id)->update(['deleted' => 1]);
            if ($res) {
                return json(['code' => 200, 'msg' => '删除成功']);
            }
            return json(['code' => 500, 'msg' => "删除失败"]);
        }
        return json(['code' => 401, 'msg' => "Not Forbidden"]);
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
     * @param $streamName
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
     * Shell脚本任务执行前插入数据库记录
     * @param $taskId
     * @param $editId
     * @param int $pid
     * @param $liveId
     * @param $new_video_name
     * @param $editConfig
     * @param $eidittype
     * @return bool|int|string
     */
    private function saveEditDataByTaskId($taskId, $editId, $pid = 0, $liveId, $new_video_name, $editConfig, $eidittype)
    {
        $msg = "视频剪切(cut)操作";
        $data = [
            'liveId' => $liveId,
            'streamName' => $liveId,
            'type' => 3,
            'state' => 0,
            'name' => $new_video_name,
            'fileName' => $editId,
            'fileTime' => getCurrentDate(),
            'duration' => 0,
            'fileSize' => 0,
            'createTime' => getCurrentDate(),
            'pid' => $pid,
            'eidittype' => $eidittype, // 1 剪切 2 合并
            'editid' => $editId,
            'editresult' => 2,
            'editmsg' => 'running',
            'task_id' => $taskId,
            'edit_config' => json_encode($editConfig)
        ];
        if ($eidittype == 2) $msg = "视频合并(concat)操作";

        try {
            $insertId = Db::table('resty_stream_video_edit')->insertGetId($data);
            Log::info('[' . self::formatDate(time()) . ']:' . "[06] {$msg} 记录保存记录成功 msg insertId = " . $insertId);
        } catch (\Exception $e) {
            Log::error('[' . self::formatDate(time()) . ']:' . "[06] {$msg} 记录保存记录失败 msg = " . $e->getMessage());
            return false;
        }
        return $insertId;
    }

    /**
     * 视频编辑成功或者失败更新数据库记录
     * @param $updateData
     * @return bool
     */
    private function updateEditDataById($updateData, $eiditType)
    {
        $msg = "视频剪切(cut)操作";
        if($eiditType == 2) $msg = "视频合并(concat)操作";
        if($eiditType == 3) $msg = "视频重新编辑操作";
        try {
            $insertId = Db::table('resty_stream_video_edit')->update($updateData);
            Log::info('[' . self::formatDate(time()) . ']:' . "[07] {$msg} 更新记录成功 msg insertId = " . $insertId);
        } catch (\Exception $e) {
            Log::error('[' . self::formatDate(time()) . ']:' . "[07] {$msg} 更新记录失败 msg = " . $e->getMessage());
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
     * -----------------------------------------------------------------剪切--------------------------------------------
     * 【新版视频v2.0】素材剪切编辑
     * @return mixed
     */
    public function videoCutOperateByTaskId()
    {
        if (!request()->isAjax()) return json(['status' => 403, 'msg' => "非Ajax请求"]);
        $starttime = request()->post("start_time");
        $endtime = request()->post("end_time");
        $cutImageTime = request()->post("cut_image_time");
        $new_video_id = request()->post("new_video_id");
        $new_video_name = request()->post("new_video_name");
        $origin_video_id = request()->post("origin_video_id");
        if (empty($starttime) || empty($endtime) || empty($new_video_name) || empty($origin_video_id)) {
            return json(['status' => 403, 'msg' => "请求的参数不完整，请检查参数是否合适"],403);
        }
        #   根据LiveId获取视频信息
        $taskId = self::getVideoEditTaskId($origin_video_id);
        $editid = $new_video_id;
        Log::info('[' . getCurrentDate() . ']:' . "[01] 视频剪切操作参数] $origin_video_id ： " . $origin_video_id);
        $origVideoInfo = $this->videoInfoByVideoId($origin_video_id);
        $liveId = $origVideoInfo['liveId'];
        Log::info('[' . getCurrentDate() . ']:' . "[02] 视频剪切操作参数 ： " . json_encode($origVideoInfo));
        $version = $origVideoInfo['version'];
        $fileName = $origVideoInfo['fileName'];
        $shellScript = self::SHELL_SCRIPT_PATH . "check_oss_cut_task_id.sh";
        $editConfig = [
            'live_id' => $liveId,
            'origin_video_name' => $fileName,
            'start_time' => $starttime,
            'end_time' => $endtime,
            'cut_image_time' => $cutImageTime,
            'new_video_id' => $new_video_id,
            'new_video_name' => $new_video_name,
            'auto_slice' => 0
        ];
        $pid = $origVideoInfo['id'];
        $insertId = $this->saveEditDataByTaskId($taskId, $editid, $pid, $liveId, $new_video_name, $editConfig, 1);
        $cmdStr = "{$shellScript} {$taskId}";
        // 根据版本号拼接视频文件名
        Log::info('[' . getCurrentDate() . ']:' . "[03] 视频剪切操作 Shell 脚本参数 ： " . $cmdStr);
        exec("{$cmdStr}", $results, $sysStatus);
        Log::info('[' . getCurrentDate() . ']:' . "[04-1] 执行系统函数返回状态码 sysStatus = " . $sysStatus);
        $shellResult = -1;
        Log::info('[' . getCurrentDate() . ']:' . '[04-2] 执行系统函数返回结果 results =  ' . $results[0]);
        #   如果命令执行错误，则exec 的第二个参数会返回shell 脚本的 echo 出的值，$results 返回结果为一个数组
        if (count($results) == 1) $shellResult = $results[0];
        #   ffmpeg 脚本是否执行成功
        if ($sysStatus != 0) {
            Log::error('[' . getCurrentDate() . ']:' . '[05] 视频剪切操作参数 system exec() failed , code = ' . $sysStatus);
            $editMsg = $this->editResultMsg($shellResult);
            $updateData = [
                'id' => $insertId,
                'state' => 0,
                'pid' => $origin_video_id,
                'editresult' => $shellResult,
                'editmsg' => $editMsg
            ];
            $this->updateEditDataById($updateData, 1);
            return json(['status' => 500, 'msg' => $editMsg]);
        }
        Log::info('[' . getCurrentDate() . ']:' . '[05] 视频剪切执行系统函数 Success , status = ' . $sysStatus);
        $resultVideoPathFile = self::RESULT_FILE_PATH . $editid . '.mp4';
        $resultImagePathFile = self::RESULT_FILE_PATH . $editid . '.jpg';
        #   根据返回的状态码提示消息
        $shellResult = $sysStatus;
        if (file_exists($resultVideoPathFile) && file_exists($resultImagePathFile)) {
            $editresultcode = $shellResult;
            if ($shellResult == '0') $editresultcode = '1';
            $editMsg = $this->editResultMsg($shellResult);
            $updateData2 = [
                'id' => $insertId,
                'state' => 1,
                'duration' => self::getVideoDuration($resultVideoPathFile),
                'fileSize' => filesize($resultVideoPathFile),
                'editresult' => $editresultcode,
                'editmsg' => $editMsg
            ];
            $this->updateEditDataById($updateData2, 1);
            Log::info('[' . getCurrentDate() . ']:' . '[06] 视频剪切操作成功完成 , msg =' . $editMsg);
            return json(['status' => 200, 'msg' => $editMsg]);
        }
        return json(['code' => 500, 'msg' => $origVideoInfo],500);
    }

    /**
     * -----------------------------------------------------------------合并--------------------------------------------
     * 通过任务ID合并视频编辑
     * @return mixed
     */
    public function videoConcatOperateByTaskId()
    {
        if (!request()->isAjax()) return json(['status' => 403, 'msg' => "非Ajax请求"]);
        $videoList = request()->post("video_list");
        $new_video_id = request()->post("new_video_id");
        $new_video_name = request()->post("new_video_name");
        Log::info(getCurrentDate() . '--------------------' . json_encode($videoList));
        $cmdStr = "";
        if ($videoList == "" || empty($new_video_id) || empty($new_video_name)) {
            return json(['status' => 403, 'msg' => "请求的参数不完整，请检查参数是否合适"]);
        }
        $videoArr = explode(",", $videoList);
        for ($k = 0; $k < count($videoArr); $k++) {
            $videoInfo = $this->videoInfoByVideoId($videoArr[$k]);
            $liveId = $videoInfo['liveId'];
            $cmdStr = $cmdStr . $liveId . ',' . $videoInfo['fileName'] . ',';
        }
        $cmdStr = substr($cmdStr, 0, strlen($cmdStr) - 1);
        # 自动切片
        $auto_slice = 0;
        # [1] 根据LiveId获取视频信息
        $taskId = self::getVideoEditTaskId($new_video_id);
        // [2] 插入数据再执行
        $pid = "-1";
        $editId = $new_video_id;
        $edit_config = [
            'live_id' => $liveId,
            'new_video_id' => $new_video_id,
            'auto_slice' => $auto_slice,
            'all_param' => $cmdStr
        ];
        $insertId = $this->saveEditDataByTaskId($taskId, $editId, $pid, $liveId, $new_video_name, $edit_config, 2);
        // [3] 执行系统函数，运行shell 脚本
        $shell_script = self::SHELL_SCRIPT_PATH . "check_oss_concat_mv_task_id.sh";
        $cmdStr = "{$shell_script} {$taskId}";
        Log::info('[' . getCurrentDate() . ']:' . "[03] 视频合并操作Shell 脚本参数 ： " . $cmdStr);
        // [3] 执行系统函数，运行shell 脚本，$results 为一个数组
        exec("{$cmdStr}", $results, $sysStatus);
        Log::info('[' . getCurrentDate() . ']:' . "[04-1] 执行系统函数返回状态码 sysStatus = " . $sysStatus);
        $shellResult = -1;
        Log::info('[' . getCurrentDate() . ']:' . '[04-2] 视频合并系统函数执行返回结果 results =  ' . $results[0]?$results[0]:'unknown');
        if (count($results) == 1) $shellResult = $results[0];
        #  [4] 系统函数执行失败
        if ($sysStatus != 0) {
            Log::error('[' . getCurrentDate() . ']:' . '[05] 视频合并操作参数 system  exec() failed , code : ' . $sysStatus);
            $updateData = [
                'id' => $insertId,
                'pid' => -1,
                'editresult' => $shellResult,
                'editmsg' => $this->editResultMsg($shellResult),
            ];
            $this->updateEditDataById($updateData, 2);
            return json(['status' => 500, 'msg' => "shell error"]);
        }
        Log::info('[' . getCurrentDate() . ']:' . '[05] 视频合并操作执行系统函数 Success , status = ' . $sysStatus);
        $resultVideoPathFile = self::RESULT_FILE_PATH . $editId . '.mp4';
        $resultImagePathFile = self::RESULT_FILE_PATH . $editId . '.jpg';
        #   [5] 根据返回的状态码提示消息
        if (file_exists($resultVideoPathFile) && file_exists($resultImagePathFile)) {
            if ($shellResult == '0') $editresultcode = '1';
            $editMsg = $this->editResultMsg($shellResult);
            $updateData = [
                'id' => $insertId,
                'duration' => self::getVideoDuration($resultVideoPathFile),
                'fileSize' => filesize($resultVideoPathFile),
                'editresult' => $editresultcode,
                'editmsg' => $editMsg,
            ];
            $this->updateEditDataById($updateData, 2);
            Log::info('[' . getCurrentDate() . ']:' . '[06] 视频合并操作成功完成 , msg =' . $editMsg);
            return json(['status' => 200, 'msg' => $editMsg]);
        }
        return json(['status' => 200, 'msg' => null]);
    }

    /**
     * -----------------------------------------------------------------重新编辑----------------------------------------
     * 视频编辑失败，重新编辑
     * @return mixed
     */
    public function videoReOperateByTaskId()
    {
        if (!request()->isAjax()) return json(['status' => 403, 'msg' => "非Ajax请求"]);
        $task_id = request()->post("task_id");
        if (empty($task_id)) {
            return json(['status' => 403, 'msg' => "请求的参数不完整，请检查参数是否合适"]);
        }
        $findRes = Db::table('resty_stream_video_edit')->where('task_id', $task_id)->find();
        if (empty($findRes) || ($findRes == false)) {
            return json(['code' => 403, 'msg' => '没有对应的任务 task_id ']);
        }
        $taskId = $task_id;
        $insertId = $findRes['id'];
        $editId = $findRes['editid'];
        $editType = $findRes['eidittype'];
        // [3] 执行系统函数，运行shell 脚本
        if ($editType == 1) {
            $shell_script = self::SHELL_SCRIPT_PATH . "check_oss_cut_task_id.sh";
        } elseif ($editType == 2) {
            $shell_script = self::SHELL_SCRIPT_PATH . "check_oss_concat_mv_task_id.sh";
        } else {
            return json(['code' => 403, 'msg' => '视频是剪切或者合并未知']);
        }
        $cmdStr = "{$shell_script} {$taskId}";
        Log::info('[' . getCurrentDate() . ']:' . "[03] 视频重新编辑操作Shell 脚本参数 ： " . $cmdStr);
        // [3] 执行系统函数，运行shell 脚本
        exec("{$cmdStr}", $results, $sysStatus);
        Log::info('[' . getCurrentDate() . ']:' . "[04-1] 执行系统函数返回状态码 status = " . $sysStatus);
        $shellResult = -1;
        Log::info('[' . getCurrentDate() . ']:' . '[04-2] 执行系统函数返回结果 results =  ' . $results[0]);
        if (count($results) == 1) $shellResult = $results[0];
        #  [4] 系统函数执行失败
        if ($sysStatus != 0) {
            Log::error('[' . getCurrentDate() . ']:' . '[05] 视频重新编辑执行系统函数 Failed , status = ' . $sysStatus);
            $updateData = [
                'id' => $insertId,
                'pid' => -1,
                'editresult' => $shellResult,
                'editmsg' => $this->editResultMsg($shellResult),
            ];
            $this->updateEditDataById($updateData);
            return json(['status' => 500, 'msg' => "shell error"]);
        }
        Log::info('[' . getCurrentDate() . ']:' . '[05] 视频重新编辑执行系统函数 Success , status = ' . $sysStatus);
        $resultVideoPathFile = self::RESULT_FILE_PATH . $editId . '.mp4';
        $resultImagePathFile = self::RESULT_FILE_PATH . $editId . '.jpg';
        #   [5] 根据返回的状态码提示消息
        if (file_exists($resultVideoPathFile) && file_exists($resultImagePathFile)) {
            if ($shellResult == '0') $editresultcode = '1';
            $editMsg = $this->editResultMsg($shellResult);
            $updateData = [
                'id' => $insertId,
                'duration' => self::getVideoDuration($resultVideoPathFile),
                'fileSize' => filesize($resultVideoPathFile),
                'editresult' => $editresultcode,
                'editmsg' => $editMsg,
            ];
            $this->updateEditDataById($updateData);
            Log::info('[' . getCurrentDate() . ']:' . '[06] 视频重新编辑操作成功完成 , msg =' . $editMsg);
            return json(['status' => 200, 'msg' => $editMsg]);
        }
        return json(['status' => 200, 'msg' => null]);
    }

    /**
     * 【全局设置】
     */
    public function settingsAll()
    {
        return $this->fetch();
    }


    /**
     * 【全局设置】回调设置
     */
    public function settingsCallBack()
    {
        $data = input('post.');
        $data['event_type'] = json_encode($data['event_type']);
        $insertId = Db::table('resty_stream_config')->insertGetId($data);
        halt($insertId);
    }

    /**
     * 视频上传完成 事件类型
     * 以后修改为switch结构语句
     */
    public function fileUploadComplete()
    {
        $res = [
            'EventTime' => '2017-03-20T07:49:17Z',
            'EventType' => 'FileUploadComplete',
            'VideoId' => '43q9fjasjdflask',
            'Size' => '1439213',
        ];

        $url = 'https://api.github.com/gists';
        $headers = ['Accept' => 'application/json'];
        $data = [];
        $options = ['auth' => ['tinywan', 'github_klwdws1988']];
        $request = \Requests::get($url, $headers, $options);
        // int(200)
        halt($request);

    }

    /**
     * 【全局设置】水印管理
     */
    public function waterMark()
    {

    }

    /**
     * 【安全管理】
     */
    public function safetyManage()
    {
        return $this->fetch();
    }

    /**
     * 【安全管理】URL鉴权
     */
    public function urlAuth()
    {

    }

    /**
     * 【安全管理】播放鉴权
     */
    public function playAuthentication()
    {

    }

    /**
     * -----------------------------------------媒体转码---------------------------------------------------------------
     * 【媒体转码】媒体转码管理
     */
    public function mediaFormatSwitch()
    {
        $editVideos = Db::table('resty_stream_video_edit')->where(['type' => 3, 'deleted' => 0])->order('createTime desc')->paginate(6);
        $this->assign('editVideos', $editVideos);
        return $this->fetch();
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

    public function getCityByIp(){
        $ip = '115.192.189.173';
        $res = ip_format($ip);
        echo $res;
    }

}