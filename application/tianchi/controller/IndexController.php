<?php

/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/1/16 9:41
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\tianchi\controller;


use app\common\controller\BaseController;
use app\common\model\CarCustomer;
use app\common\model\OpenUser;
use app\common\model\StreamName;
use app\common\model\StreamVideo;
use EasyWeChat\Factory;
use live\LiveStream;
use redis\BaseRedis;
use think\Db;
use think\Image;
use think\Log;

class IndexController extends BaseController
{
    /**
     * 根据OpenId 获取推流信息
     * @param $serviceProvider
     * @param $authKeyStatus
     * @return mixed
     * @static
     */
    protected static function apiCreateAddress($serviceProvider = 2, $authKeyStatus = 0)
    {
        $appId = 'rawdsb9ldp855h7r';
        $domainName = 'lives.tinywan.com';
        $appName = 'live';
        $appSecret = 'pvxyij6wdalr694u7dq3zqlvhlf55ytldoa49ij2';
        if ($serviceProvider == 1) {
            $domainName = 'live.tinywan.com';
        }
        $str = "AppId" . $appId . "AppName" . $appName . "AuthKeyStatus" . $authKeyStatus . "DomainName" . $domainName . "ServiceProvider" . $serviceProvider . $appSecret;
        $sign = strtoupper(sha1($str));
        $url = "https://www.tinywan.com/api/stream/createPushAddress?AppId=" . $appId . "&AppName=" . $appName . "&AuthKeyStatus=" . $authKeyStatus . "&DomainName=" . $domainName . "&ServiceProvider=" . $serviceProvider . "&Sign=" . $sign;
        $ch = curl_init() or die (curl_error());
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 360);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    /**
     * 使用PHP对图片进行base64解码输出
     * @param $image_file
     * @return string
     * @static
     */
    public static function base64EncodeImage($image_file)
    {
        $image_info = getimagesize($image_file);
        $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
        $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
        return $base64_image;
    }

    /**
     * 根据车牌图片获取车牌号
     * @param $base64_str
     * @return mixed
     * @static
     */
    public static function getNumberPlate($base64_str)
    {
        $host = "http://jisucpsb.market.alicloudapi.com";
        $path = "/licenseplaterecognition/recognize";
        $method = "POST";
        $appcode = "e607e7ed7d78441097c6eb6fddd309b1";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        //根据API的要求，定义相对应的Content-Type
        array_push($headers, "Content-Type" . ":" . "application/x-www-form-urlencoded; charset=UTF-8");
        $querys = "";
        $urlencode = urlencode($base64_str);
        $bodys = "pic=" . $urlencode;
        $url = $host . $path;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if (1 == strpos("$" . $host, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $bodys);
        $res = json_decode(curl_exec($curl), true);
        curl_close($curl);
        return $res;
    }

    /**
     * 授权回调页面
     */
    public function oauthCallback()
    {
        $app = Factory::officialAccount(config('easywechat'));
        $oauth = $app->oauth;
        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user()->toArray();
        $user = $user['original'];
        $condition['open_id'] = $user['openid'];
        $checkUserInfo = OpenUser::where($condition)->find();
        if ($checkUserInfo) {
            session('tianchi_wechat_user', $checkUserInfo->id);
        } else {
            // 添加用户信息到数据库
            $insertData['account'] = $user['nickname'];
            $insertData['open_id'] = $user['openid'];
            $insertData['password'] = md5('123456');
            $insertData['realname'] = $user['nickname'];
            $insertData['nickname'] = $user['nickname'];
            $insertData['avatar'] = $user['headimgurl'];
            $insertData['company'] = $user['country'];
            $insertData['address'] = $user['country'] . '-' . $user['province'] . '-' . $user['city'];
            $insertData['type'] = "微信";
            $insertData['app_id'] = get_rand_string();
            $insertData['app_secret'] = get_rand_string(40);

            $user = OpenUser::create($insertData);
            if ($user) {
                session('tianchi_wechat_user', $user->id);
            } else {
                return $this->redirect("/");
            }
        }
        $targetUrl = empty(session('target_url')) ? '/' : session('target_url');
        header('location:' . $targetUrl); // 跳转
    }

    /**
     * 首页 需要授权才能访问的页面
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function index()
    {
        $app = Factory::officialAccount(config('easywechat'));
        $oauth = $app->oauth;
        // 未登录
        if (empty(session('tianchi_wechat_user'))) {
            session('target_url', $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . DIRECTORY_SEPARATOR . $_SERVER['REQUEST_URI']);
            return $oauth->redirect();
        }
        // 已经登录过，获取微信登录信息
        $userId = session('tianchi_wechat_user');
        $this->assign('userId', $userId);
        return $this->fetch();
    }

    /**
     * 个人中心
     */
    public function personCenter()
    {
        $userId = session("tianchi_wechat_user");
        $openUser = OpenUser::where(['id' => $userId])->field('id,open_id,account,nickname,avatar,address,mobile,type')->find();
        $this->assign('userInfo', $openUser);
        return $this->fetch();
    }

    /**
     * 手机&微信OPenId绑定页面
     * @return mixed
     */
    public function mobileBind()
    {
        $id = input('param.id');
        if (request()->isAjax()) {
            $mobile = input('post.mobile');
            $userId = input('post.user_id');
            $code = input('post.code');

            // 验证码是否合适
            $serverCode = messageRedis()->get("MOBILE_CODE:" . $mobile);
            if ($serverCode != $code) {
                $res = [
                    "code" => 500,
                    "msg" => "验证码已过期，或者不匹配，请重新获取授权验证码"
                ];
                return json($res);
            }
            //要不要生成推流地址？？
            $openUser = OpenUser::get($userId);
            $openUser->mobile = $mobile;
            if ($openUser->save() != false) {
                $res = [
                    "code" => 200,
                    "mobile" => $mobile,
                    "msg" => "绑定成功"
                ];
            } else {
                $res = [
                    "code" => 500,
                    "msg" => "绑定失败"
                ];
            }
            return json($res);
        }
        $this->assign('user_id', $id);
        return $this->fetch();
    }

    /**
     * 异步发送手机验证码
     */
    public function sendMobileCode()
    {
        if (request()->isAjax()) {
            $mobile = input('post.mobile');
            $code = rand(100000, 999999);
            messageRedis()->set("MOBILE_CODE:" . $mobile, $code, 1000);
            $sendRes = send_dayu_sms($mobile, "register", ['code' => $code]);
            if (isset($sendRes->result->success) && ($sendRes->result->success == true)) {
                $res = [
                    "code" => 200,
                    "msg" => "验证码发送成功"
                ];
            } else {
                $res = [
                    "code" => 500,
                    "msg" => "验证码发送失败"
                ];
            }
            return json($res);
        }
    }

    /**
     * 直播页面
     * 不允许观看
     * 1、4S店客户基本信息没有
     * 2、手机号码没有绑定
     */
    public function live()
    {
        //如果手机号码为空，则不予许观看直播
        // 【1】获取用户id
        $userId = session("tianchi_wechat_user");
        $userInfo = OpenUser::where(['id' => $userId])->field('account,mobile,avatar,address')->find();
        if ($userInfo)
            // 【2】获取4S店客户信息表
            $customerInfo = CarCustomer::where(['c_tel' => $userInfo->mobile])->find();
        if (empty($customerInfo) || $customerInfo == null) {
            return "<h1>你还没有在4S店登记过自己的信息吧！登记后才可以观看哦</h1>";
        }
        //【3】如何客户4S店客户的基本信息没有登记，则该客户不可以观看直播哦
        $streamInfo = StreamName::where(['id' => $customerInfo->stream_id])->field('stream_name,play_m3u8_address,push_flow_address')->find();
        if (empty($customerInfo) || $customerInfo == null) {
            return "<h1>你的车还没有开始维修呢！只支持观看自己的修车信息</h1>";
        }
        //【4】如果号码为空，则不允许观看直播的,一定要判断的
        $liveStatus = LiveStream::getRecordLiveStreamNameStatus($streamInfo->stream_name)['status'];
        // 历史回顾列表
        $liveVodList = Db::name('stream_video')->where(['streamName' => $streamInfo->stream_name])->field('id,streamName,fileName,fileSize,createTime,duration')->order("createTime desc")->select();
        $this->assign('userInfo', $userInfo);
        $this->assign('streamInfo', $streamInfo);
        $this->assign('customerInfo', $customerInfo);
        $this->assign('VodList', $liveVodList);
        $this->assign('liveStatus', $liveStatus);
        return $this->fetch();
    }

    public function live2()
    {
        //如果手机号码为空，则不予许观看直播
        // 【1】获取用户id
        $userId = session("tianchi_wechat_user");
        $userInfo = OpenUser::where(['id' => $userId])->field('account,mobile,avatar,address')->find();
        if ($userInfo)
            // 【2】获取4S店客户信息表
            $customerInfo = CarCustomer::where(['c_tel' => $userInfo->mobile])->find();
        if (empty($customerInfo) || $customerInfo == null) {
            return "<h1>你还没有在4S店登记过自己的信息吧！登记后才可以观看哦</h1>";
        }
        //【3】如何客户4S店客户的基本信息没有登记，则该客户不可以观看直播哦
        $streamInfo = StreamName::where(['id' => $customerInfo->stream_id])->field('stream_name,play_m3u8_address,push_flow_address')->find();
        if (empty($customerInfo) || $customerInfo == null) {
            return "<h1>你的车还没有开始维修呢！只支持观看自己的修车信息</h1>";
        }
        //【4】如果号码为空，则不允许观看直播的,一定要判断的
        $liveStatus = LiveStream::getRecordLiveStreamNameStatus($streamInfo->stream_name)['status'];
        // 历史回顾列表
        $liveVodList = Db::name('stream_video')->where(['streamName' => $streamInfo->stream_name])->field('id,streamName,fileName,fileSize,createTime,duration')->select();
        $this->assign('userInfo', $userInfo);
        $this->assign('streamInfo', $streamInfo);
        $this->assign('customerInfo', $customerInfo);
        $this->assign('VodList', $liveVodList);
        $this->assign('liveStatus', $liveStatus);
        return $this->fetch();
    }

    /**
     * 我的直播回顾列表
     */
    public function liveVodList()
    {
        $id = input('param.id');
        $videoInfo = StreamVideo::where(['id' => $id])->field('streamName,fileName,createTime,duration')->find();
        $this->assign('videoInfo', $videoInfo);
        return $this->fetch();
    }

    /**
     * 注销用户信息
     */
    public function logout()
    {
        session(null);
        return $this->redirect("/");
    }

    /**
     * kill FFmpeg 推流
     */
    public function killAllFFmpeg()
    {
        if (request()->isAjax()) {
            $id = input('param.id');
            if (empty($id)) return 'id is null';
            $customerInfo = CarCustomer::get($id);
            if ($customerInfo == null) {
                $res = [
                    'code' => 500,
                    'msg' => $id . " 该用户不存在"
                ];
                return json($res);
            }
            $shellCmd = "ps -aux | grep " . $customerInfo->stream_name . "\t | grep -v grep | cut -c 9-15 | xargs kill -s 9";
            system("{$shellCmd} > /dev/null 2>&1", $sysStatus);
            if ($sysStatus != 0) {
                $res = ['code' => 500, 'msg' => "Shell 命令执行失败"];
                return json($res);
            }
            $res = [
                'code' => 200,
                'msg' => "执行成功"
            ];
            return json($res);
        }
        return json(['403']);
    }

    /**
     * 维修开始
     * 【1】车辆入库获取车牌号信息，
     * 【2】用户信息绑定
     * 【3】推流地址的生成
     * @return mixed
     */
    public function carGettingStarted()
    {
        // 【1】获取车牌号 ,进行（车牌识别API）
        // 注意：这里的图片必须是在服务器本地，才可转换的哦，所以要使用OSS下载车辆牌照照片
        $str = ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . '5a61c7cfcaead.jpg';
        $thumbStr = ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'thumb_12312.jpg';
        // 图片缩放
        $image = Image::open($str);
        // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
        $res = $image->thumb(720, 720, Image::THUMB_CENTER)->save($thumbStr);
        halt($res);
        $base64Str = self::base64EncodeImage($str);
        $base64StrFotmat = explode(',', $base64Str)[1];
        $res = self::getNumberPlate($base64StrFotmat);
        if ($res['status'] != 0) return $res['msg'];
        $carCode = $res['result']['number'];
        // 【2】根据车牌号获取客户新
        //$carCode = "川A88888";
        $customer = CarCustomer::where(['num_plate' => $carCode])->find();

        // 【4】开始推流，这里要做转换的
        $streamInfo = StreamName::get($customer->stream_id);
        //$deviceStreamAddr = "rtsp://192.168.18.240:554/onvif/live/1";
        $inputStreamAddr = "rtmp://tinywan.amai8.com/live/4001516151987";
        $action_str = "nohup /usr/bin/ffmpeg -r 25 -i " . $inputStreamAddr . " -c copy  -f flv " . $streamInfo->push_flow_address;
        system("{$action_str} > /dev/null 2>&1 &", $sysStatus);
        if ($sysStatus != 0) {
            Log::error('[' . getCurrentDate() . ']:' . '系统执行函数system()没有成功,返回状态码：' . $sysStatus);
        }
        halt($customer);
    }

    public function uploadImage()
    {
        return $this->fetch();
    }

    /**
     * 上传图片识别车牌号同时开始推流
     * @return \think\response\Json
     */
    public function uploadImageFrom()
    {
        if (request()->isPost()) {
            $file = request()->file("chepai_file");
            if ($file) {
                $id = input('param.id');
                $savePath = ROOT_PATH . 'public' . DS . 'tmp';
                // 增加服务端图片验证
                $info = $file->validate(['size' => config('upload_config')['web']['size'] * 1024, 'ext' => config('upload_config')['web']['ext']])->rule("uniqid")->move($savePath);
                if ($info) {
                    // 成功上传后 获取车牌号
                    $originImg = ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $info->getSaveName();
                    $thumbImg = ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'thumb_' . $info->getSaveName();
                    // 图片缩放
                    $image = Image::open($originImg);
                    // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
                    $image->thumb(720, 720, Image::THUMB_CENTER)->save($thumbImg);
                    $base64Str = self::base64EncodeImage($thumbImg);
                    $base64StrFotmat = explode(',', $base64Str)[1];
                    $res = self::getNumberPlate($base64StrFotmat);
                    if ($res['status'] != 0) {
                        unlink($originImg);
                        unlink($thumbImg);
                        $res = ['code' => 500, 'msg' => $res['msg']];
                        return json($res);
                    }
                    $carCode = $res['result']['number'];
                    // 获取客户信息表
                    $customer = CarCustomer::where(['num_plate' => $carCode])->find();
                    if ($customer == null || empty($customer)) {
                        unlink($originImg);
                        unlink($thumbImg);
                        $res = ['code' => 500, 'msg' => "客户信息不存在，请填写完整信息后在进行车牌识别，谢谢！"];
                        return json($res);
                    }
                    // 【4】开始推流，这里要做转换的
                    $streamInfo = StreamName::get($customer->stream_id);
                    // 摄像头流地址,如果是本地流，判断是否有流
                    //$liveStatus = LiveStream::getRecordLiveStreamNameStatus($streamInfo->stream_name)['status'];
                    $redis = messageRedis();
                    //$inputStreamAddr = "rtmp://tinywan.amai8.com/live/4001489565547";
                    $inputStreamAddr = $redis->get('TC_INPUT_STREAM_ADDRESS');
                    $action_str = "nohup /usr/bin/ffmpeg -r 25 -i " . $inputStreamAddr . " -c copy  -f flv " . $streamInfo->push_flow_address;
                    Log::error('[1]------FFmpeg拉 阿麦直播流 到 Tinywan阿里云直播服务器 ------------------------' . $action_str);
                    system("{$action_str} > /dev/null 2>&1 &", $sysStatus);
                    if ($sysStatus != 0) {
                        $res = ['code' => 500, 'msg' => "摄像头拉流失败，系统执行函数system()没有成功,返回状态码"];
                        return json($res);
                    }
                    unlink($originImg);
                    send_dayu_sms($customer->c_tel, 'car_notice', ['name' => $customer->c_name, 'address' => 'gh_d0eb34e007dd']);
                    $res = [
                        'code' => 200,
                        'msg' => "OK",
                        'data' => [
                            'c_id' => $customer->c_no,
                            'c_name' => $customer->c_name,
                            'c_tel' => $customer->c_tel,
                            'address' => $customer->unit,
                            'carCode' => $carCode,
                            'play_m3u8_address' => $streamInfo->play_m3u8_address,
                            'image_path' => 'thumb_' . $info->getSaveName()
                        ]
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
        return json(['code' => 403, 'msg' => "forbbide"]);
    }

    /**
     * 车辆客户信息表
     */
    public function customerList()
    {
        $custInfo = Db::name('car_customer')->select();
        foreach ($custInfo as $vale) {
            $lists[] = [
                'c_no' => $vale['c_no'],
                'c_name' => $vale['c_name'],
                'c_tel' => $vale['c_tel'],
                'unit' => $vale['unit'],
                'num_plate' => $vale['num_plate'],
                'live_status' => LiveStream::getRecordLiveStreamNameStatus($vale['stream_name'])['status']
            ];
        }
        $this->assign('lists', $lists);
        return $this->fetch();
    }

    /**
     * 添加客户信息
     */
    public function addCustomer()
    {
        if (request()->isPost()) {
            $res = input('post.');
            // 【3】生成推流地址
            $stream = self::apiCreateAddress();
            if ($stream == false) {
                return $this->error("获取推流信息错误");
            }
            $user = CarCustomer::create([
                'c_name' => $res['c_name'],
                'c_tel' => $res['c_tel'],
                'num_plate' => $res['num_plate'],
                'unit' => $res['unit'],
                'stream_id' => $stream['data']['streamId'],
                'stream_name' => $stream['data']['streamName'],
            ]);
            if ($user) {
                return $this->success("成功");
            } else {
                return $this->error("失败");
            }
        }
        return $this->fetch();
    }

    /**
     * 切换推流地址
     */
    public function switchStreamAddress($addr)
    {
        $redis = messageRedis();
        $origin_addr = $redis->get('TC_INPUT_STREAM_ADDRESS');
        if ($redis->set('TC_INPUT_STREAM_ADDRESS', $addr)) {
            $res = [
                'code' => 200,
                'msg' => "set success",
                'data' => [
                    'old_addr' => $origin_addr,
                    'new_addr' => $addr,
                ]
            ];
        } else {
            $res = [
                'code' => 500,
                'msg' => 'set fail'
            ];
        }
        return json($res);
    }

    /**
     * 测试
     */
    public function sessionTest()
    {
        halt(session('wechat_user_tinywan'));
    }

    /**
     * @return 阿麦设备Redis备份
     */
    public function backRedisDeviceData()
    {
        $redis = BaseRedis::instance();
        $redis->connect('121.40.30.105');
        $keys = $redis->keys('*');
        $currentTime = time(); //当前时间-必须存储在队列中
        $redis->lPush('DEVICE_REDIS_LIST', $currentTime);
        foreach ($keys as $val) {
            if (is_numeric($val)) {
                $liveId = $redis->hGet($val, 'liveId');
                $redis->zAdd('DEVICE_REDIS_ZADD:' . $val, $currentTime, $liveId);
            }
        }
        echo 1111111111;
        die;
    }

    /**
     * 删除集合：ZREMRANGEBYSCORE key 当前时间-7天时间戳 当前时间-3天时间戳
     *  eg:ZREMRANGEBYSCORE DEVICE_REDIS_ZADD:13669361192 1523261400 1523261436
     */
    public function readRedisDeviceData($deviceId = 201)
    {
        $redis = BaseRedis::instance();
        $redis->connect('121.40.30.105');
        $list = $redis->lRange('DEVICE_REDIS_LIST', 0, -1);
        $resArr = [];
        $backKey = 'DEVICE_REDIS_ZADD:' . $deviceId;
        $currentTime = time();
        foreach ($list as $val) {
            if (!empty($redis->zRangeByScore($backKey, $val, $currentTime))) {
                $resArr[] = [
                    'time' => date('Y年m月d日 H:i:s', $val),
                    'liveId' => $redis->zRangeByScore($backKey, $val, $currentTime)[0]
                ];
            } else {
                $resArr = null;
            }
        }

        //保持列表长度
        $listKey = 'DEVICE_REDIS_LIST';
        $listLen = $redis->lLen($listKey);
        if ($listLen > 5) {
            // 删除列表成员
            $redis->lTrim($listKey, 0, 5);
            // 删除集合成员
            $minTime = $currentTime - 604800; // 7天前
            $maxTime = $currentTime - 259200; // 3天前
            $redis->zRemRangeByScore($backKey, $minTime, $maxTime);
        }
        return $this->fetch('', [
            'resArr' => $resArr,
            'deviceId' => $deviceId
        ]);
    }

    /**
     * shell执行文件移动操作
     */
    public function recordFileMove()
    {
        if (request()->isAjax()) {
            $deviceId = input('param.deviceId');
            $targetLiveId = input('param.targetLiveId');
            $shortVideoName = input('param.shortVideoName');

            $host = "121.40.133.183";
            $username = "www";
            $password = "wwwOracle11f";
            $connection = ssh2_connect($host, 22);

            if (!$connection) {
                $res = [
                    'code' => 200,
                    'msg' => 'connection to ' . $host . ':3600 failed'
                ];
                return json($res);
            }

            // 通过password方式登录远程服务器
            if (!ssh2_auth_password($connection, $username, $password)) {
                $res = [
                    'code' => 200,
                    'msg' => "Authentication Failed..."
                ];
                return json($res);
            }

            $formatShortVideoName = $shortVideoName . '.flv';
            $cmdStr = "cd /data/recorded_flvs && /home/www/bin/pubFlv.sh {$deviceId} {$formatShortVideoName} {$targetLiveId}";
            Log::error(getCurrentDate() . '---shell执行文件移动操作 ' . $cmdStr);
            $stream = ssh2_exec($connection, $cmdStr);
            // 为资源流设置阻塞或者阻塞模式 如果 mode 为0，资源流将会被转换为非阻塞模式；如果是1，资源流将会被转换为阻塞模式。
            $res = stream_set_blocking($stream, true); // 这里必须设置为阻塞模式
            Log::error('shell 脚本执行结果为 ' . $stream);
            $cmdRes = stream_get_contents($stream); // 这个内容最好是shell脚本返回 exit $value
            if ($res) {
                $res = [
                    'code' => 200,
                    'msg' => "恭喜你执行成功",
                    'cmdRes' => $cmdRes
                ];
            } else {
                $res = [
                    'code' => 500,
                    'msg' => "远程命令执行失败"
                ];
            }
            return json($res);
        }
        return json(['403']);
    }

    /**
     * ssh 远程链接操作
     * @return int|\think\response\Json
     */
    public function connectShellTest()
    {
        $targetLiveId = "L04343";
        $deviceId = '201';
        $shortVideoName = '201-1523324506';
        $fotmatShortVideoName = $shortVideoName . '.flv';

        $host = "www.tinywan.com";
        $username = "www";
        $password = "www_klwdws1988";
        // 连接服务器
        $connection = ssh2_connect($host, 22);
        if (!$connection) {
            return 'connection to ' . $host . ':3600 failed';
        }

        // 通过password方式登录远程服务器
        if (!ssh2_auth_password($connection, $username, $password)) {
            return 'Authentication Failed...';
        }

        //执行远程服务器上的命令并取返回值
        // /home/www/bin/pubFlv.sh 201 201-1520318940.flv L04343
        $cmdStr = " cat /proc/meminfo | grep 'MemFree:' | awk '{print $2}'";
        //$cmdStr = "cd /data/recorded_flvs && /home/www/bin/pubFlv.sh {$deviceId} {$fotmatShortVideoName} {$targetLiveId}";
        echo $cmdStr . "\r\n";
        $stream = ssh2_exec($connection, $cmdStr); //执行结果以流的形式返回
        if ($stream === FALSE) {
            return '命令执行错误';
        }
        $dioStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDIO); // 获得标准输入输出流
        $errStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR); // 获得错误输流
        stream_set_blocking($dioStream, true);
        stream_set_blocking($errStream, true);
        $resultErr = stream_get_contents($errStream);
        $resultDio = stream_get_contents($dioStream); //获取流的内容，即命令的返回内容
        fclose($stream);
        if ($resultErr == "") {
            $res = [
                'code' => 200,
                'msg' => '执行成功',
                'errorCode' => $resultDio
            ];
        } else {
            $res = [
                'code' => 500,
                'msg' => '执行失败',
                'errorCode' => 60001
            ];
        }
        return json($res);
    }


    /**
     * @return mixed
     */
    public function test()
    {
        // 注意：这里的图片必须是在服务器本地，才可转换的哦，所以要使用OSS下载车辆牌照照片
        $currentTime = time();
        $param = 60 * 60 * 24 * 3;
        echo $param;
        $minTime = $currentTime - 604800; // 7天前
        $maxTime = $currentTime - 259200; // 3天前
        halt($currentTime - $param);
    }

}
