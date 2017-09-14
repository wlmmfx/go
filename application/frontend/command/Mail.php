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
            $output->writeln(json_encode($this->sendMail()));
            sleep($this->sleep);
        }
    }

    /**
     * 发送短信队列
     * @return string
     */
    protected function sendSms()
    {
        $res = Db::table('resty_task_list')->where('status', 1)->select();
        if (!empty($res)) {
            foreach ($res as $v) {
                $sendRes = send_dayu_sms($v['user_mobile'], "register", ['code' => rand(100000, 999999)]);
                // 短信发送成功直接删除该任务记录
                if (isset($sendRes->result->success) && ($sendRes->result->success == true)) {
                    Db::table('resty_task_list')->where('user_mobile', $v['user_mobile'])->delete();
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
                    Db::table('resty_task_list')->where('user_email', $data['user_email'])->setField('status', 1);
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