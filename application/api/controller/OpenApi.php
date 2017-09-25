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
use think\Db;

class OpenApi extends Base
{
    public function index(){
        return '11'.__FUNCTION__;
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
            'channelId' => $channelId,
            'name' => $baseName,
            'fileName' => $baseName,
            'fileTime' => strftime("%Y-%m-%d %X", $fileTime),
            'fileSize' => $fileSize,
            'duration' => $duration,
            'createTime' => date("Y-m-d H:i:s"),
        ];
        $res = Db::table('resty_stream_video')->insertGetId($videoData);
        if($res){
            // 加入消息队列
            $taskData['task_type'] = 1;
            $taskData['status'] = 0;
            $taskData['mobile_type'] = 1;
            $taskData['user_mobile'] = 13669361192;
            $taskData['msg'] = "909090";
            // 加入邮件队列
            $this->addTaskList($taskData);
            exit('200:success');
        }else{
            exit('500:error');
        }
    }

    public function testSendMail()
    {
        $res = Db::table('resty_task_list')->where('status', 0)->find();
            $emailSendDomain = config('email.EMAIL_SEND_DOMAIN');
            $result = send_email_qq('756684177@qq.com', '新用户注册', '11111111');
        return $result;
    }
}