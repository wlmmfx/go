<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/28 8:57
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\test\controller;


use app\api\controller\OpenController;
use app\common\controller\BaseFrontendController;
use app\common\model\Admin;
use app\common\model\Article;
use app\common\model\AuthGroup;
use app\common\model\Category;
use app\common\model\OpenUser;
use app\common\model\User;
use app\common\model\Vod;
use think\Db;

class ModelDemo extends BaseFrontendController
{

    /**
     * 创建（Create）
     * 由于insert和insertAll方法最终都是调用连接类的execute方法，
     * 我们已经知道execute方法的返回值是影响的记录数，
     * 所以insert和insertAll方法的返回值也是影响（新增）的记录数，并不会返回主键值。
     */
    public function createDemo()
    {
        $data = [
            'user_id' => 178,
            'article_id' => 19,
            'comment_content' => '测试数据一',
            'create_time' => time()
        ];
        //插入单个记录，注意，这里的表名是全名，和数据表保持一致，不存在前缀问题
        $res = Db::table('resty_comment')->insert($data);

        // 插入多个记录，注意：insertAll方法的数据集中的元素请确保字段列表一致，否则会出错。
        $ress = Db::table('resty_comment')
            ->insertAll([
                ['user_id' => 9, 'comment_content' => 'thinkphp'],
                ['user_id' => 10, 'comment_content' => 'topthink'],
            ]);

        //主键id如果是自增类型，可以使用下面（插入单个记录），注意：这里的id字段名称没有限制，我这里是：comment_id，也会成功
        $res3 = Db::table('resty_comment')
            ->insert(['comment_content' => 'kancloud']);
        halt($res3);
    }

    /**
     * 你并不需要考虑写入数据失败的情况，数据库操作过程有任何的错误都会抛出异常，你需要做的只是修正BUG或者捕获异常自行处理。
     */
    public function createAndGetIdDemo2()
    {
        $data = [
            'user_id' => 178,
            'article_id' => 19,
            'comment_content' => '测试数据一',
            'create_time' => time()
        ];
        //插入单个记录
        Db::table('resty_comment')->insert($data);
        // 获取上次写入的自增Id
        $id = Db::getLastInsID();

        // 插入单个记录 并返回自增Id
        $id = Db::table('resty_comment')
            ->insertGetId($data);
        halt($id);
    }

    /**
     * 更新（Update）
     * 注意：出于数据安全考虑，ThinkPHP的update方法必须使用更新条件而不允许无条件更新，
     *      如果没有指定更新条件，则会从更新数据中获取主键作为更新条件
     */
    public function updateDemo1()
    {
        $data = [
            'user_id' => 178,
            'article_id' => 19,
            'comment_content' => '测试数据一',
            'create_time' => time()
        ];
        // 更新记录 success: $res = 1
        $res = Db::table('resty_comment')
            ->where('comment_id', 662)
            ->update(['comment_content' => "framework"]);
        // 更新记录 数据更新成功：int 1 ，更新数据和元数据一样：int 0
        $res2 = Db::table('resty_comment')
            ->update(['comment_id' => 663, 'comment_content' => "framework-111comment_id"]);
        halt($res2);
    }

    /**
     * 过滤需要更新的字段列表
     */
    public function updateFilterDemo1()
    {
        $data = [
            'user_id' => 178,
            'article_id' => 19,
            'comment_content' => '测试数据一',
            'create_time' => time()
        ];
        // 更新记录
        $res = Db::table('resty_comment')
            ->field(['create_time'])
            ->where('comment_id', 662)
            ->update(['comment_content' => "framework12222222", 'create_time' => time()]);
        halt($res);
    }

    /**
     * https://www.tinywan.com/test/model_demo/adminHasManyArticle
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
     * ===========================================模型关联===============================================================
     *
     * ===========================================基础方法===============================================================
     *
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
     * 一个分类下的视频
     */
    public function categoryRelationVod()
    {
        $cate = Category::get(132);
        // relation 方法传入的字符串就是 [关联定义的方法名] 而不是 关联模型的名称
//        $vods = $cate->relation('categoryVods')->select();
//        foreach ($vods as $vod) {
//            var_dump($vod->name);
//        }

        $user3 = $cate->relation(['categoryVods' => function ($query) {
            $query->where('name', 'like', '%多媒体%');
        }])->select();
        foreach ($user3 as $user) {
            // 使用模型的toArray方法可以同时输出关联属性（对象）
            var_dump($user->toArray());
        }

        // 使用自定义关联查询
//        $res3 =  $cate->relation(['categoryVods' => function ($query) {
//            $query->field('id,name,title,pub_time,user_id')
//                ->order('pub_time desc')
//                ->whereTime('pub_time', 'year');
//        }])->select();
    }


    /**
     * ====================================关联查询================================================================
     */
    /**
     * 一个分类下的视频
     * relation方法中传入关联（方法）名称即可（多个可以使用逗号分割的字符串或者数组）。
     * 这种方式，无论你是否最终获取profile属性，都会事先进行关联查询，因此称为关联预查询。
     * 注意：如果关联数据不存在，一对一关联返回的是null，一对多关联的话返回的是空数组或空数据集对象。
     */
    public function categoryRelationVod2()
    {
        //【关联预查询】
        // 指定Category模型的categoryVods关联
        //$cate = Category::relation('categoryVods')->find(132);

        // categoryVods关联属性也是一个模型对象实例
        //halt($cate->categoryVods);

        //【 推荐 】【关联延迟查询】出于性能考虑，通常我们选择关联延迟查询的方式。
        // 不需要指定关联
        $cate = Category::get(132);
        // 获取profile属性的时候自动进行关联查询
        dump($cate->categoryVods);
        //这种方式下的关联查询是惰性的，只有在获取关联属性的时候才会实际进行关联查询，因此称之为关联延迟查询。
    }

    /**
     * 关联自定义查询
     */
    public function categoryRelationVod3()
    {
        $user = Category::get(132);
        $vods = $user->categoryVods()
            ->where('name', 'like', '%多媒体%')
            ->order('create_time desc')
            ->select();
        halt($vods);
    }

    /**
     * 关联约束
     * 查询有评论数据的文章
     */
    public function categoryRelationVod4()
    {
        //【1】 查询有评论数据的文章
        $list = Article::has('comments')->select();
        //【2】可以指定关联数据的数量进行查询
        // 查询评论超过3个的文章
        $list = Article::has('comments', '>', 2)->select();

        //【3】如果需要复杂的关联查询约束条件的话，可以使用hasWhere方法，例如：
        // 查询评论状态正常的文章
        $list = Article::hasWhere('comments', ['status' => 1])->select();

        //【4】或者直接使用闭包查询，然后在闭包里面使用链式方法查询：
        // 查询最近一周包含think字符的评论的文章
        $list = Article::hasWhere('comments', function ($query) {
            $query->whereTime('Comments.create_time', 'week')
                ->where('comment_content', 'like', '%测试数据一%');
        })->select();
        halt($list);
    }

    /**
     * 关联预载入
     */
    public function categoryRelationVod5()
    {
        //【1】普通查询
//        $list = Admin::all([213, 178, 225]);
//        foreach ($list as $user) {
//            // 获取用户关联的profile模型数据
//            dump($user->articles);
//        }

        //【2】关联预查询功能,预载入使用with方法指定需要预载入的关联（方法）
        $list = Admin::with('articles')->select([213, 178, 225]);
        foreach ($list as $user) {
            // 获取用户关联的profile模型数据
            dump($user->articles);
        }

        //【3】 鉴于预载入查询的重要性，模型的get和all方法的第二个参数可以直接传入预载入参数
        $list = Admin::all([213, 178, 225], 'articles');
        foreach ($list as $user) {
            // 获取用户关联的profile模型数据
            dump($user->articles);
        }
        // 【2】和【3】是等效的
    }

    /**
     * 预载入条件限制
     */
    public function categoryRelationVod6()
    {
        $list = Admin::with(['articles' => function ($query) {
            $query->where('title ', 'like', '%学习笔记%')
                ->field('id,title,keyword')
                ->order('create_time desc');
        }])->select([213, 178, 225]);
        foreach ($list as $user) {
            // 获取用户关联的articles模型数据
            dump($user->articles);
        }
    }

    /**
     * 关联统计
     */
    public function categoryRelationVod7()
    {
        $list = Admin::withCount('cards')->select([1, 2, 3]);
        foreach ($list as $user) {
            // 获取用户关联的card关联统计
            echo $user->cards_count;
        }
    }

    /**
     * 关联输出
     * 根据用户的ID 获取所有的文章
     */
    public function categoryRelationVod8()
    {
        $user = Admin::get(178, 'articles');
//        $data = $user->toArray();
        //如果要隐藏多个关联属性的话，可以使用下面的方式：
        $data = $user->hidden(['password', 'deleted', 'password_time', 'articles' => ['keyword', 'oss_upload_status', 'cate_id']])->toArray();
        dump($data);
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
        $admin = Admin::get(178, 'adminArticles');
        // 上面的代码返回的data数据中不会包含用户模型的name属性以及关联profile模型的email属性
        $data = $admin->hidden(['password_token', 'mobile', 'expire', 'password', 'adminArticles' => ['oss_upload_status', 'image_origin', 'delete_time']]);
        halt($data->toArray());
        foreach ($data['admin_articles'] as $value) {
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
    public function getUserArticleByUserId()
    {
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
    public function getUserArticleCounts()
    {
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
    public function manyToManyDemo1()
    {
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

    public function manyToManyDemo2()
    {
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
     * 点播视频查询
     */
    public function manyToManyDemo3()
    {
        $vod = Vod::get(51);
        halt($vod->tags());
        // 查询组织
        $group = AuthGroup::getByTitle('管理员');

        // 获取文章的评论数
        $article = Article::get(119);
        foreach ($article->comments as $comment) {
            dump($comment);
        }
    }

    /**
     * --------------------------------------数据库和模型----------------------------------------------------------------
     * -----------------------------------------------------------------------------------------------------------------
     * 模型查询
     */
    public function modelDemo01()
    {
        // 查询操作
        $user = User::get(178);
        // 取值操作
        echo $user->username;
        echo $user->email;

        // 设置操作
        $user->city_id  = '2';
        // 由于模型类实现了ArrayAccess接口，因此一样可以使用数组方式操作
        $user['mobile']  = '123456';

        // 更新操作
        halt($user->getUserEmail());
        halt($user->setUserEmail('756684177'));
        halt($user->save());
    }

    /**
     * 模型创建Create
     */
    public function curlDemo01()
    {
        $user = new User();
        $user->email = '756682@qq.com';
        $user->username = 'mode';
        $user->password = md5('123456');
        $user->loginip = request()->ip();
        var_dump($user->save());
        // 批量设置
        var_dump($user->save([
            'email'=>'56682@qq.com',
            'username'=>'111',
            'password'=>'111',
            'loginip'=>request()->ip()
        ]));
    }

    /**
     * 静态方法创建数据
     */
    public function curlDemo02()
    {
        // create方法的返回值是User模型的对象实例，而save方法调用的时候本身就在对象实例里面。
        $user = User::create([
            'email'=>'56682@qq.com',
            'username'=>'UserName'.rand(000,999),
            'password'=>'111',
            'loginip'=>request()->ip()
        ]);
        // 获取用户的主键数据
        echo $user->id;
    }

    /**
     * 模型读取Read
     */
    public function curlDemo03()
    {
        // Db类的find方法返回的是一个数组，模型类的get方法返回的是一个User模型对象实例
        // 模型的读取操作一般使用静态方法读取即可，返回模型对象实例。
        $user = User::get(178);
        echo $user->id;
        echo $user->username;

        // 模型实现读取多个记录
        $users = User::where('id', '>', 1)
            ->limit(5)
            ->select();
        // 遍历读取用户数据
        foreach ($users as $user) {
            echo $user->id;
            echo $user->username;
        }

        // 查询用户数据集
        // 相当于 Db::table('user')->select([1,2,3]);
        $users = User::all([178, 231, 232]);
        foreach ($users as $user) {
            echo $user->id;
            echo $user->username;
        }
    }

    /**
     * 模型更新Update
     * 【注意：】模型和Db更新方法的最大区别是模型的更新方法只会更新有变化的数据，没有变化的数据是不会更新到数据库的，如果所有数据都没变化，那么根本就不会去执行数据库的更新操作。
     */
    public function curlDemo04()
    {
        $user        = User::get(232);
        $user->username  = 'topthink111';
        $user->email = 'topthink@qq.com';
        // save方法返回影响的记录数
        var_dump($user->save());
        //静态调用，而update方法返回的则是模型的对象实例。
        $res = User::update([
            'username'  => 'topthink222',
            'email' => 'topthink@qq.com',
        ],['id'=>233]);
        var_dump($res);
    }

    /**
     * 模型 删除Delete
     * 【注意：】模型和Db更新方法的最大区别是模型的更新方法只会更新有变化的数据，没有变化的数据是不会更新到数据库的，如果所有数据都没变化，那么根本就不会去执行数据库的更新操作。
     */
    public function curlDemo05()
    {
        $user = User::get(233);
        //var_dump($user->delete());

        // 静态实现
        //var_dump(User::destroy(232));

        // 根据主键删除多个数据
        //User::destroy([244, 243, 242]);

        // 指定条件删除数据
//        User::destroy([
//            'password' => 111,
//        ]);

        // 使用闭包条件
        User::destroy(function ($query) {
            $query->where('id', '>', 0)
                ->where('username', 111);
        });
    }

    /**
     * 使用查询构造器
     */
    public function curlDemo06()
    {
        $user = User::where('username', 'like', '%先生')
            ->where('id', 'between', [1, 335])
            ->order('id desc')
            ->limit(3)
            ->select();
        halt($user);
    }

    /**
     * 获取器和修改器
     */
    public function curlDemo07()
    {
        $user = OpenUser::get(67);
        echo $user->create_time.PHP_EOL;
        // 要获取原始数据的值
        echo $user->getData('create_time').PHP_EOL;
        echo $user->user_app;
        echo $user->type;
    }

    /**
     * 自动时间字段
     */
    public function curlDemo08()
    {
        $user = new OpenUser();
        $user->open_id = '111111';
        $user->account = '111111';
        $user->avatar = '111111-avatar';
        // 会自动写入create_time和update_time字段
        var_dump($user->save());
        // 更新用户数据
        $user->account = 'topthink';
        // 会自动更新update_time字段
        $user->save();
    }

    /**
     * 自动时间字段
     */
    public function curlDemo09()
    {
        $user = OpenUser::get(67);
        // 追加额外的（获取器）属性
        $data = $user->append(['level'], true)->toArray();
        dump($data);
    }

    /**
     * 第七章：模型高级用法-条件查询
     */
    public function curlDemo10()
    {
        $user = OpenUser::where(67)->find();
        //dump($user);
        // 调用动态查询方法
        //var_dump(User::getByUpdateTime('1515138238'));

        // 查询数据集
        dump(OpenUser::where('id', '>', 0)->limit(10)->order('id desc')->select()->toArray());
    }

    /**
     * 第七章：字段过滤
     */
    public function curlDemo11()
    {
        // 获取当前用户对象
        $user = OpenUser::get(request()->session('open_user_id'));

        // 获取当前用户对象
        $user = User::get(request()->session('user_id'));

        // 只允许更新用户的nickname和address数据
        $user->allowField(['nickname', 'address'])
            ->data(requst()->param(), true)
            ->save();

        // 如果仅仅是希望去除数据表之外的字段，可以使用，只允许更新数据表字段数据
        $user->allowField(true)
            ->data(requst()->param(), true)
            ->save();
    }


}