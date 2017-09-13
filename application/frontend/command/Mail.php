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
    protected $sleep = 1;

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
     * php+mysql 模拟队列发送邮件队列
     * @return false|int
     */
    protected function sendMail()
    {
        $res = Db::table('resty_task_list')->where('status', 0)->select();
        foreach ($res as $v) {
            $result = send_email($v['user_email'], 'Linux 物联网智能数据 帐户激活邮件--', '我是贰萬先生 队列发送数据哦');
            if ($result['error'] == 0) {
                Db::table('resty_task_list')->where('user_email', $v['user_email'])->setField('status', 1);
            }
            sleep(2);
        }
        return json_encode($res);
    }

}