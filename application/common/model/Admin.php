<?php

namespace app\common\model;

use function EasyWeChat\Payment\get_client_ip;
use Faker\Factory;
use houdunwang\crypt\Crypt;
use think\Loader;
use think\Model;
use think\Validate;

class Admin extends Model
{
    /**
     * 获取随机姓名
     * @return string
     * @static
     */
    public static function getRandUserName()
    {
        $faker = Factory::create($locale = 'zh_CN');
        return $faker->country . '-' . $faker->name;
    }

    /**
     * 主键
     * @var string
     */
    protected $pk = "id";

    /**
     * 设置当前模型对应的完整数据表名称
     * @var string
     */
    protected $table = "resty_user";

    /**
     * 登录验证
     * @param $data
     * @return array
     */
    public function login($data)
    {
        // 1 验证数据
        $validate = Loader::validate('Admin');
        if (!$validate->check($data)) {
            // 返回给控制器
            return ['valid' => 0, 'msg' => $validate->getError()];
        }
        // 2 验证用户名的正确性 查找不到返回 null
        $infoUser = $this->where("email", $data['email'])->where("password", md5($data['password']))->find();
        if (!$infoUser) {
            // 数据库中为匹配到
            return ['valid' => 0, 'msg' => "邮箱或者密码错误"];
        }
        if ($infoUser["enable"] == 0) return ['valid' => 0, 'msg' => "该账号还没有激活，请登录邮箱" . $infoUser["email"] . '立即激活'];
        // 3 记录session
        session('admin.admin_id', $infoUser['id']);
        session('admin.username', $infoUser['username']);
        // 4 每天登录增加经验值
        $today = strtotime(date('Y-m-d')); // 获取今天0时0分0秒的时间
        // 如果上次的登录时间小于今天的时间，则增加经验值
        if ($infoUser['logintime'] < $today) {
            $this->where('id',$infoUser['id'])->setInc('expire', 10);
        }
        return ['valid' => 1, 'msg' => "登录成功"];
    }

    /**
     * 邮箱注册
     * @param $data
     * @return array
     */
    public function emailRegister($data)
    {
        // 1 验证数据
        $validate = new Validate([
            'email' => 'require|email',
            'password' => 'require',
            'repassword' => 'require|confirm:password'
        ], [
            'password.require' => "密码不能为空！",
            'repassword.require' => "两次密码输入不一致！",
            'repassword.confirm' => "两次密码输入不一致！"
        ]);
        if (!$validate->check($data)) {
            return ['valid' => 0, 'msg' => $validate->getError()];
        }
        // 2 检测邮箱是否被注册
        $time = time();
        $passwordToken = md5($data['email'] . md5($data['password']) . $time); //创建用于激活识别码
        $userInfo = $userInfo = $this->where('email', $data['email'])->find();
        if ($userInfo) return ['valid' => 0, 'msg' => "该邮箱已经被注册"];
        // 3 插入数据库
        $res = $this->data([
            'username' => self::getRandUserName(),
            'password' => md5($data["password"]),
            'email' => $data["email"],
            'password_token' => $passwordToken,
            'loginip' => "127.0.0.1",
        ])->save();
        if (!$res) return ['valid' => 0, 'msg' => "数据库添加数据失败"];
        // 4 发送邮件

        $emailSendDomain = config('email.EMAIL_SEND_DOMAIN');
        $checkstr = base64_encode($data['email']);
        $auth_key = get_auth_key($data['email']);
        $link = "http://{$emailSendDomain}/backend/login/emailRegisterUrlValid?checkstr=$checkstr&auth_key={$auth_key}";
        $str = <<<html
            您好！<p></p>
            感谢您在Tinywan世界注册帐户！<p></p>
			帐户需要激活才能使用，赶紧激活成为Tinywan家园的正式一员吧:)<p></p>
            点击下面的链接立即激活帐户(或将网址复制到浏览器中打开):<p></p>
			$link
html;
        //传递一个数组，可以实现多邮件发送,有人注册的时候给管理员也同时发送一份邮件
        $result = send_email($data["email"], '物联网智能数据 帐户激活邮件--', $str);
        if ($result['error'] == 1) return ['valid' => 0, 'msg' => "邮件发送失败，请联系管理员"];
        return ['valid' => 1, 'msg' => $data['email'] . "注册成功，请立即验证邮箱<br/>邮件发送至: " . $data['email']];
    }

    /**
     * checkstr=NzU2Njg0MTc3QHFxLmNvbQ==&amp;auth_key=1499661824-0-0-0c3729355a38452f491584711b76e46d
     * 邮箱注册回调地址的有效性检测 emailRegisterUrlValid
     * @param $data
     * @return array
     */
    public function emailRegisterUrlValid($data)
    {
        $email = base64_decode($data['checkstr']);
        // 签名验证
        $res = check_auth_key($email, $data['auth_key']);
        // 1 邮箱过期时间
        if (!$res["valid"]) return ['valid' => 0, 'msg' => $res["msg"]];
        // 2 验证邮箱
        $userInfo = $this->where('email', $email)->where('enable', 0)->find();
        if (!$userInfo) return ['valid' => 0, 'msg' => "该账户已被激活或者该账户不存在"];
        // 3、修改数据库字段信息
        $res = $this->save([
            'enable' => 1  # 表示已经激活
        ], [$this->pk => $userInfo['id']]);
        if (!$res) return ['valid' => 0, 'msg' => "邮件激活失败"];
        // 4 记录session
        session('admin.admin_id', $userInfo['id']);
        session('admin.username', $userInfo['username']);
        return ['valid' => 1, 'msg' => "邮箱激活成功，正在跳转到主页面..."];
    }

    /**
     * 修改密码
     * @param $data
     * @return array
     */
    public function changePassword($data)
    {
        // 1 验证数据
        $validate = new Validate([
            'password' => 'require',
            'new_password' => 'require',
            'repassword' => 'require|confirm:new_password'
        ], [
            'password.require' => "密码不能为空！",
            'new_password.require' => "新密码不能为空！",
            'repassword.require' => "两次密码输入不一致！",
            'repassword.confirm' => "两次密码输入不一致！"
        ]);
        if (!$validate->check($data)) {
            return ['valid' => 0, 'msg' => $validate->getError()];
        }
        // 2 原始密码是否正确
        $userInfo = $this->where('id', session("admin.admin_id"))->where('password', md5($data['password']))->find();
        if (!$userInfo) {
            // 数据库中为匹配到
            return ['valid' => 0, 'msg' => "原始密码错误"];
        }
        // 3 更新数据
        $res = $this->save([
            'password' => md5($data['new_password'])
        ], [$this->pk => session("admin.admin_id")]
        ); //更新主键
        if ($res) return ['valid' => 1, 'msg' => "密码修改成功"];
        return ['valid' => 0, 'msg' => "密码修改失败"];
    }

    /**
     * 检测邮箱是否注册
     * @param $data
     * @return array
     */
    public function checkSendEmail($data)
    {
        // 1 验证数据
        $validate = new Validate([
            'email' => 'require|email'
        ], [
            'email.require' => "发送邮箱不能为空",
            'email.email' => "邮箱格式不合适"
        ]);
        if (!$validate->check($data)) {
            return ['valid' => 0, 'msg' => "邮箱不能为空或者格式不合适"];
        }
        // 2 该邮箱是否注册
        $userInfo = $this->where('email', $data['email'])->find();
        if (!$userInfo) return ['valid' => 0, 'msg' => "该邮箱尚未注册"];
        // 邮箱配置文件
        $emailSendDomain = config('email.EMAIL_SEND_DOMAIN');
        $checkstr = base64_encode($data['email']);
        $auth_key = get_auth_key($data['email']);
        $link = "http://{$emailSendDomain}/backend/login/checkEmailUrlValid?checkstr=$checkstr&auth_key={$auth_key}";

        $str = "您好!{$userInfo['username']}， 请点击下面的链接重置您的密码：<p></p>" . $link;
        $sendResult = send_email($data['email'], "Tinywan世界重置密码", $str);
        if ($sendResult['error'] == 1) return ['valid' => 0, 'msg' => "邮件发送失败，请联系管理员"];
        // 4 修改密码发送时间
        $updateResult = $this->save([
            'password_time' => time()
        ], [$this->pk => $userInfo['id']]);
        if (!$updateResult) return ['valid' => 0, 'msg' => "修改数据库密码发送时间失败"];
        return ['valid' => 1, 'msg' => $data['email'] . "系统已向您的邮箱发送了一封邮件<br/>请登录到您的邮箱及时重置您的密码"];
    }

    /**
     * 邮箱回调地址的有效性检测
     * @param $data
     * @return array
     */
    public function checkEmailUrlValid($data)
    {
        // 1 检查url地址有效性
        $email = base64_decode($data['checkstr']);
        $res = check_auth_key($email, $data['auth_key']);
        if (!$res["valid"]) return ['valid' => 0, 'msg' => $res["msg"]];
        // 2 验证邮箱
        $userInfo = $userInfo = $this->where('email', $email)->find();
        if (!$userInfo) return ['valid' => 0, 'msg' => "该邮箱尚未注册"];
        // 3、验证通过，跳转到密码修改界面
        session('admin.admin_id', $userInfo['id']);
        session('admin.username', $userInfo['username']);
        return ['valid' => 1, 'email' => $email];
    }

    /**
     * 邮箱重设密码
     * @param $data
     * @return array
     */
    public function reSetPassword($data)
    {
        // 1 验证数据
        $validate = new Validate([
            'new_password' => 'require|min:6|max:64',
            'repassword' => 'require|confirm:new_password'
        ], [
            'new_password.require' => "密码不能为空！",
            'new_password.min' => "密码不能少于6位！",
            'new_password.max' => "密码不能大于6位！",
            'repassword.require' => "两次密码输入不一致！",
            'repassword.confirm' => "两次密码输入不一致！"
        ]);
        if (!$validate->check($data)) {
            return ['valid' => 0, 'msg' => $validate->getError()];
        }
        // 2 更新数据
        $userInfo = $this->where('email', $data['email'])->find();
        $res = $this->save([
            'password' => md5($data['new_password'])
        ], [$this->pk => $userInfo['id']]
        );
        if (!$res) return ['valid' => 0, 'msg' => "邮箱密码修改失败" . $userInfo['username']];
        // 3 密码修改成功直接跳转到主页面
        session('admin.admin_id', $userInfo['id']);
        session('admin.username', $userInfo['username']);
        return ['valid' => 1, 'msg' => "邮箱密码修改成功，正在跳转到主页面..."];
    }

    /**
     * oAuth 邮箱发送邮件
     * @param $data
     * @return array
     */
    public function oAuthSendEmail($data)
    {
        // 1 验证数据
        $validate = new Validate([
            'email' => 'require|email',
        ], [
            'email.require' => "邮箱是不能为空！",
        ]);
        if (!$validate->check($data)) {
            return ['valid' => 0, 'msg' => $validate->getError()];
        }
        // 2 检测邮箱是否被注册
        $userInfo = $userInfo = $this->where('email', $data['email'])->find();
        if ($userInfo) return ['valid' => 0, 'msg' => "该邮箱已经被注册"];
        // 4 发送邮件
        $emailSendDomain = config('email.EMAIL_SEND_DOMAIN');
        $checkstr = base64_encode($data['email']);
        $auth_key = get_auth_key($data['email']);
        $email_code = mt_rand(1111,9999);
        $str = <<<html
            您好！你的验证码：<p></p>
            <h1>$email_code</h1><p></p>
html;
        //传递一个数组，可以实现多邮件发送,有人注册的时候给管理员也同时发送一份邮件
        $result = send_email($data["email"], '物联网智能数据 邮件验证码：', $str);
        if ($result['error'] == 1) return ['valid' => 0, 'msg' => "邮件发送失败，请联系管理员"];
        //存储方便验证
        session($data['email'].':email_code',$email_code);
        return ['valid' => 1, 'msg' => $data['email'] . "注册成功，请立即验证邮箱<br/>邮件发送至: " . $data['email']];
    }


}
