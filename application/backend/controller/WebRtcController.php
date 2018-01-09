<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/7 14:42
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\backend\controller;


use app\common\controller\BaseBackendController;
use app\common\controller\UserController;
use redis\BaseRedis;
use think\Db;
use think\Log;

class WebRtcController extends BaseBackendController
{
    public function index()
    {
        return $this->fetch();
    }

    public function recordRtcRun()
    {
        foreach (array('video', 'audio') as $type) {
            if (isset($_FILES["${type}-blob"])) {
                echo 'uploads/';
                $fileName = $_POST["${type}-filename"];
                $uploadDirectory = ROOT_PATH . 'public' . DS . 'uploads/' . $fileName;

                if (!move_uploaded_file($_FILES["${type}-blob"]["tmp_name"], $uploadDirectory)) {
                    echo(" problem moving uploaded file");
                }
                echo($fileName);
            }
        }
    }

    /**
     * 消息队列
     */
    public function redisQueue()
    {
        // 哈希
        $taskKey = "TASK_LIST:".time();
        $res = messageRedis()->hMset($taskKey, [
            'task_id' => 1,
            'status' => 1,
            'task_type' => 2,
            'email_type' => 1,
            'mobile_type' => 2,
//            'user_email' => '1170427228@qq.com',
            'user_email' => '756684177@qq.com',
//            'user_mobile' => 13634171830,
//            'user_mobile' => 13669361192,
            'user_mobile' => 17757177259,
            'create_time' => getCurrentDate(),
            'email_status' => 0,
            'email_scene' => 2,
            'mobile_status' => 0,
            'msg' => "测试消息",
            'live_id' => "19901227",
        ]);
        // 加入队列
        if(true === $res){
            // 返回队列的长度
            $resList = messageRedis()->rPush("TASK_QUEUE",$taskKey);
            if($resList == true) return "加入队列设置成功";
        }
        return "加入哈希失败";
    }

    public function getRedisQueue()
    {
        $listRes = messageRedis()->lRange("TASK_QUEUE",0,3);
        if(empty($listRes)) return "Redis Queue is Null";
        foreach ($listRes as $key=>$value){
            $msgData = self::redis()->hGetAll($value);
            if($msgData['status'] == 1) {
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
                            self::redis()->lRem($value, 2);
                        }
                        break;
                    case 2:
                        $result = send_email_qq($msgData['user_email'], self::getEmailType($msgData['email_type']), self::getEmailTemplate($msgData['email_scene'], $msgData['email_type'], $msgData['user_email']));
                        if ($result['error'] == 0) {
                            self::redis()->hSet($value, 'status', 0);
                            self::redis()->lRem($value, 2);
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
    }

    public function delQueue()
    {
        $resList = messageRedis()->keys("*");
        halt($resList);
        if($resList == true) return "加入队列设置成功";
        Log::error("-------------------Socket调试");
        return "加入哈希失败";
    }

    /**
     * 开启Trace调试
     */
    public function trace()
    {
//        Db::listen(function($sql,$time,$explain){
//            // 记录SQL
//            echo $sql. ' ['.$time.'s]';
//            // 查看性能分析结果
//            dump($explain);
//        });
    }


}


