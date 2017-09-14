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

namespace app\frontend\command;

use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\Db;

class Mail extends Command
{
    protected $sleep = 3;

    protected function configure()
    {
        $this->setName('send_mail')->setDescription('this is mail list');
    }

    protected function execute(Input $input, Output $output)
    {
        while (true) {
            $output->writeln(json_encode($this->sendAllByMsgType()));
            sleep($this->sleep);
        }
    }

    /**
     * 根据消息类型发送消息
     * $task_type 可选类值：
     * 1：短信通知
     * 2：邮件通知
     * 3：订单通知
     * $msg['task_msg']
     * @return string
     */
    protected function sendAllByMsgType()
    {
        $res = Db::table('resty_task_list')->where('status', 0)->select();
        if (!empty($res)) {
            foreach ($res as $msg) {
                switch ($msg['task_type']){
                    case 1:
                        $sendRes = send_dayu_sms($msg['user_mobile'], "register", ['code' => rand(100000, 999999)]);
                        // 短信发送成功直接删除该任务记录
                        if (isset($sendRes->result->success) && ($sendRes->result->success == true)) {
                            Db::table('resty_task_list')->where('user_mobile', $msg['user_mobile'])->update(['status'=>1,'mobile_status'=>1]);
                        }
                        break;
                    case 2:
                        $emailSendDomain = config('email.EMAIL_SEND_DOMAIN');
                        $checkstr = base64_encode($msg['user_email']);
                        $auth_key = get_auth_key($msg['user_email']);
                        $link = "http://{$emailSendDomain}/frontend/member/emailRegisterUrlValid?checkstr=$checkstr&auth_key={$auth_key}";
                        $str = <<<html
                您好！<p></p>
                感谢您在Tinywan世界注册帐户！<p></p>
                帐户需要激活才能使用，赶紧激活成为Tinywan家园的正式一员吧:)<p></p>
                点击下面的链接立即激活帐户(或将网址复制到浏览器中打开):<p></p>
                $link
html;
                        $result = send_email_qq($msg['user_email'], '新用户注册', $str);
                        if ($result['error'] == 0) {
                            Db::table('resty_task_list')->where('user_email', $msg['user_email'])->update(['status'=>1,'email_status'=>1]);
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

    protected function sendSms()
    {
        $res = Db::table('resty_task_list')->where('status', 0)->select();
        if (!empty($res)) {
            foreach ($res as $v) {
                $sendRes = send_dayu_sms($v['user_mobile'], "register", ['code' => rand(100000, 999999)]);
                // 短信发送成功直接删除该任务记录
                if (isset($sendRes->result->success) && ($sendRes->result->success == true)) {
                    Db::table('resty_task_list')->where('user_mobile', $v['user_mobile'])->update(['status'=>1,'mobile_status'=>1]);
                }
            }
        }
        return json_encode($res);
    }

    /**
     * 发送邮件队列
     * @return string
     */
    protected function sendMail()
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
                    Db::table('resty_task_list')->where('user_email', $data['user_email'])->update(['status'=>1,'email_status'=>1]);
                }
                sleep(2);
            }
        }
        return json_encode($res);
    }


    protected function sendMailQQ()
    {
        $address = '756684177@qq.com';
        $subject = '弍萬QQ邮箱发送';
        $content = '恭喜你成功加入LSGO实验室，开启你的学习之旅吧！';
        $res = send_email_qq($address, $subject, $content);
        return json_encode($res);
    }

}