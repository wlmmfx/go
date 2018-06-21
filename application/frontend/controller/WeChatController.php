<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  DateTime: 2017/11/21 15:22
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/

namespace app\frontend\controller;


use app\common\controller\BaseFrontendController;
use EasyWeChat\Foundation\Application;
use think\Log;

class WeChatController extends BaseFrontendController
{
    public function index()
    {
        echo ROOT_PATH . 'public' . DS;
        halt(config('easywechat'));
        return '111';
    }

    /**
     * 来设置消息处理函数
     */
    public function setMessageHandler()
    {
        $server = self::easyWeChatApp()->server;
        $server->setMessageHandler(function ($message) {
            return "您好！欢迎关注我!";
        });
        $response = $server->serve();
        $response->send();
        return $response;
    }

    /**
     * profile
     */
    public function profile()
    {
        $oauth = self::easyWeChatApp()->oauth;
        // 未登录
        if (empty($_SESSION['wechat_user'])) {
            $_SESSION['target_url'] = 'https://www.tinywan.com/frontend/we_chat/profile';
            return $oauth->redirect();
        }
        // 已经登录过
        $user = $_SESSION['wechat_user'];
        halt($user);
    }

    /**
     * 回调地址
     */
    public function oauth_callback()
    {
        $oauth = self::easyWeChatApp()->oauth;
        // 获取 OAuth 授权结果用户信息
        $user = $oauth->user();
        $_SESSION['wechat_user'] = $user->toArray();
        $targetUrl = empty($_SESSION['target_url']) ? '/' : $_SESSION['target_url'];
        header('location:' . $targetUrl); // 跳转到 user/profile
    }

    /**
     * 获取用户信息
     */
    public function getUserInfo()
    {
        $user = self::easyWeChatApp()->oauth->user();
        halt($user);
    }

    // shell 脚本返回状态码
    protected static function shellReturnMsg($code)
    {
        $msg = [
          '0' => 'success',
          '-5' => '截取缩略图失败，请检查视频开始、结束、视频截图时间',
          '-4' => 'Rename file error, Disk is full',
          '-3' => 'FFmpeg cut/concat Video Fail',
          '-2' => 'Oss File Download Fail',
          '-1' => 'API Sign Error , Please task_id',
          126 => 'Permission denied',
          127 => 'dos2unix shell script',
        ];

        $result = $msg[$code];
        if ($result == null) return "unknown code " . $code;
        return $result;
    }

    /**
     * 脚本没有执行权限 Permission denied
     */
    const PERMISSION = 126;

    /**
     * 需要使用dos2unix命令将文件转换为unix格式
     * eg：dos2unix file
     */
    const DOS2UNIX = 127;

    /**
     * 脚本内部命令执行错误
     */
    const INTERNAL_ERROR = 247;

    const OUTPUT_MSG = [
      0 => 'success',
      1 => 'API Sign Error , Please task_id',
      2 => 'Oss File Download Fail',
      3 => 'FFmpeg cut/concat Video Fail',
      4 => 'Rename file error, Disk is full',
      5 => '截取缩略图失败，请检查视频开始、结束、视频截图时间',
    ];

    /**
     * 在退出时使用不同的错误码
     * 错误码
     * -- 126 脚本没有执行权限
     */
    public function phpRunShellScript()
    {
        // 脚本路径
        $scriptPath = $_SERVER['DOCUMENT_ROOT'] . "/shell/php-test.sh";
        $scriptParam = '1 2 8';
        $cmdStr = "{$scriptPath} {$scriptParam}";
        echo $cmdStr;
        // 根据版本号拼接视频文件名
        exec("{$cmdStr}", $output_result, $return_status);

        // 返回码判断
        if($return_status == self::PERMISSION ){
            echo "chmod u+x ".$scriptPath."<br/>";
        }elseif ($return_status == self::DOS2UNIX){
            echo "需要使用dos2unix命令将文件转换为unix格式 <br/>";
        }else{
            // 这时候要根据脚本返回的第二个返回值判断脚本具体哪里出错误了
            echo "脚本执行异常 MSg ：" . $return_status . "<br/>";
            var_dump($output_result);
            if ( isset($output_result[1]) && $output_result[1] == 1) {
                echo " MSg1 : ".self::OUTPUT_MSG[$output_result[1]]."<br/>";
            } elseif (isset($output_result[1]) && $output_result[1] == 2){
                echo " MSg2 : ".self::OUTPUT_MSG[$output_result[1]]."<br/>";
            } elseif (isset($output_result[1]) && $output_result[1] == 3){
                echo " MSg3 : ".self::OUTPUT_MSG[$output_result[1]]."<br/>";
            }else{
                echo " MSg Unknown : <br/>";
            }
        }
        echo 'success';
    }
}