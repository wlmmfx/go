<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/9/10 16:43
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm
 * '-------------------------------------------------------------------*/

namespace app\common\model;

use think\Db;

class OpenUser extends BaseModel
{
    protected $pk = "id";
    protected $table = "resty_open_user"; //完整的表名

    /**
     * 添加用户
     * @param $data
     * @return array
     */
    public function store($data)
    {
        return $this->insertGetId($data);
    }

    /**
     * 通过$openId查看该用户是否存在
     */
    public function userIsExistByOpenId($openId)
    {
        $res = $this->where('open_id',$openId)->find();
        if (!$res) return ['valid' => 0, 'msg' => "不存在"];
        return ['valid' => 1, 'msg' => "存在"];
    }

    /**
     * 邮箱注册
     * @param $data
     * @return array
     */
    public function emailRegister($data)
    {
        // 1 验证数据
        $validate = $this->validate($data);
        if (!$validate) {
            // 返回给控制器
            return ['valid' => 0, 'msg' => $validate->getError()];
        }
        // 2 检测邮箱是否被注册
        $userInfo = $this->where('email', $data['email'])->find();
        if ($userInfo) return ['valid' => 0, 'msg' => "该邮箱已经被注册"];
        // 3 考虑URL地址失效问题，重新发送邮件
        $userInfoEnable = $this->where("enable=:enable and email=:email")->bind(['enable' => 0, 'email' => $data['email']])->find();
        if ($userInfoEnable) {
            // 4 放入邮件队列
            addEmailTaskQueue(2, $data['email'], 1);
            return ['valid' => 1, 'msg' => "邮件重新发送成功，请立即验证邮箱:" . $data['email']];
        }
        // 3 插入数据库
        $insertData['account'] = 'Tinywan' . rand(1111, 9999);
        $insertData['realname'] = $data['username'];
        $insertData['password'] = md5($data['pass']);
        $insertData['email'] = $data['email'];
        $insertData['create_time'] = time();
        $insertData['avatar'] = "https://avatars0.githubusercontent.com/u/25687708?v=4";
        $userId = Db::table('resty_open_user')->insertGetId($insertData);
        if ($userId) {
            session('open_user_id', $userId);
            session('open_user_username', $insertData['account']);
        }
        // 4 放入邮件队列
        addEmailTaskQueue(2, $data['email'], 1);
        return ['valid' => 1, 'msg' => $data['email'] . "注册成功，请立即验证邮箱<br/>邮件发送至: " . $data['email']];
    }

    /**
     * 邮箱注册验证
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
        $saveRes = $this->save([
            'enable' => 1  # 表示已经激活
        ], [$this->pk => $userInfo['id']]);
        if ($saveRes) {
            // 4 记录session
            session('open_user_id', $userInfo['id']);
            session('open_user_username', $userInfo['username']);
            return ['valid' => 1, 'msg' => "邮箱激活成功"];
        }
        return ['valid' => 0, 'msg' => "邮箱激活失败"];
    }

    /**
     * 手机注册
     * @param $data
     * @return array
     */
    public function mobileRegister($data)
    {
        $serverCodeExpires = messageRedis()->ttl("MOBILE:" . $data["mobile"]);
        if ($serverCodeExpires == false) return ['valid' => 0, 'msg' => "手机验证码已经失效，请重新获取"];
        $serverCode = messageRedis()->get("MOBILE:" . $data["mobile"]);
        if ($serverCode != $data["code"]) return ['valid' => 0, 'msg' => "手机验证码错误"];
        // 2 验证数据
        $validate = new Validate([
            'mobile' => 'require',
            'password' => 'require'
        ], [
            'mobile.require' => "手机号码不能为空！",
            'password.require' => "密码不能为空！"
        ]);
        if (!$validate->check($data)) {
            return ['valid' => 0, 'msg' => $validate->getError()];
        }
        // 3 插入数据库
        $userName = self::getRandUserName();
        $res = $this->data([
            'username' => $userName,
            'password' => md5($data["password"]),
            'mobile' => $data["mobile"],
            'loginip' => get_client_ip()
        ])->save();
        if (!$res) return ['valid' => 0, 'msg' => "数据库添加数据失败"];
        session('open_user_id', $this->pk);
        session('open_user_username', $userName);
        return ['valid' => 1, 'msg' => "注册成功"];
    }

    /**
     *
     * @param $appId
     * @param $allParam
     * @return bool|string
     */
    public function checkApiSign($appId, $allParam)
    {
        //根据appId查询否存在该用户
        $userInfo = OpenUser::get($appId);
        if (false == $userInfo) return false;
        $appSecret = $userInfo->appSecret;  //$appSecret = sha1('http://sewise.amai8.com/');
        //去除最后的签名
        unset($allParam['_url']);
        unset($allParam['Sign']);
        // 1. 对加密数组进行字典排序
        foreach ($allParam as $key => $value) {
            $sortParam[$key] = $key;
        }
        // 2. 字典排序的作用就是防止因为参数顺序不一致而导致下面拼接加密不同
        sort($sortParam);
        // 3. 将Key和Value拼接
        $str = "";
        foreach ($sortParam as $k => $v) {
            $str = $str . $sortParam[$k] . $allParam[$v];
        }
        //3.将appSecret作为拼接字符串的后缀,形成最后的字符串
        $finalStr = $str . $appSecret;
        //4. 通过sha1加密,转化为大写大写获得签名
        $sign = strtoupper(sha1($finalStr));
        return $sign;
    }
}
