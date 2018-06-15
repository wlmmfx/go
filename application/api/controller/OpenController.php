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

use app\common\controller\BaseApiController;
use app\common\model\Live;
use app\common\model\StreamVideo;
use app\common\model\StreamVideoEdit;
use redis\BaseRedis;
use think\Db;
use think\Log;
use Yansongda\Pay\Pay;

class OpenController extends BaseApiController
{
    protected $config = [
      'app_id' => '2016082000295641',
      'notify_url' => 'http://yansongda.cn/notify.php',
      'return_url' => 'http://yansongda.cn/return.php',
      'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuWJKrQ6SWvS6niI+4vEVZiYfjkCfLQfoFI2nCp9ZLDS42QtiL4Ccyx8scgc3nhVwmVRte8f57TFvGhvJD0upT4O5O/lRxmTjechXAorirVdAODpOu0mFfQV9y/T9o9hHnU+VmO5spoVb3umqpq6D/Pt8p25Yk852/w01VTIczrXC4QlrbOEe3sr1E9auoC7rgYjjCO6lZUIDjX/oBmNXZxhRDrYx4Yf5X7y8FRBFvygIE2FgxV4Yw+SL3QAa2m5MLcbusJpxOml9YVQfP8iSurx41PvvXUMo49JG3BDVernaCYXQCoUJv9fJwbnfZd7J5YByC+5KM4sblJTq7bXZWQIDAQAB',
        // 加密方式： **RSA2**
      'private_key' => 'MIIEpAIBAAKCAQEAs6+F2leOgOrvj9jTeDhb5q46GewOjqLBlGSs/bVL4Z3fMr3p+Q1Tux/6uogeVi/eHd84xvQdfpZ87A1SfoWnEGH5z15yorccxSOwWUI+q8gz51IWqjgZxhWKe31BxNZ+prnQpyeMBtE25fXp5nQZ/pftgePyUUvUZRcAUisswntobDQKbwx28VCXw5XB2A+lvYEvxmMv/QexYjwKK4M54j435TuC3UctZbnuynSPpOmCu45ZhEYXd4YMsGMdZE5/077ZU1aU7wx/gk07PiHImEOCDkzqsFo0Buc/knGcdOiUDvm2hn2y1XvwjyFOThsqCsQYi4JmwZdRa8kvOf57nwIDAQABAoIBAQCw5QCqln4VTrTvcW+msB1ReX57nJgsNfDLbV2dG8mLYQemBa9833DqDK6iynTLNq69y88ylose33o2TVtEccGp8Dqluv6yUAED14G6LexS43KtrXPgugAtsXE253ZDGUNwUggnN1i0MW2RcMqHdQ9ORDWvJUCeZj/AEafgPN8AyiLrZeL07jJz/uaRfAuNqkImCVIarKUX3HBCjl9TpuoMjcMhz/MsOmQ0agtCatO1eoH1sqv5Odvxb1i59c8Hvq/mGEXyRuoiDo05SE6IyXYXr84/Nf2xvVNHNQA6kTckj8shSi+HGM4mO1Y4Pbb7XcnxNkT0Inn6oJMSiy56P+CpAoGBAO1O+5FE1ZuVGuLb48cY+0lHCD+nhSBd66B5FrxgPYCkFOQWR7pWyfNDBlmO3SSooQ8TQXA25blrkDxzOAEGX57EPiipXr/hy5e+WNoukpy09rsO1TMsvC+v0FXLvZ+TIAkqfnYBgaT56ku7yZ8aFGMwdCPL7WJYAwUIcZX8wZ3dAoGBAMHWplAqhe4bfkGOEEpfs6VvEQxCqYMYVyR65K0rI1LiDZn6Ij8fdVtwMjGKFSZZTspmsqnbbuCE/VTyDzF4NpAxdm3cBtZACv1Lpu2Om+aTzhK2PI6WTDVTKAJBYegXaahBCqVbSxieR62IWtmOMjggTtAKWZ1P5LQcRwdkaB2rAoGAWnAPT318Kp7YcDx8whOzMGnxqtCc24jvk2iSUZgb2Dqv+3zCOTF6JUsV0Guxu5bISoZ8GdfSFKf5gBAo97sGFeuUBMsHYPkcLehM1FmLZk1Q+ljcx3P1A/ds3kWXLolTXCrlpvNMBSN5NwOKAyhdPK/qkvnUrfX8sJ5XK2H4J8ECgYAGIZ0HIiE0Y+g9eJnpUFelXvsCEUW9YNK4065SD/BBGedmPHRC3OLgbo8X5A9BNEf6vP7fwpIiRfKhcjqqzOuk6fueA/yvYD04v+Da2MzzoS8+hkcqF3T3pta4I4tORRdRfCUzD80zTSZlRc/h286Y2eTETd+By1onnFFe2X01mwKBgQDaxo4PBcLL2OyVT5DoXiIdTCJ8KNZL9+kV1aiBuOWxnRgkDjPngslzNa1bK+klGgJNYDbQqohKNn1HeFX3mYNfCUpuSnD2Yag53Dd/1DLO+NxzwvTu4D6DCUnMMMBVaF42ig31Bs0jI3JQZVqeeFzSET8fkoFopJf3G6UXlrIEAQ==',
      'log' => [ // optional
        'file' => './logs/alipay.log',
        'level' => 'debug'
      ],
      'mode' => 'dev', // optional,设置此参数，将进入沙箱模式
    ];

    /**
     * 【正式-已上线】
     * 直播录像信息回调添加
     * type:
     * 1：直播录制
     * 2：编辑
     * 3：上传
     */
    public function createStreamVideo()
    {
        $version = input("get.version", 1);
        $streamName = input("get.streamName");
        $baseName = input("get.baseName");
        $duration = input("get.duration");
        $fileSize = input("get.fileSize");
        $fileTime = input("get.fileTime");

        //获取liveId
        $live = Live::where('stream_name', $streamName)->field('id,stream_id,stream_name')->find();
        // 2018-1-19 非活动的录像视频不能够成功，如果liveId 为空，则liveId 等于流名称
        $liveId = $streamName;
        if ($live) {
            $liveId = $live->id;
        }
        $res = StreamVideo::create([
            'streamName' => $streamName,
            'liveId' => $liveId,
            'name' => $baseName,
            'fileName' => $baseName,
            'fileTime' => strftime("%Y-%m-%d %X", $fileTime),
            'fileSize' => $fileSize,
            'duration' => $duration,
            'type' => 1,
            'state' => 1,
            'version' => $version,
            'createTime' => getCurrentDate(),
        ]);
        if ($res) {
            // 加入消息队列
            addEmailTaskQueue(6, 1, '756684177@qq.com', 3, "data/" . $streamName . "/video/" . $baseName . ".mp4");
            exit('200:success');
        } else {
            exit('500:error');
        }
    }

    /**
     * 【正式-已上线】
     * 通过任务ID去编辑视频配置信息
     * Shell 脚本专用接口
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
            $findRes = StreamVideoEdit::where('task_id', $sign)->find();
            if (empty($findRes) || ($findRes == false)) {
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
     * 创建 License
     * @param $service_uuid
     * @param $expire_time
     * @param string $private_key
     * @param int $rand
     * @param int $uid
     * @return string
     */
    public function createLicense($service_uuid, $expire_time, $private_key = 'amailive', $rand = 0, $uid = 0)
    {
        $timestatmp = strtotime(date('Y-m-d H:i:s', strtotime($expire_time . " minute ")));
        $hash_value = md5($service_uuid . "-" . $timestatmp . "-" . $rand . "-" . $uid . "-" . $private_key);
        $auth_key = $timestatmp . '-' . $rand . '-' . $uid . '-' . $hash_value;
        return $auth_key;
    }


    /**
     * 通过Uuid穿点Licnese
     * @return string|\think\response\Json
     */
    public function getLicenseByUuid()
    {
        $service_uuid = input('get.service_uuid');
        if (empty($service_uuid) || $service_uuid == '') return json('请求的参数不完整，请检查参数是否合适', 400);
        $expire_time = 720000;
        return $this->createLicense($service_uuid, $expire_time);
    }

    /**
     * 支付宝回调地址
     */
    public function AliPayRedirectUri()
    {
        $redis = BaseRedis::Instance();
        $redis->connect('127.0.0.1');
        $redis->hMGet('alipay', [
            'get' => $_GET,
            'post' => $_POST,
        ]);
        halt($redis->hGetAll('alipay'));
    }

    /**
     * 播放验证
     */
    public function playValidate()
    {
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
            'var_jsonp_handler' => 'callback',
            'default_jsonp_handler' => 'jsonpReturn',
            'json_encode_param' => JSON_PRETTY_PRINT,
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
        array_push($headers, "Content-Type" . ":" . "application/x-www-form-urlencoded; charset=UTF-8");
        $querys = "";
        $bodys = "userId=$userId&msgType=$msgType&msgContent=$msgContent&expireAfterSeconds=$expireAfterSeconds&terminalId=$terminalId&terminalType=$terminalType&justSendToOneClient=$justSendToOneClient";
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
        var_dump(curl_exec($curl));
    }


    public function testSendMail2()
    {
        $email_smtp_host = config('email.EMAIL_SMTP_HOST');
        $email_username = config('email.EMAIL_USERNAME');
        $email_password = config('email.EMAIL_PASSWORD');
        $email_from_name = config('email.EMAIL_FROM_NAME');
        $email_host = config('email.EMAIL_SEND_DOMAIN');
        $address = '756684177@qq.com';
        $subject = '弍萬邮件标题';
        $content = '测试邮件发送';
        try {
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
            $phpmailer->addAddress($address, 'lsgo在线通知');
            // 设置邮件标题
            $phpmailer->Subject = $subject;
            // 设置邮件正文,这里最好修改为这个，不是boby
            $phpmailer->MsgHTML($content);
            $res = $phpmailer->Send();
            echo '1111111111111';
            var_dump($res);
        } catch (\phpmailerException $e) {
            return "邮件发送失败：" . $e->errorMessage();
        }
    }

    /**
     * 长链接转为短链接
     */
    public function getShortUrl()
    {
        // AppKey
        $source = config('oauth')['weibo']['app_key'];
        $url_long = input('get.url');

        // 单个链接转换
        $data = get_sina_short_url($source, $url_long);

        // 多个链接转换
//        $url_long = array(
//            'http://www.shuchengxian.com/article/670.html',
//            'http://www.shuchengxian.com/article/654.html',
//            'http://www.shuchengxian.com/index.html'
//        );
        //$data = get_sina_short_url($source, $url_long);
        return json($data);
    }

    /**
     * 接口测试
     */
    public function test()
    {
        halt(addEmailTaskQueue(6, 1, '756684177@qq.com', 3, "data/201710008/video/5a3cad60b2340.mp4"));
    }


    //=====================================支付宝支付回调
    public function returnUrl()
    {
        $data = Pay::alipay($this->config)->verify(); // 是的，验签就这么简单！
        halt($data);
        // 订单号：$data->out_trade_no
        // 支付宝交易号：$data->trade_no
        // 订单总金额：$data->total_amount
    }

    public function notifyUrl()
    {
        $alipay = Pay::alipay($this->config);

        try{
            $data = $alipay->verify(); // 是的，验签就这么简单！

            // 请自行对 trade_status 进行判断及其它逻辑进行判断，在支付宝的业务通知中，只有交易通知状态为 TRADE_SUCCESS 或 TRADE_FINISHED 时，支付宝才会认定为买家付款成功。
            // 1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号；
            // 2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额）；
            // 3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）；
            // 4、验证app_id是否为该商户本身。
            // 5、其它业务逻辑情况

            Log::error('Alipay notify', $data->all());
        } catch (\Exception $e) {
            // $e->getMessage();
        }

        return $alipay->success()->send();// laravel 框架中请直接 `return $alipay->success()`
    }


}