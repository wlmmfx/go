<?php

/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/24
 * Time: 22:33
 */

namespace app\frontend\controller;

use app\frontend\model\User;
use Faker\Factory;
use Faker\Provider\Uuid;
use FFMpeg\FFMpeg;
use think\Controller;
use think\Loader;

class Index extends Controller
{
    public function index()
    {
        return " frontend Index index";
    }

    public function gitApi()
    {
        $url = "https://github.com/login/oauth/authorize";
        // 这个参数是必须的，这就是我们在第一步注册应用程序之后获取到的Client ID；
        $client_id = "5e70ee2d904f655b0c31";
        // 该参数可选，当我们从Github获取到code码之后跳转到我们自己网站的URL
        $redirect_uri = "http://www.tinywan.xyz:8086/frontend/index/redirect_uri";

        return " frontend Index index";
    }

    public function redirect_uri(){
        echo '1111111111111';
        halt($_GET);
    }

    /**
     * 测试是否合并这里的部分
     */
    public function backend()
    {
        $faker = Factory::create();
        $faker2 = Uuid::uuid();
        var_dump($faker2);
    }

    /**
     * view 使用
     * @return \think\response\View
     */
    public function indexView()
    {
        return view("index");
    }

    /**
     * fetch 使用
     * @return mixed
     */
    public function indexFetch()
    {
        return $this->fetch("hello");
    }

    /**
     * User model
     */
    public function userModelTest1()
    {
        // [1]
        $res = User::get(27);
        var_dump($res->toArray());
        // [2]
        $user = new User();
        $res = $user::get(28);
        var_dump($res->toArray());
    }

    /**
     * 使用Loader 查询数据
     */
    public function userModelTest2()
    {
        // 使用Loader
        $user1 = Loader::model("User");
        $user = model("User");
        $res = $user::get(28);
        var_dump($res->toArray());
    }

    /**
     * 使用模型查询数据
     */
    public function userModelTest3()
    {
        // 【1】直接查询
        $user = User::get(27); // 返回为一个对象
        var_dump($user->username);
        // 【2】通过闭包查询
        $user1 = User::get(function ($query) {
            $query->where("username", "eq", "tinywan001");
        });
        var_dump($user1->toArray());
        // 【3】静态方法
        $user2 = new User();
        $res3 = $user2->where('username', "tinywan001")
            ->field("user_id,username")
            ->find();
        var_dump($res3->toArray());
    }

    /**
     * 使用模型查询更新数据
     */
    public function userModelTest4()
    {
        // 【1】直接查询
        $user = User::update([
            "username" => "33333333333"
        ], ["user_id" => 27]);
        var_dump($user);
    }

    /**
     *  模型获取器
     */
    public function userModelTest5()
    {
        $user = User::get(18);
        var_dump($user->status);
        var_dump($user->toArray());
        // 获取原始的数据
        var_dump($user->getData());
    }

    /**
     *  模型修改器-+自动完成
     */
    public function userModelTest6()
    {
        $data = [
            "username" => "USER" . rand(00000, 99999),
            "password" => "password" . rand(55555, 99999),
            "apikey_value" => "756684177@qq.com",
            "description" => "模型修改器",
        ];
        //使用模型插入一条记录
        $user = User::create($data);
        var_dump($user);
    }

    /**
     * 模型时间戳+软删除
     */
    public function userModelTest7()
    {
//        $data = [
//            "username"=>"Tinywan:".rand(00000,99999),
//            "password"=>"password::".rand(55555,99999),
//            "apikey_value"=>"756684177@qq.com",
//            "description"=>"模型时间戳",
//        ];
//        //使用模型插入一条记录
//        $user = User::create($data);
//        var_dump($user);

        //数据库更新操作
        $userModel = User::get(79);
        $userModel->status = 1;
        $res = $userModel->save();
        var_dump($res);
    }

    /**
     * 软删除
     */
    public function userModelTest8()
    {
        # 执行软删除
        //$userModel = User::destroy(85); # success return 1
        //var_dump($userModel);

        # 根据ID字段获取软删除
        //$res = User::withTrashed(true)->find(85);
        //var_dump($res->getData());

        # 获取所有被删除字段
//        $resAll = User::onlyTrashed()->select();
//        foreach ($resAll as $val){
//            var_dump($val->getData());
//        }

        # 数据的真实删除
        //$resBackup = User::destroy(85,true);
        //var_dump($resBackup);

        //第二种软删除
//        $res2 = User::get(84);
//        $resdel = $res2->delete();
//        var_dump($resdel);

        //第二种真实删除
        $res2 = User::get(84);
        $resdel = $res2->delete(true);
        var_dump($resdel);

    }
}