<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  DateTime: 2017/9/13 12:53
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/

namespace app\common\command;

use Curl\Curl;
use redis\BaseRedis;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class ThinkConsoleServer extends Command
{
    protected $sleep = 3;

    protected function configure()
    {
        $this->setName('ThinkConsoleServer')->setDescription('Based on ThinkPHP5 command-line daemon service');
    }

    protected function execute(Input $input, Output $output)
    {
        while (true) {
            $output->writeln(json_encode($this->sendMsgByRedis()));
            sleep($this->sleep);
        }
    }

    /**
     * redis instance
     */
    public static function redis()
    {
        return BaseRedis::message();
    }

    /**
     * Redis数据库操作
     * status 0:执行成功 1:正在执行 2:执行失败
     * @return string
     */
    protected function sendMsgByRedis()
    {
        $listRes = self::redis()->lRange("TASK_QUEUE", 0, 3);
        if (empty($listRes)) {
            return getCurrentDate() . " : Redis Queue is Null";
        }
        foreach ($listRes as $key => $value) {
            $msgData = self::redis()->hGetAll($value);
            if ($msgData['status'] == 1) {
                //判断消息类型
                switch ($msgData['task_type']) {
                    case 1:
                        if ($msgData['mobile_type'] == 1) {
                            $sendRes = send_dayu_sms($msgData['user_mobile'], self::getSmsType($msgData['mobile_type']), ['code' => $msgData['msg']]);
                        } elseif ($msgData['mobile_type'] == 2) {
                            $sendRes = send_dayu_sms($msgData['user_mobile'], self::getSmsType($msgData['mobile_type']), ['code' => $msgData['msg'], 'number' => $msgData['live_id']]);
                        } else {
                            $sendRes = send_dayu_sms($msgData['user_mobile'], self::getSmsType($msgData['mobile_type']), ['code' => $msgData['msg']]);
                        }
                        // 短信发送成功更新记录
                        if (isset($sendRes->result) && ($sendRes->result->err_code == 0) && ($sendRes->result->success == true)) {
                            self::redis()->hSet($value, 'status', 0);
                            self::redis()->lRem('TASK_QUEUE', $value, 2);
                            self::redis()->hSet($value, 'task_msg', 'success');
                        } else {
                            self::redis()->hSet($value, 'status', 2);
                            self::redis()->hSet($value, 'task_msg', json_encode($sendRes));
                        }
                        break;
                    case 2:
                        $result = send_email_qq($msgData['user_email'], self::getEmailType($msgData['email_type']), self::getEmailTemplate($msgData['email_scene'], $msgData['email_type'], $msgData['user_email']));
                        if ($result['error'] == 0) {
                            self::redis()->hSet($value, 'status', 0);
                            self::redis()->lRem('TASK_QUEUE', $value, 2);
                            self::redis()->hSet($value, 'task_msg', 'success');
                        } else {
                            self::redis()->hSet($value, 'status', 2);
                            self::redis()->hSet($value, 'task_msg', json_encode($result['message']));
                        }
                        break;
                    case 3:
                        $result3 = self::callbackTaskHandle($msgData['event_type'], $msgData['callback_url'], $msgData['stream_id'], $msgData['stream_name'], $msgData['create_time']);
                        if ($result3) {
                            self::redis()->hSet($value, 'status', 0);
                            self::redis()->lRem('TASK_QUEUE', $value, 2);
                            self::redis()->hSet($value, 'task_msg', 'success');
                        } else {
                            self::redis()->hSet($value, 'status', 2);
                            self::redis()->hSet($value, 'task_msg', json_encode($result3['message']));
                        }
                        break;
                    // 删除过期的队列
                    default:
                        echo '1';
                }
            }
            return 'Redis Queue Success ID: ' . $value;
        }
    }

    protected static function curl()
    {
        return new Curl();
    }

    /**
     * 发布一个回调地址
     * @param $event_type
     * @param $callback_url
     * @param $stream_id
     * @param $stream_name
     * @param $time
     * @return string
     * @static
     */
    public static function callbackTaskHandle($event_type, $callback_url, $stream_id, $stream_name, $time)
    {
        $curl = self::curl();
        $callbackRes = $curl->get($callback_url, [
            'event_type' => $event_type,
            'stream_id' => $stream_id,
            'stream_name' => $stream_name,
            'time' => $time,
        ]);
        if ($callbackRes->code == 200) {
            return true;
        }
        return json_encode($curl->errorMessage);
        /**
         * 这里如何保证100次推送成功
         * 在这里要读取Redis的值吗？
         * 1 是加入的队列状态，这里一直不返回就可以了
         */
//        $count = 0;
//        $res = '';
//        while (true) {
//            $count++;
//            $curl = self::curl();
//            $callbackRes = $curl->get($callback_url, [
//                'event_type' => $event_type,
//                'stream_id' => $stream_id,
//                'stream_name' => $stream_name,
//                'on_publish_time' => date('Y-m-d H:i:s'),
//            ]);
//            if ($curl->error) {
//                $res = json_encode($curl->errorCode . $curl->errorMessage);
//                break;
//            }
//            //最大通知次数
//            if ($count > 10) break;
//            //如果客户端返回数据为 200 则表示接收到数据了
//            if ($callbackRes->code == 200) {
//                $res = "callback is success";
//                break;
//            }
//            sleep(1);
//            continue;
//        }
//        return $res;
    }


    /**
     * MySQL数据库操作
     * 根据消息类型发送消息
     * $task_type 可选类值：
     * 1：短信通知
     * 2：邮件通知
     * 3：订单通知
     * $msg['task_msg']
     * @return string
     */
    protected function sendMsgByMySQL()
    {
        $res = Db::table('resty_task_list')->where('status', 0)->select();
        if (!empty($res)) {
            foreach ($res as $msg) {
                switch ($msg['task_type']) {
                    case 1:
                        if ($msg['mobile_type'] == 1) {
                            $sendRes = send_dayu_sms($msg['user_mobile'], self::getSmsType($msg['mobile_type']), ['code' => $msg['msg']]);
                        } elseif ($msg['mobile_type'] == 2) {
                            $sendRes = send_dayu_sms($msg['user_mobile'], self::getSmsType($msg['mobile_type']), ['code' => $msg['msg'], 'number' => $msg['live_id']]);
                        } else {
                            $sendRes = send_dayu_sms($msg['user_mobile'], self::getSmsType($msg['mobile_type']), ['code' => $msg['msg']]);
                        }
                        // 短信发送成功更新记录
                        if (isset($sendRes->result) && ($sendRes->result->err_code == 0) && ($sendRes->result->success == true)) {
                            Db::table('resty_task_list')->where('user_mobile', $msg['user_mobile'])->delete();
                        }
                        break;
                    case 2:
                        $result = send_email_qq($msg['user_email'], self::getEmailType($msg['email_type']), self::getEmailTemplate($msg['email_scene'], $msg['email_type'], $msg['user_email']));
                        if ($result['error'] == 0) {
                            Db::table('resty_task_list')->where('user_email', $msg['user_email'])->delete();
                        }
                        break;
                    case 3:
                        echo '3';
                        break;
                    // 删除过期的队列
                    default:
                        echo '1';
                }
            }
        }
        return json_encode($res);
    }

    /**
     * 获取短信标识符
     * @param $mobile_type
     * @return mixed
     */
    protected static function getSmsType($mobile_type)
    {
        $msg = [
            '1' => 'register',
            '2' => 'live',
            '3' => 'identity',
        ];
        return $msg[$mobile_type];
    }

    /**
     * 获取邮件标识符
     * @param $email_type
     * @return mixed
     */
    protected static function getEmailType($email_type)
    {
        $msg = [
            '1' => '新用户注册',
            '2' => '激活',
            '3' => '找回密码',
            '4' => '修改密码',
            '5' => '订阅 ',
        ];
        return $msg[$email_type];
    }

    /**
     * 获取邮件发送模板
     * 问题：发现这里修改后，如果php think send_mail 已经启动则修改内容不会生效
     * @param $email_scene
     * @param $email_type
     * @param $user_email
     * @return string
     * @static
     */
    protected static function getEmailTemplate($email_scene, $email_type, $user_email)
    {
        $emailSendDomain = config('email.EMAIL_SEND_DOMAIN');
        $emailUrlExpire = config('email.EMAIL_SEND_EXPIRE_TIME');
        $checkStr = base64_encode($user_email);
        $auth_key = get_auth_key($user_email);
        if ($email_scene == 1) { // frontend
            switch ($email_type) {
                case 1:
                    $link = "http://{$emailSendDomain}/frontend/login/emailRegisterUrlValid?checkstr=$checkStr&auth_key={$auth_key}";
                    $str = <<<html
                您好！<p></p>
                感谢您在Tinywan世界注册帐户！<p></p>
                帐户需要激活才能使用，赶紧激活成为Tinywan家园的正式一员吧:)<p></p>
                地址有效时间：$emailUrlExpire (分钟)<p></p>
                点击下面的链接立即激活帐户(或将网址复制到浏览器中打开):<p></p>
                $link
html;
                    break;
                case 2:
                    $link = "http://{$emailSendDomain}/frontend/login/checkEmailUrlValid?checkstr=$checkStr&auth_key={$auth_key}";
                    $str = "请点击下面的链接重置您的密码：<p></p>" . $link;
                    break;
                case 3:
                    echo '1';
                    break;
                default:
                    echo '0';
            }
        } elseif ($email_scene == 2) { // backend
            switch ($email_type) {
                case 1:
                    $link = "http://{$emailSendDomain}/backend/login/emailRegisterUrlValid?checkstr=$checkStr&auth_key={$auth_key}";
                    $str = <<<html
                您好！<p></p>
                感谢您在Tinywan世界注册帐户！<p></p>
                帐户需要激活才能使用，赶紧激活成为Tinywan家园的正式一员吧:)<p></p>
                地址有效时间：$emailUrlExpire (分钟)<p></p>
                点击下面的链接立即激活帐户(或将网址复制到浏览器中打开):<p></p>
                $link
html;
                    break;
                case 2:
                    $link = "http://{$emailSendDomain}/backend/login/checkEmailUrlValid?checkstr=$checkStr&auth_key={$auth_key}";
                    $str = "请点击下面的链接重置您的密码：<p></p>" . $link;
                    break;
                // 通知
                case 3:
                    $rand = rand(00000, 99999);
                    $str = <<<html
                    "管理员发送给你的信息,有效验证码：<p></p>" $rand
html;
                    break;
                default:
                    echo '0';
            }
        } else { // other

        }

        return $str;
    }

    /**
     * Test sendSms
     * @return string
     */
    protected function testSendSms()
    {
        $res = Db::table('resty_task_list')->where('status', 0)->select();
        if (!empty($res)) {
            foreach ($res as $v) {
                $sendRes = send_dayu_sms($v['user_mobile'], "register", ['code' => rand(100000, 999999)]);
                // 短信发送成功直接删除该任务记录
                if (isset($sendRes->result->success) && ($sendRes->result->success == true)) {
                    Db::table('resty_task_list')->where('user_mobile', $v['user_mobile'])->update(['status' => 1, 'mobile_status' => 1]);
                }
            }
        }
        return json_encode($res);
    }

    /**
     * Test 发送邮件队列
     * @return string
     */
    protected function testSendMail()
    {
        $res = Db::table('resty_task_list')->where('status', 0)->select();
        if (!empty($res)) {
            $emailSendDomain = config('email.EMAIL_SEND_DOMAIN');
            foreach ($res as $data) {
                $checkstr = base64_encode($data['user_email']);
                $auth_key = get_auth_key($data['user_email']);
                $link = "http://{$emailSendDomain}/frontend/member/emailRegisterUrlValid?checkstr=$checkstr&auth_key={$auth_key}";
                $str = <<<html
                您好！<p></p>
                感谢您在Tinywan世界注册帐户！<p></p>
                帐户需要激活才能使用，赶紧激活成为Tinywan家园的正式一员吧:)<p></p>
                点击下面的链接立即激活帐户(或将网址复制到浏览器中打开):<p></p>
                $link
html;
                $result = send_email_qq($data['user_email'], '新用户注册', $str);
                if ($result['error'] == 0) {
                    Db::table('resty_task_list')->where('user_email', $data['user_email'])->update(['status' => 1, 'email_status' => 1]);
                }
                sleep(2);
            }
        }
        return json_encode($res);
    }

}