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
use think\Db;
use think\Image;

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
            return "你还没有在4S店登记过自己的信息吧！登记后才可以观看哦！";
        }
        //【3】如何客户4S店客户的基本信息没有登记，则该客户不可以观看直播哦
        $streamInfo = StreamName::where(['id' => $customerInfo->stream_id])->field('stream_name,play_m3u8_address,push_flow_address')->find();
        if (empty($customerInfo) || $customerInfo == null) {
            return "你的车还没有开始维修呢！只支持观看自己的修车信息";
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
        $id = input('param.id');
        $customerInfo = CarCustomer::get($id);
        if ($customerInfo == null) {
            $res = [
                'code' => 500,
                'msg' => $id." 该用户不存在"
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
            'msg' => "执行OK"
        ];
        return json($res);
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
        //$inputStreamAddr = "rtmp://tinywan.amai8.com/live/4001516151987";
        //$action_str = "nohup /usr/bin/ffmpeg -r 25 -i " . $inputStreamAddr . "\t -c copy  -f flv " . $streamInfo->push_flow_address;
        //system("{$action_str} > /dev/null 2>&1 &", $sysStatus);
        //if ($sysStatus != 0) {
        //    Log::error('[' . getCurrentDate() . ']:' . '系统执行函数system()没有成功,返回状态码：' . $sysStatus);
        //}
        halt($customer);
    }

    public function uploadImage()
    {
        return $this->fetch();
    }

    public function uploadImageFrom()
    {
        if (request()->isPost()) {
            $file = request()->file("chepai_file");
            if ($file) {
                $id = input('param.id');
                $savePath = ROOT_PATH . 'public' . DS . 'tmp';
                $info = $file->rule("uniqid")->move($savePath);
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
                        $res = ['code' => 500, 'msg' => $res['msg']];
                        return json($res);
                    }
                    $carCode = $res['result']['number'];
                    // 获取客户信息表
                    $customer = CarCustomer::where(['num_plate' => $carCode])->find();
                    if ($customer == null || empty($customer)) {
                        $res = ['code' => 500, 'msg' => "客户信息不存在，请填写完整信息后在进行车牌识别，谢谢！"];
                        return json($res);
                    }
                    // 【4】开始推流，这里要做转换的
                    $streamInfo = StreamName::get($customer->stream_id);
                    // 摄像头流地址
                    $inputStreamAddr = "rtmp://tinywan.amai8.com/live/4001516151987";
                    $action_str = "nohup /usr/bin/ffmpeg -r 25 -i " . $inputStreamAddr . "\t -c copy  -f flv " . $streamInfo->push_flow_address;
                    system("{$action_str} > /dev/null 2>&1 &", $sysStatus);
                    if ($sysStatus != 0) {
                        $res = ['code' => 500, 'msg' => "摄像头拉流失败，系统执行函数system()没有成功,返回状态码"];
                        return json($res);
                    }
                    //$this->rmdirs($originImg);
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
    }

    /**
     * 测试
     */
    public function sessionTest()
    {
        halt(session('wechat_user_tinywan'));
    }

    /**
     * @return mixed
     */
    public function test()
    {
        // 注意：这里的图片必须是在服务器本地，才可转换的哦，所以要使用OSS下载车辆牌照照片
        $str = ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'chepaihao002.jpg';
        $base64Str = self::base64EncodeImage($str);
        $base64StrFotmat = explode(',', $base64Str)[1];
        $res = self::getNumberPlate($base64StrFotmat);
        if ($res['status'] != 0) return $res['msg'];
        $busCode = $res['result']['number'];
        echo $busCode;
    }

}
