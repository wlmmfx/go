<?php

/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/24
 * Time: 22:33
 */

namespace app\frontend\controller;

use app\common\model\Comment;
use app\frontend\model\User;
use Faker\Factory;
use Faker\Provider\Uuid;
use think\Controller;
use think\Db;
use think\Loader;

class Index extends Controller
{
    /**
     * 评论实例
     * @var
     */
    protected $comment_db;

    /**
     * 初始化
     */
    public function _initialize()
    {
        parent::_initialize();
        $this->comment_db = new Comment();
    }

    /**
     * 首页列表
     * @return mixed
     */
    public function index()
    {
        $tags = Db::table('resty_tag')
            ->alias('t')
            ->join('resty_article_tag at', "t.id = at.tag_id")
            ->field('t.name,count(at.article_id) as art_num,at.tag_id')
            ->group('t.id')
            ->select();
        $article = Db::table("resty_article")
            ->alias('a')
            ->join('resty_category c', 'c.id = a.cate_id')
            ->join('resty_user u', 'u.id = a.author_id')
            ->field("a.title,a.create_time,a.content,a.id,a.views,a.image_thumb,a.desc,c.name as c_name,u.username")
            ->order("a.create_time desc,a.id desc")
            ->paginate(4);
        $userInfo = Db::table('resty_open_user')->where('id', session('open_user_id'))->find();
        $this->assign('tags', $tags);
        $this->assign('list', $article);
        $this->assign('userInfo', $userInfo);
        return $this->fetch();
    }

    /**
     * APi  数据获取
     */
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

    /**
     * 回调地址
     * @param Request $request
     */
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

    public function hello()
    {
        $tags = Db::table('resty_tag')->select();
        $this->assign('tags', $tags);
        return $this->fetch();
    }

    /**
     * 通过标签查询文章
     */
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
     * 根据标签Id查询文章
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
        $userInfo = Db::table('resty_open_user')->where('id', session('open_user_id'))->find();
        $this->assign('userInfo', $userInfo);
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
            ->field("a.title,a.id,a.create_time,a.content,a.views,c.name as c_name,u.username")
            ->where('a.id', $id)
            ->find();
        $tags = Db::table("resty_tag")
            ->alias('t')
            ->join("resty_article_tag at", "at.tag_id = t.id")
            ->where("at.article_id", $id)
            ->select();
        $userInfo = Db::table('resty_open_user')->where('id', session('open_user_id'))->find();
        $commentInfos = $this->getCommlist($id);
//        halt($commentInfos);
        Db::table('resty_article')->where('id', $id)->setInc('views');
        $this->assign('article', $article);
        $this->assign('tags', $tags);
        $this->assign('userInfo', $userInfo);
        $this->assign('comments', $commentInfos);
        $this->assign('commentCounts', count($commentInfos));
        return $this->fetch();
    }

    public function getCommlist($post_id, $parent_id = 0, &$result = array())
    {
        $arr = Db::table("resty_comment")
            ->alias('c')
            ->join('resty_open_user ou', 'c.user_id = ou.id')
            ->field('c.comment_id,c.user_id,c.post_id,c.parent_id,c.comment_content,c.parent_id,c.create_time,ou.account,ou.avatar')
            ->where('c.post_id', $post_id)
            ->where('c.parent_id', $parent_id)
            ->order('c.create_time desc')
            ->select();
        if (empty($arr)) {
            return array();
        }
        foreach ($arr as $cm) {
            $thisArr =& $result[];
            $cm["children"] = $this->getCommlist($cm["post_id"], $cm["comment_id"], $thisArr);
            $thisArr = $cm;
        }
        return $result;
    }


    /**
     * 发表评论处理
     */
    public function commentStore1()
    {
        if (request()->isPost()) {
            $res = $this->comment_db->store(input('post.'));
            if ($res["valid"]) {
                $this->success($res["msg"]);
                exit;
            } else {
                $this->error($res["msg"]);
                exit;
            }
        }
    }

    /**
     * 发表评论处理
     */
    public function commentStore()
    {
        if (request()->isPost()) {
            $data['post_id'] = input('post.post_id');
            $data['parent_id'] = input('post.parent_id');
            $data['user_id'] = input('post.user_id');
            $data['comment_content'] = input('post.comment_content');
            $res = $this->comment_db->store($data);
            if ($res["valid"]) {
                /**
                 * 这里要返回的信息应该是新插入的数据显示哦
                 */
                $arr = Db::table("resty_comment")
                    ->alias('c')
                    ->join('resty_open_user ou', 'c.user_id = ou.id')
                    ->field('c.comment_id,c.user_id,c.post_id,c.parent_id,c.comment_content,c.parent_id,c.create_time,ou.account,ou.avatar')
                    ->where('c.comment_id', $res["id"])
                    ->find();
                //格式化时间输出
                $arr['create_time'] = date('Y-m-d H:i:s', $arr['create_time']);
                $res = [
                    "code" => 200,
                    "msg" => "success",
                    'list' => $arr
                ];
            } else {
                $res = ["code" => 500, "msg" => "fail"];
            }
            return json($res);
        }
    }

    /**
     * 评论回复处理
     * @param $id
     * @return string
     */
    public function commentReply2()
    {
        if (request()->isPost()) {
            $res = $this->comment_db->commentReply(input('post.'));
            if ($res["valid"]) {
                $this->success($res["msg"]);
                exit;
            } else {
                $this->error($res["msg"]);
                exit;
            }
        }
    }

    /**
     * 评论回复处理
     * @param $id
     * @return string
     */
    public function commentReply()
    {
        if (request()->isPost()) {
            $data['post_id'] = input('post.post_id');
            $data['parent_id'] = input('post.parent_id');
            $data['user_id'] = input('post.user_id');
            $data['comment_content'] = input('post.comment_content');
            $res = $this->comment_db->commentReply($data);
            if ($res["valid"]) {
                $responseData['post_id'] = $data['post_id'];
                $responseData['parent_id'] = $data['parent_id'];
                $responseData['user_id'] = $data['user_id'];
                $responseData['comment_content'] = $data['comment_content'];
                $responseData['num'] = count($commentInfos = $this->getCommlist($data['post_id']));
                // 这里要查询出用户信息表啊！
                $res = [
                    "code" => 200,
                    "msg" => "success",
                    'list' => $responseData
                ];
            } else {
                $res = ["code" => 500, "msg" => "fail"];
            }
            return json($res);
        }
    }

    public function commenttest()
    {
        //临时关闭当前模板的布局功能
        $this->view->engine->layout(false);
        $userInfo = Db::table('resty_open_user')->where('id', session('open_user_id'))->find();
        $this->assign('userInfo', $userInfo);
        return $this->fetch();
    }


    /**
     * 百度编辑器
     */
    public function saveInfo()
    {
        $ueditor_config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(ROOT_PATH . 'public' . DS . "common/plugins/ueditor/php/config.json")), true);
        $action = $_GET['action'];
        switch ($action) {
            case 'config':
                $result = json_encode($ueditor_config);
                break;
            /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                $file = request()->file("upfile");
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
                if ($info) {
                    //获取文件名
                    $data['thumb'] = "/uploads/" . $info->getSaveName();
                    // oss upload
                    $bucket = config('aliyun_oss.bucket');
                    $object = 'uploads/' . $info->getSaveName();
                    $file = './' . $object;  //这个才是文件在本地的真实路径，也是就是你要上传的文件信息
                    $oss = OssInstance::Instance();
                    try {
                        $res = $oss->uploadFile($bucket, $object, $file);
                        if ($res['info']['http_code'] == 200) {
                            // 返回数据
                            $url = "http://tinywan-develop.oss-cn-hangzhou.aliyuncs.com" . DS . $object;
                            Log::info("url == " . $url);
                            $result = json_encode(array(
                                'url' => $url,
                                'title' => htmlspecialchars("11111111111", ENT_QUOTES),
                                'original' => $url,
                                'state' => 'SUCCESS'
                            ));
                            if (!is_dir($object)) unlink($object);
                        }
                    } catch (OssException $e) {
                        $url = "http://" . $_SERVER["HTTP_HOST"] . "/uploads/" . $info->getSaveName();
                        Log::info("url == " . $url);
                        $result = json_encode(array(
                            'url' => $url,
                            'title' => htmlspecialchars("2222222222", ENT_QUOTES),
                            'original' => $url,
                            'state' => 'FAIL'
                        ));
                    }

                } else {
                    $result = json_encode(array(
                        'state' => $file->getError(),
                    ));
                }
                break;
            default:
                $result = json_encode(array(
                    'state' => '请求地址出错'
                ));
                break;
        }
        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state' => 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }

    public function info($id)
    {
        return "{$id}";
    }
}