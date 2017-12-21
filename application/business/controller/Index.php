<?php

/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/21 13:50
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/
namespace app\business\controller;

use app\common\controller\BaseFrontend;
use think\Db;

class Index extends BaseFrontend
{
    // 首页
    public function index()
    {
        $article = Db::table("resty_article")
            ->alias('a')
            ->join('resty_category c', 'c.id = a.cate_id')
            ->join('resty_user u', 'u.id = a.author_id')
            ->field("a.title,a.create_time,a.content,a.id,a.views,a.image_thumb,a.desc,c.name as c_name,u.username")
            ->order("a.create_time desc,a.id desc")
            ->paginate(4);
        $articlesList = Db::table("resty_article")
            ->alias('a')
            ->join('resty_category c', 'c.id = a.cate_id')
            ->join('resty_user u', 'u.id = a.author_id')
            ->field("a.title,a.create_time,a.content,a.id,a.views,a.image_thumb,a.desc,c.name as c_name,u.username")
            ->order("a.create_time desc,a.id desc")
            ->paginate(8);
        $this->assign('articles', $article);
        $this->assign('articlesList', $articlesList);
        $this->assign('page', $article->render());
        return $this->fetch();
    }

    // 详情
    public function detail()
    {
        $postId = input("param.id");
        if (empty($postId) || !is_numeric($postId)) {
            return json(['code' => 404]);
        }
        $articleDetailCacheKey = 'resty_article_detail:' . $postId;
        if ($this->cache_switch == true && redisCache()->has($articleDetailCacheKey) == true) {
            $article = redisCache()->get($articleDetailCacheKey);
        } else {
            // 先删除，再查询缓存
            redisCache()->rm($articleDetailCacheKey);
            $article = Db::table("resty_article")
                ->alias('a')
                ->join('resty_category c', 'c.id = a.cate_id')
                ->join('resty_user u', 'u.id = a.author_id')
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
        // 观看使用缓存
        redisCache()->inc('views:'.$postId);
        Db::table('resty_article')->where('id', ':id')->bind(['id' => [$postId, \PDO::PARAM_INT]])->cache(10)->setInc('views');
        $this->assign('article', $article);
        $this->assign('tags', $tags);
        $this->assign('comments', $commentInfos);
        $this->assign('commentCounts', count($commentInfos));
        return $this->fetch();
    }

    /**
     * 评论暂时不做缓存
     * 1、通过文章ID遍历获取全部评论以及回复
     * 2、这里不可以使用缓存，TP5自带的
     * @param $post_id
     * @param int $parent_id
     * @param array $result
     * @return array
     */
    public function getCommentListByPostId($post_id, $parent_id = 0, &$result = [])
    {
        $arr = Db::table("resty_comment")
            ->alias('c')
            ->join('resty_open_user ou', 'c.user_id = ou.id')
            ->field('c.comment_id,c.user_id,c.post_id,c.parent_id,c.comment_content,c.parent_id,c.create_time,ou.account,ou.avatar')
            ->where('c.post_id', $post_id)
            ->where('c.parent_id', $parent_id)
            ->order('c.create_time desc')
            ->select();
        if (empty($arr)) return [];
        foreach ($arr as $cm) {
            $thisArr =& $result[];
            $cm["children"] = $this->getCommentListByPostId($cm["post_id"], $cm["comment_id"], $thisArr);
            $thisArr = $cm;
        }
        return $result;
    }
}