<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  Date: 2017/1/20
 * |  Time: 16:25
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/

namespace app\backend\controller;

use app\common\controller\BaseBackendController;
use app\common\model\Admin;
use think\Db;
use think\Request;

class EntryController extends BaseBackendController
{
    //个人
    public function index()
    {
        $mysqlVer = Db::query('SELECT VERSION() AS ver');
        $config = [
            'url' => $_SERVER['HTTP_HOST'],
            'document_root' => $_SERVER['DOCUMENT_ROOT'],
            'server_os' => PHP_OS,
            'server_port' => $_SERVER['SERVER_PORT'],
            'server_ip' => $_SERVER['SERVER_ADDR'],
            'server_soft' => $_SERVER['SERVER_SOFTWARE'],
            'REMOTE_ADDR' => $_SERVER['REMOTE_ADDR'],
            'php_version' => PHP_VERSION,
            'PHP_EXTENSION_DIR' => PHP_EXTENSION_DIR,
            'mysql_version' => $mysqlVer[0]['ver'],
            'max_upload_size' => ini_get('upload_max_filesize')
        ];
        return $this->fetch('',[
            'sub_title'=>"个人中心",
            'config'=>$config,
        ]);
    }

    /**
     * 修改密码
     * @param Request $request
     * @return mixed
     */
    public function modPass(Request $request)
    {
        if ($request->isPost()) {
            $res = (new Admin())->changePassword(input("post."));
            if ($res["valid"]) {
                //success 密码修改成功，则清除session的登录信息
                session(null);
                $this->success($res['msg'], "backend/entry/index");
                exit;
            } else {
                // fail
                $this->error($res["msg"]);
                exit;
            }
        }
        return $this->fetch();
    }

    /**
     * auth_key=1507083010+604800-0-0-3d9fc802d0ef0de9c10a68a481847aea
     */
    public function get_auth_key()
    {
//        var_dump(get_auth_key("756684177@qq.com"));
        halt(check_auth_key("756684177@qq.com", "1499654985-0-0-e5dfc9987222d2ace9c7e35dd480bd82"));
        die;
        // 1 邮件过期时间
        $email = "756684177@qq.com";
        $emailSendDomain = "mail.qq.com";
        $send_email_expire_time = config('email.EMAIL_SEND_EXPIRE_TIME'); // 10 min
        $send_email_private_key = config('email.EMAIL_SEND_PRIVATE_KEY'); // 私有密钥
        $timestatmp = strtotime(date('Y-m-d H:i:s', strtotime("+" . $send_email_expire_time . "minute")));
        $uuid = 0;
        $uid = 0;
        $hash_value = md5($email . '-' . $timestatmp . '-' . $uuid . '-' . $uid . '-' . $send_email_private_key);
        $auth_key = $timestatmp . '-' . $uuid . '-' . $uid . '-' . $hash_value;
        dump(base64_encode($auth_key));
        $email = base64_encode($email);
//        $auth_key = base64_encode($auth_key); checkstr=MTQ5OTQ4NjA2OTI0MTA3MjVuZ3RhZHg=
        $link = "http://{$emailSendDomain}/backend/login/checkEmailUrlValid?checkstr={$email}&auth_key={$auth_key}";
        echo $link;
    }

    /**
     * 验证auth_key
     * @param $req_auth_key
     * @param string $app
     * @param $stream_name
     * @return bool
     */
    public function check_auth_key($check_str = "NzU2Njg0MTc3QHFxLmNvbQ==", $auth_key = "1499654261-0-0-46880d9e1469e99eca08dc420c54b15a")
    {
        $send_email_expire_time = substr($auth_key, 0, 10);
        echo $send_email_expire_time . "<br/>";
//        if ($send_email_expire_time < time()) return ['valid' => 0, 'msg' => "已经过期了"];
        $uuid = 0;
        $uid = 0;
        $send_email_private_key = config('email.EMAIL_SEND_PRIVATE_KEY'); // 私有密钥
        $sequest_hash_value = substr($auth_key, -32);
        $hash_value = md5(base64_decode($check_str) . '-' . $send_email_expire_time . '-' . $uuid . '-' . $uid . '-' . $send_email_private_key);
        echo $hash_value;
//        if ($hash_value != $sequest_hash_value) return ['valid' => 0, 'msg' => "改地址签名错误"];
//        return ['valid' => 1, 'email' => "验证通过"];
    }

}
