<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/9/25 20:53
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm
 * '-------------------------------------------------------------------*/

namespace app\api\controller;

use app\common\controller\Base;
use redis\BaseRedis;
use think\Db;

class Open extends Base
{
    public function index(){
        return '11'.__FUNCTION__;
    }

    public function createLicense($service_uuid, $expire_time, $private_key = 'amailive', $rand = 0, $uid = 0)
    {
        $timestatmp = strtotime(date('Y-m-d H:i:s', strtotime($expire_time . " minute ")));
        $hash_value = md5($service_uuid . "-" . $timestatmp . "-" . $rand . "-" . $uid . "-" . $private_key);
        $auth_key = $timestatmp . '-' . $rand . '-' . $uid . '-' . $hash_value;
        return $auth_key;
    }


    public function getLicenseByUuid()
    {
        $service_uuid = input('get.service_uuid');
        if(empty($service_uuid) || $service_uuid == '') return json('请求的参数不完整，请检查参数是否合适',400);
        $expire_time = 720000;
        return $this->createLicense($service_uuid,$expire_time);
    }

    /**
     * 支付宝回调地址
     */
    public function AliPayRedirectUri(){
        $redis = BaseRedis::Instance();
        $redis->connect('127.0.0.1');
        $redis->hMGet('alipay',[
            'get'=>$_GET,
            'post'=>$_POST,
        ]);
        halt($redis->hGetAll('alipay'));
    }

    /**
     * 录像信息添加
     */
    public function createStreamVideo(){
        $version = input("get.version");
        $streamName = input("get.streamName");
        $channelId = input("get.channelId");
        $baseName = input("get.baseName");
        $duration = input("get.duration");
        $fileSize = input("get.fileSize");
        $fileTime = input("get.fileTime");

        $videoData = [
            'streamName' => $streamName,
            'liveId' => $channelId,
            'name' => $baseName,
            'fileName' => $baseName,
            'fileTime' => strftime("%Y-%m-%d %X", $fileTime),
            'fileSize' => $fileSize,
            'duration' => $duration,
            'version' => $version,
            'createTime' => date("Y-m-d H:i:s"),
        ];
        $res = Db::table('resty_stream_video')->insertGetId($videoData);
        if($res){
//            // 加入消息队列
//            $taskData['task_type'] = 1;
//            $taskData['status'] = 0;
//            $taskData['mobile_type'] = 2;
//            $taskData['user_mobile'] = 18170603953;
//            $taskData['msg'] = "909090";
//            $taskData['live_id'] = $streamName;
//            // 加入邮件队列
//            $this->addTaskList($taskData);
            exit('200:success');
        }else{
            exit('500:error');
        }
    }

    /**
     * 自动安装配置文件
     * @return mixed
     */
    public function videoEditConf()
    {
        $sign = input('param.sign');
        if (empty($sign)) {
            $resJson = [
                'code' => 500,
                'msg' => 'fail',
                'data' => null
            ];
        } else {
            $findRes = Db::table('resty_stream_video_edit')->where('task_id', $sign)->find();
            if(empty($findRes) || ($findRes == false)){
                $resJson = ['code' => 500, 'msg' => 'success', 'data' => null];
                return json($resJson);
            }
            $resJson = [
                'code' => 200,
                'msg' => 'success',
                'data' => json_decode($findRes['edit_config'])
            ];
        }
        return json($resJson);
    }

    /**
     * 播放验证
     */
    public function playValidate(){
        $resJson = [
            'code' => 200,
            'msg' => 'success',
            'data' => json_encode($_POST)
        ];
        return json($resJson);
    }

    /**
     * 测试文件
     * @return $this
     */
    public function testSendMail()
    {
        $res = Db::table('resty_task_list')->where('status', 0)->find();
        $emailSendDomain = config('email.EMAIL_SEND_DOMAIN');
        $result = send_email_qq('756684177@qq.com', '新用户注册', '11111111');

        return json($result)->code(200)->header(['Cache-control' => 'no-cache,must-revalidate']);
        return json($result)->code(200)->header(['Cache-control' => 'no-cache,must-revalidate']);
    }

    public function setHeaderInfo()
    {
        $header = [
            'Cache-control' => 'no-cache,must-revalidate',
            'Last-Modified' => gmdate('D, d M Y H:i:s') . ' GMT',
        ];
        $result = send_email_qq('756684177@qq.com', '新用户注册', '11111111');
        return json($result)->code(200)->header('Cache-control', 'no-cache,must-revalidate');
        return json($result)->code(200)->header(['Cache-control' => 'no-cache,must-revalidate']);
        // 多个头设置响应的头信息
        return json($result)->code(200)->header($header);
    }

    public function setJsonInfo()
    {
        $result = send_email_qq('756684177@qq.com', '新用户注册', '11111111');
        $options = [
            'var_jsonp_handler'     => 'callback',
            'default_jsonp_handler' => 'jsonpReturn',
            'json_encode_param'     => JSON_PRETTY_PRINT,
        ];
        return json($result)->options($options);
    }

    /**
     * APush物联网websocket长连接comet消息推送api
     * @return $this
     */
    public function sendPersistentMsg()
    {
        //指定接收消息的客户端
        $userId = 123;
        //用户自定义的msgType
        $msgType = 'msgType1';
        //消息内容
        $msgContent = 'msgContent 001';
        //消息最多缓存在多少秒
        $expireAfterSeconds = 60;
        //设置消息只发给terminalId为指定值的客户端，客户端的terminalId在客户端连接
        $terminalId = 'terminalId1';
        //设置消息只发给terminalType为指定值的客户端，客户端的terminalType在客户端
        $terminalType = 'pcWeb';
        //如果有多个满足条件的客户端，只把消息发送给最近登录的一个
        $justSendToOneClient = 'true';
        $host = "http://apush2.market.alicloudapi.com";
        $path = "/sendPersistentMsg";
        $method = "POST";
        $appcode = "e607e7ed7d78441097c6eb6fddd309b1";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        //根据API的要求，定义相对应的Content-Type
        array_push($headers, "Content-Type".":"."application/x-www-form-urlencoded; charset=UTF-8");
        $querys = "";
        $bodys = "userId=$userId&msgType=$msgType&msgContent=$msgContent&expireAfterSeconds=$expireAfterSeconds&terminalId=$terminalId&terminalType=$terminalType&justSendToOneClient=$justSendToOneClient";
        $url = $host . $path;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if (1 == strpos("$".$host, "https://"))
        {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $bodys);
        var_dump(curl_exec($curl));
    }


    public function testSendMail2()
    {
        $email_smtp_host = config('email.EMAIL_SMTP_HOST');
        $email_username = config('email.EMAIL_USERNAME');
        $email_password = config('email.EMAIL_PASSWORD');
        $email_from_name = config('email.EMAIL_FROM_NAME');
        $email_host = config('email.EMAIL_SEND_DOMAIN');
        $address= '756684177@qq.com';
        $subject = '弍萬邮件标题';
        $content = '测试邮件发送';
        try{
            //实例化PHPMailer核心类
            $phpmailer = new \PHPMailer();

            //是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
            $phpmailer->SMTPDebug = 1;

            //使用smtp鉴权方式发送邮件
            $phpmailer->IsSMTP();

            //smtp需要鉴权 这个必须是true
            $phpmailer->SMTPAuth = true;

            // 设置SMTP服务器。
            $phpmailer->Host = 'smtp.qq.com';

            //设置使用ssl加密方式登录鉴权
            $phpmailer->SMTPSecure = 'ssl';

            //设置ssl连接smtp服务器的远程服务器端口号，以前的默认是25，但是现在新的好像已经不可用了 可选465或587
            $phpmailer->Port = 465;

            //设置发件人的主机域 可有可无 默认为localhost 内容任意，建议使用你的域名
//            $phpmailer->Hostname = $email_host;

            // 	设置邮件的字符编码'
            $phpmailer->CharSet = 'UTF-8';

            // 设置发件人名字
            $phpmailer->FromName = '弍萬罚金人';

            //设置发件人姓名（昵称） 任意内容，显示在收件人邮件的发件人邮箱地址前的发件人姓名
            $phpmailer->Username = '1722318623@qq.com';

            //smtp登录的密码 使用生成的授权码（就刚才叫你保存的最新的授权码）
            $phpmailer->Password = 'nsluzzfumknicgfa';

            //设置发件人邮箱地址 这里填入上述提到的“发件人邮箱”
            $phpmailer->From = $email_username;

            //邮件正文是否为html编码 注意此处是一个方法 不再是属性 true或false
            $phpmailer->IsHTML(true);

            //设置收件人邮箱地址 该方法有两个参数 第一个参数为收件人邮箱地址 第二参数为给该地址设置的昵称 不同的邮箱系统会自动进行处理变动 这里第二个参数的意义不大
            $phpmailer->addAddress($address,'lsgo在线通知');
            // 设置邮件标题
            $phpmailer->Subject = $subject;
            // 设置邮件正文,这里最好修改为这个，不是boby
            $phpmailer->MsgHTML($content);
            $res = $phpmailer->Send();
            echo '1111111111111';
            var_dump($res);
        }catch (\phpmailerException $e){
            return "邮件发送失败：".$e->errorMessage();
        }
    }
}