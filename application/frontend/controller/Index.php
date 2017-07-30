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
use think\Db;
use think\Loader;

class Index extends Controller
{
    public function index()
    {
        $tags = Db::table('resty_tag')
            ->alias('t')
            ->join('resty_article_tag at',"t.id = at.tag_id")
            ->field('t.name,count(at.article_id) as art_num,at.tag_id')
            ->group('t.id')
            ->select();
        $article = Db::table("resty_article")
            ->alias('a')
            ->join('resty_category c', 'c.id = a.cate_id')
            ->join('resty_user u', 'u.id = a.author_id')
            ->field("a.title,a.create_time,a.content,a.id,a.views,c.name as c_name,u.username")
            ->order("a.create_time desc,a.id desc")
            ->paginate(4);
        $this->assign('tags', $tags);
        $this->assign('list', $article);
        return $this->fetch();
    }

    public function gitApi()
    {
        $github_url = "https://github.com/login/oauth/authorize";
        // 这个参数是必须的，这就是我们在第一步注册应用程序之后获取到的Client ID；
        $client_id = "5e70ee2d904f655b0c31";
        // 该参数可选，当我们从Github获取到code码之后跳转到我们自己网站的URL
        $redirect_uri = "http://www.tinywan.xyz:8086/frontend/index/redirect_uri";
        $url = $github_url . "?client_id=" . $client_id . "&redirect_uri=" . $redirect_uri;
        header('location:' . $url);
    }

    public function redirect_uri(Request $request)
    {
        //'code' => string '137b34c45d7282436d53'
        $code = $request->get('code');
        $client_id = "5e70ee2d904f655b0c31";
        $client_secret = "d190c915d36b5feff7ceeb017ce35ab92e7cb38c";
        $url1 = "https://github.com/login/oauth/access_token";
        //第一步:取全局access_token
        $postRes = $this->curl_request($url1, [
            "client_id" => $client_id,
            "client_secret" => $client_secret,
            "code" => $code,
        ]);
        //第三步:根据全局access_token和openid查询用户信息
        $jsonRes = json_decode($postRes, true);
        $access_token = $jsonRes["access_token"];
        $userUrl = "https://api.github.com/user?access_token=" . $access_token;
        $userInfo = $this->curl_request($userUrl);
        $userJsonRes = json_decode($userInfo, true);
        //第五步，如何设置登录状态
        halt($userJsonRes);
    }


    //参数1：访问的URL，参数2：post数据(不填则为GET)，参数3：提交的$cookies,参数4：是否返回$cookies
    public function curl_request($url, $post = '', $cookie = '', $returnCookie = 0)
    {
        $headers = ["Accept: application/json"];
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 10.0; Windows NT 6.1; Trident/6.0)');
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
        curl_setopt($curl, CURLOPT_REFERER, "http://XXX");
        if ($post) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
        }
        if ($cookie) {
            curl_setopt($curl, CURLOPT_COOKIE, $cookie);
        }
        curl_setopt($curl, CURLOPT_HEADER, $returnCookie);
        curl_setopt($curl, CURLOPT_TIMEOUT, 10);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $data = curl_exec($curl);
        if (curl_errno($curl)) {
            return curl_error($curl);
        }
        curl_close($curl);
        if ($returnCookie) {
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie'] = substr($matches[1][0], 1);
            $info['content'] = $body;
            return $info;
        } else {
            return $data;
        }
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

    public function hello()
    {
        $tags = Db::table('resty_tag')->select();
        $this->assign('tags', $tags);
        return $this->fetch();
    }

    public function articleByTag()
    {
        $tagId = input("param.id");
        $article = Db::table("resty_article")
            ->alias('a')
            ->join('resty_article_tag at', 'a.id = at.article_id')
            ->where('at.tag_id', $tagId)
            ->select();
        halt($article);
    }

    /**
     * 根据标签查询文章
     */
    public function searchByTagId()
    {
        $tagId = input("param.id");
        $articles = Db::table("resty_article")
            ->alias('a')
            ->join('resty_article_tag at', 'a.id = at.article_id')
            ->join('resty_category c', 'c.id = a.cate_id')
            ->join('resty_user u', 'u.id = a.author_id')
            ->field("a.title,a.create_time,a.update_time,a.id,a.views,c.name as c_name,u.username")
            ->where('at.tag_id', $tagId)
            ->order("a.create_time desc , a.id desc")
            ->paginate(6);
        $this->assign("articles", $articles);
        return $this->fetch("search");
    }

    /**
     * 文章详细信息
     */
    public function detail()
    {
        $id = input("param.id");
        $article = Db::table("resty_article")
            ->alias('a')
            ->join('resty_category c', 'c.id = a.cate_id')
            ->join('resty_user u', 'u.id = a.author_id')
            ->field("a.title,a.create_time,a.content,a.views,c.name as c_name,u.username")
            ->where('a.id', $id)
            ->find();
        $tags = Db::table("resty_tag")
            ->alias('t')
            ->join("resty_article_tag at","at.tag_id = t.id")
            ->where("at.article_id",$id)
            ->select();
//        halt($tags);
        // 文章浏览次数加1
        Db::table('resty_article')->where('id',$id)->setInc('views');
        $this->assign('article',$article);
        $this->assign('tags',$tags);
        return $this->fetch();
    }
}