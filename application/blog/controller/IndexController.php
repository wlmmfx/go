<?php

/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/24
 * Time: 22:33
 */

namespace app\blog\controller;

use app\common\controller\BaseFrontendController;
use app\common\model\Comments;
use think\Cache;
use think\Db;
use think\Debug;
use think\Log;

class IndexController extends BaseFrontendController
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
        $this->comment_db = new Comments();
        $this->cache_switch = true;
    }

    /**
     * 1、首页列表
     * 2、添加缓存
     * @return mixed
     */
    public function index()
    {
        // 分页缓存做不了
        $article = Db::table("resty_article")
            ->alias('a')
            ->join('resty_category c', 'c.id = a.cate_id')
            ->join('resty_user u', 'u.id = a.admin_id')
            ->field("a.title,a.create_time,a.content,a.id,a.views,a.image_thumb,a.desc,c.name as c_name,u.username")
            ->order("a.create_time desc,a.id desc")
            ->paginate(4);
        $this->assign('articles', $article);
        $this->assign('articleCounts', Db::table("resty_article")->count());
        $this->assign('page', $article->render());
        return $this->fetch();
    }

    /**
     * 1、首页列表
     * 2、添加缓存
     * @return mixed
     */
    public function prism()
    {
        $this->view->engine->layout(false);
        return $this->fetch();
    }

    /**
     * 1、文章详细
     * 2、文章详细单条缓存
     * 3、文章标签多条缓存
     */
    public function detail()
    {
        $postId = input("param.id");
        if (empty($postId) || !is_numeric($postId)) {
            return json(['code' => 404]);
        }
        // 文章单个缓存
        Debug::remark('begin');
        $articleDetailCacheKey = 'resty_article_detail:' . $postId;
        if ($this->cache_switch == true && redisCache()->has($articleDetailCacheKey) == true) {
            $article = redisCache()->get($articleDetailCacheKey);
            //$article['DataSources'] = 'content from Cache';
        } else {
            // 先删除，再查询缓存
            redisCache()->rm($articleDetailCacheKey);
            $article = Db::table("resty_article")
                ->alias('a')
                ->join('resty_category c', 'c.id = a.cate_id')
                ->join('resty_user u', 'u.id = a.admin_id')
                ->field("a.title,a.id,a.create_time,a.content,a.views,c.name as c_name,u.username")
                ->where('a.id', ':id')
                ->bind(['id' => [$postId, \PDO::PARAM_INT]])
                ->find();
            // 使用Redis缓存操作
            if ($this->cache_switch == true) redisCache()->set($articleDetailCacheKey, $article);
            $article['DataSources'] = 'content from MySQL';
        }
        // 标签多条缓存
        $articleTagKey = 'resty_tag_detail:' . $postId;
        if ($this->cache_switch == true && redisCache()->has($articleTagKey)) {
            $tags = redisCache()->get($articleTagKey);
//            $tags['DataSources'] = 'tags content from Cache';
        } else {
            redisCache()->rm($articleTagKey);
            $tags = Db::table("resty_tag")
                ->alias('t')
                ->join("resty_article_tag at", "at.tag_id = t.id")
                ->where("at.article_id", ':article_id')
                ->bind(['article_id' => [$postId, \PDO::PARAM_INT]])
                ->select();
            // 多级缓存
            $tmpTags = [];
            foreach ($tags as $v) {
                $tmpTags[] = [
                    'id' => $v['id'],
                    'name' => $v['name'],
                    'article_id' => $v['article_id'],
                    'tag_id' => $v['tag_id']
                ];
            }
            if ($this->cache_switch == true) redisCache()->set($articleTagKey, $tmpTags);
            $tags['DataSources'] = 'tags content from MySQL';
        }
        $commentInfos = $this->getCommentListByPostId($postId);
        Debug::remark('end');
        Log::error(Debug::getRangeTime('begin','end').'s');
        // 观看使用缓存
        redisCache()->inc('views:'.$postId);
        Db::table('resty_article')->where('id', ':id')->bind(['id' => [$postId, \PDO::PARAM_INT]])->cache(10)->setInc('views');
        $this->assign('article', $article);
        $this->assign('articleCounts', Db::table("resty_article")->count());
        $this->assign('tags', $tags);
        $this->assign('comments', $commentInfos);
        $this->assign('commentCounts', count($commentInfos));
        return $this->fetch();
    }

    /**
     * 评论暂时不做缓存
     * 1、通过文章ID遍历获取全部评论以及回复
     * 2、这里不可以使用缓存，TP5自带的
     * @param $article_id
     * @param int $parent_id
     * @param array $result
     * @return array
     */
    public function getCommentListByPostId($article_id, $parent_id = 0, &$result = [])
    {
        $arr = Db::table("resty_comment")
            ->alias('c')
            ->join('resty_open_user ou', 'c.user_id = ou.id')
            ->field('c.comment_id,c.user_id,c.article_id,c.parent_id,c.comment_content,c.parent_id,c.create_time,ou.account,ou.avatar')
            ->where('c.article_id', $article_id)
            ->where('c.parent_id', $parent_id)
            ->order('c.create_time desc')
            ->select();
        if (empty($arr)) return [];
        foreach ($arr as $cm) {
            $thisArr =& $result[];
            $cm["children"] = $this->getCommentListByPostId($cm["article_id"], $cm["comment_id"], $thisArr);
            $thisArr = $cm;
        }
        return $result;
    }

    /**
     * 由于异步暂时不做缓存
     * 发表评论、回复公用一个控制器
     */
    public function commentStore()
    {
        if (request()->isPost()) {
            $data['article_id'] = input('post.post_id');
            $data['parent_id'] = input('post.parent_id');
            $data['user_id'] = input('post.user_id');
            $data['comment_content'] = input('post.comment_content');
            if (empty($data['comment_content'])) {
                $res = ["code" => 500, "msg" => 'param is error'];
                return json($res);
            }
            $res = $this->comment_db->store($data);
            if ($res["valid"]) {
                /**
                 * 这里要返回的信息应该是新插入的数据显示哦
                 */
                $responseData = Db::table("resty_comment")
                    ->alias('c')
                    ->join('resty_open_user ou', 'c.user_id = ou.id')
                    ->field('c.comment_id,c.user_id,c.article_id,c.parent_id,c.comment_content,c.parent_id,c.create_time,ou.account,ou.avatar')
                    ->where('c.comment_id', $res["id"])
                    ->find();
                $responseData['num'] = count($this->getCommentListByPostId($data['article_id']));
                //格式化时间输出
                $responseData['create_time'] = date('Y-m-d H:i:s', $responseData['create_time']);
                $res = [
                    "code" => 200,
                    "msg" => $res["valid"],
                    'list' => $responseData
                ];
            } else {
                $res = ["code" => 500, "msg" => $res["valid"]];
            }
            return json($res);
        }
    }

    public function testSendSms()
    {
        $sendRes = send_dayu_sms(15858196553, "register", ['code' => rand(100000, 999999)]);
        halt($sendRes);

    }

}