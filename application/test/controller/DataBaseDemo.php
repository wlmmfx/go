<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/1/4 14:28
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\test\controller;


use app\common\model\Admin;
use think\Controller;
use think\Db;

class DataBaseDemo extends Controller
{
    /**
     * 【建议】实际开发中其实并不建议
     * Db类进行数据库运行原生SQL操作了
     */
    public function demo1()
    {
        $res = Db::query('select * from resty_user where id=178');
        halt($res);
    }

    /**
     * 使用原生查询的时候最好使用参数绑定避免SQL注入
     * [ SQL ] select * from resty_user where id='178' [ RunTime:0.016206s ]
     */
    public function demo2()
    {
        $res = Db::query('select * from resty_user where id=?', [178]);
        halt($res);
    }

    /**
     * 支持命名占位符绑定
     * [ SQL ] select * from resty_user where id='178' [ RunTime:0.013648s ]
     */
    public function demo3()
    {
        $res = Db::query('select * from resty_user where id=:id', ['id' => 178]);
        halt($res);
    }

    /**
     * 参数绑定默认都是使用的字符串，如果需要指定为数字类型，可以使用下面的方式
     * [ SQL ] select * from resty_user where id=178 [ RunTime:0.023388s ]
     */
    public function demo4()
    {
        // 如果没有查询到任何数据，返回值就是一个空数组。
        $res = Db::query('select * from resty_user where id=:id', ['id' => [178, \PDO::PARAM_INT]]);
        halt($res);
    }

    /**
     * 插入
     * execute方法的返回值就比较单纯，一般就是返回影响（包括新增和更新）的记录数，如果没有影响任何记录，则返回值为0，
     * 所以千万不要用布尔值来判断execute是否执行成功，事实上，在5.0里面不需要判断是否成功，因为如果发生错误一定会抛出异常。
     */
    public function demo5()
    {
        // 如果没有查询到任何数据，返回值就是一个空数组。
        $res = Db::execute('insert into resty_user(username, password) values (:username, :password)', ['username' => '111', 'password' => 'topthink']);
        halt($res);
    }


    /**
     * 查询构造器
     * [1] Db类的任何方法都会自动调用connect方法返回连接对象实例，然后调用连接对象的查询构造器方法会自动实例化查询类。
     * [2] 数据库操作就是用Db类，至于内部怎么相互调用完全不需要操心。
     * [3] 辅助方法（辅助查询用的，也称为链式方法，例如table、field、where、order和limit等方法）
     * [4] 真正的查询方法（find、select、insert、update和delete方法）
     * [5] 查询方法是必须的，而辅助方法是可选的，并且辅助方法必须在查询方法之前被调用，并且在查询调用之后自动失效。
     */
    public function queryDemo01()
    {
        // 插入单个记录
        Db::table('data')
            ->insert(['id' => 8, 'name' => 'thinkphp']);
        // 插入多个记录
        Db::table('data')
            ->insertAll([
                ['id' => 9, 'name' => 'thinkphp'],
                ['id' => 10, 'name' => 'topthink'],
            ]);
    }

    public function queryDemo02()
    {
        // 查询多个数据
        $list = Db::table('resty_user')
            ->where('id', 'in', [178, 227, 228])
            ->select();
        dump($list);
        $my_array = array("Dog", "Cat", "Horse");
        // 把数组中的值赋给一些变量
        list($a, $b, $c) = $my_array;
        // 直接传入多个主键的值查询
        $list = Db::table('resty_user')
            ->select([1, 5, 8]);
    }

    /**
     * 获取记录的某个字段值
     */
    public function queryDemo03()
    {
        // 返回某个字段的值
        $list = Db::table('resty_user')->where('id', 178)->value('username');
        // 获取name列的数组
        $list2 = Db::table('resty_user')->where('status', 0)->column(['username', 'email', 'password']);
        // 获取记录某个列的值
        halt($list2);
    }

    /**
     * 链式操作方法返回的是当前对象实例
     */
    public function queryDemo04()
    {
        $list = Db::table('resty_user')
            ->where('id', '>', 1)
            ->order('id', 'desc')
            ->limit(8)
            ->select();
        halt($list);
    }

    /**
     * 获取查询SQL
     */
    public function queryDemo05()
    {
        $list = Db::table('resty_user')
            ->fetchSql(true)
            ->where('id', '>', 1)
            ->order('id', 'desc')
            ->limit(8)
            ->select();
        var_dump($list);
        //返回PDOStatement对象
        $pdo = Db::table('resty_user')
            ->fetchPdo(true)
            ->field('username')
            ->where('id', 178)
            ->select();
        var_dump($pdo);
        // 最终都只是返回PDOStatement对象，然后自己进行查询。
        $result = $pdo->fetchColumn();
        var_dump($result);
    }

    /**
     * 同一字段多个查询条件
     */
    public function queryDemo06()
    {
        $list = Db::table('resty_user')
            ->fetchSql(false)
            ->whereOr('username', ['like', '陈宇%'], ['like', '%先生'])
            ->where('id', ['>', 0], ['<>', 100], 'or')
            ->find();
        halt($list);
    }

    /**
     * 动态查询
     */
    public function queryDemo07()
    {
        $list = Db::table('resty_user')->getByEmail('756684177@qq.com');
        halt($list);
    }

    /**
     * 闭包查询如何传入变量
     */
    public function queryDemo08()
    {
        $id = 178;
        $list = Admin::get(function ($query) use ($id){
            $query->where('id',$id);
        });
        halt($list);
    }

    /**
     * 如何查询一个字段值为NULL或者NOT NULL的数据？
     */
    public function queryDemo09()
    {
        // 查询 mobile 为NULL的数据
        $isNull = Db::table('resty_user')->whereNull('mobile')->select();
        $notNull = Db::table('resty_user')->whereNotNull('mobile')->select();
        halt($notNull);
    }
}