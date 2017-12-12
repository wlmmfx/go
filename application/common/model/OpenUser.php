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
        $result = $this->save($data);
        if (false === $result) {
            // 验证失败 输出错误信息
            return ['valid' => 0, 'msg' => $this->getError()];
        }
        session('open_user.id', $this->pk);
        session('open_user.username', $result['account']);
        return ['valid' => 1, 'msg' => "注册成功"];
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
        $insertData['password'] = md5($data['email']);
        $insertData['email'] = $data['email'];
        $insertData['create_time'] = date("Y-m-d H:i:s");;
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
}
