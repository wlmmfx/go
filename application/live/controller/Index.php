<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/10/20 22:06
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm
 * '-------------------------------------------------------------------*/

namespace app\live\controller;

use app\common\controller\BaseFrontend;
use app\common\model\Admin;
use app\common\model\Article;
use app\common\model\AuthGroup;
use think\Db;

class Index extends BaseFrontend
{
    /**
     * 初始化
     */
    public function _initialize()
    {
        parent::_initialize();

    }

    /**
     * @return mixed
     */
    public function index()
    {
        $vods = Db::table("resty_vod")
            ->alias('v')
            ->join('resty_category c', 'c.id = v.cid', 'left')
            ->field('v.id,v.create_time,v.name,v.hls_url,v.image_url,v.content,v.download_data,c.name as cName')
            ->order('v.create_time desc')
            ->limit(6)
            ->select();
        $this->assign('banners', db('banner')->where(['publish_status' => 1, 'deleted' => 0])->order('id desc')->select());
        $this->assign('vods', $vods);
//        halt($vods);
        return $this->fetch();
    }

    /**
     * article 表中的用户id必须是用户表开头的，在这里：admin_id
     */
    public function adminHasManyArticle()
    {
        $admin = Admin::get(178);
        $articles = $admin->articles()
            ->where('title', 'like', '%学习笔记%')
            ->order('create_time desc')
            ->select();
        halt($articles);
    }

    /**
     * 查询用户的Profile关联数据
     */
    public function adminRelationArticle()
    {
        $admin = Admin::get(223);
        // relation 方法传入的字符串就是 [关联定义的方法名] 而不是 关联模型的名称
        $list1 = $admin->relation('adminArticles')->select();
        $user2 = $admin->relation('admin_articles')->select();
        foreach ($user2 as $user) {
            print_r($user);
        }
    }

    /**
     * 使用自定义关联查询
     */
    public function adminRelationArticle2()
    {
        // 使用自定义关联查询
        $admin = Admin::get(178);
        $user3 = $admin->relation(['adminArticles' => function ($query) {
            $query->where('title', 'like', '%学习笔记%');
        }])->select();


        //对关联数据进行排序和指定字段
//        $user4 =$admin->relation(['adminArticles' => function ($query) {
//            $query->field('id,title,create_time,admin_id')
//                ->order('create_time desc')
//                ->whereTime('create_time', 'year');
//        }])->select();
        foreach ($user3 as $user) {
            // 使用模型的toArray方法可以同时输出关联属性（对象）
            var_dump($user->toArray());
            var_dump($user->toJson());
        }
    }

    /**
     * 关联输出
     */
    public function adminRelationArticle4()
    {
        // 使用自定义关联查询
        $admin = Admin::get(178,'adminArticles');
        // 上面的代码返回的data数据中不会包含用户模型的name属性以及关联profile模型的email属性
        $data = $admin->hidden(['password_token','mobile','expire','password','adminArticles'=>['oss_upload_status','image_origin','delete_time']]);
        halt($data->toArray());
        foreach ($data['admin_articles'] as $value){
            var_dump($value);
        }
    }

    /**
     * 关联查询
     * relation方法中传入关联（方法）名称即可（多个可以使用逗号分割的字符串或者数组）
     */
    public function adminRelationArticle3()
    {

    }

    /**
     * 一对多关联
     * 【1】获取用户的所有articles
     * 【2】根据查询条件去获取用户的所有articles
     */
    public function getUserArticleByUserId(){
        $user = Admin::get(178);
        // 获取用户的所有博客
        //halt($user->articles);

        // 也可以进行条件搜索
        //halt($user->articles()->where('cate_id', 120)->select());

        //查询博客所属的用户信息,在这里，如果数据查询不到则会返回一个异常哦
        $article = Article::get(27);
        halt($article->admin->username);
    }

    /**
     * 远程一对多
     * FUN：远程一对多的作用是跨过一个中间模型操作查询另外一个远程模型的关联数据，而这个远程模型通常和当前模型是没有任何关联的
     * eg: 一个用户发表了多个博客
     */
    public function getUserArticleCounts(){
        $user = Admin::get(178);
        $article = [];
        foreach ($user->articles as $article) {
            $article[$article->id] = $user->articles()->order('id desc')->limit(100)->select();
        }
        halt($article->toArray());
    }

    /**
     * 多对多关联
     * 多对多关联关系必然会有一个中间表，最少必须包含两个字段，例如auth表就包含了user_id 和 role_id（建议对这两个字段设置联合唯一索引），但中间表仍然可以包含额外的数据。
     * resty_vod_tag 添加联合索引：ALTER TABLE resty_vod_tag ADD UNIQUE INDEX(vod_id,tag_id);
     */
    public function manyToMany(){
        // 查询用户
        $user = Admin::get(178);
        // 查询组织
        $group = AuthGroup::getByTitle('管理员');

        // 获取文章的评论数
        $article = Article::get(119);
        foreach ($article->comments as $comment) {
            dump($comment);
        }
    }

    /**
     * 事件列表
     */
    public function eventList()
    {
        return $this->fetch();
    }

    /**
     * 活动列表
     */
    public function liveList()
    {
        return $this->fetch();
    }

    /**
     * 活动详情
     */
    public function detail()
    {
        $liveId = input('param.id');
        $live = Db::table('resty_live')->where('id', $liveId)->find();
        $streamInfo = Db::table('resty_stream_name')->where('id', $live['stream_id'])->find();
        $this->assign('streamInfo', $streamInfo);
        $this->assign('live', $live);
        return $this->fetch();
    }

    /**
     * 点播详情
     */
    public function vodDetail()
    {
        $liveId = input('param.id');
        $live = Db::table('resty_vod')->where('id', $liveId)->find();
        $this->assign('vod', $live);
        return $this->fetch();
    }

}