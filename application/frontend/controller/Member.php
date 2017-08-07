<?php
/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/7/1
 * Time: 13:40
 */
namespace app\frontend\controller;

use app\common\model\Admin;
use think\Controller;
use think\Request;

class Member extends Controller
{
    public function index()
    {
        return $this->fetch();
    }

    public function login()
    {
        return $this->fetch();
    }

    /**
     * 注册
     * @return mixed
     */
    public function signup(Request $request)
    {
        //1 验证数据
        if ($request->isPost()) {
            $res = (new Admin())->emailRegister(input("post."),"frontend");
            if ($res['valid']) {
                //success
                add_operation_log('登录成功');
                $this->success($res['msg'], "frontend/index/index");
                exit;
            } else {
                //fail
                add_operation_log('登录失败');
                $this->error($res['msg']);
                exit;
            }
        }
        return $this->fetch();
    }

    /**
     * 邮箱注册URL验证
     * @param Request $request
     */
    public function emailRegisterUrlValid(Request $request)
    {
        if ($request->isGet()) {
            $res = (new Admin())->emailRegisterUrlValid(input("get."),"frontend");
            if ($res["valid"]) {
                //success 把目前的邮箱地址保存在session中
                $this->success($res['msg'], "frontend/index/index");
            } else {
                $this->error($res['msg'], "frontend/index/login");
            }
        }
    }

    /**
     * 登录
     * @return mixed
     */
    public function signin()
    {
        return $this->fetch();
    }


}