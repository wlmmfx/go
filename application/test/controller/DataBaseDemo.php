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


use think\Controller;
use think\Db;

class DataBaseDemo extends Controller
{
    /**
     * 【建议】实际开发中其实并不建议
     * Db类进行数据库运行原生SQL操作了
     */
    public function demo1(){
        $res = Db::query('select * from resty_user where id=178');
        halt($res);
    }

    /**
     * 使用原生查询的时候最好使用参数绑定避免SQL注入
     * [ SQL ] select * from resty_user where id='178' [ RunTime:0.016206s ]
     */
    public function demo2(){
        $res = Db::query('select * from resty_user where id=?',[178]);
        halt($res);
    }

    /**
     * 支持命名占位符绑定
     * [ SQL ] select * from resty_user where id='178' [ RunTime:0.013648s ]
     */
    public function demo3(){
        $res = Db::query('select * from resty_user where id=:id',['id'=>178]);
        halt($res);
    }

    /**
     * 参数绑定默认都是使用的字符串，如果需要指定为数字类型，可以使用下面的方式
     * [ SQL ] select * from resty_user where id=178 [ RunTime:0.023388s ]
     */
    public function demo4(){
        // 如果没有查询到任何数据，返回值就是一个空数组。
        $res = Db::query('select * from resty_user where id=:id',['id'=>[178,\PDO::PARAM_INT]]);
        halt($res);
    }

    /**
     * 插入
     * execute方法的返回值就比较单纯，一般就是返回影响（包括新增和更新）的记录数，如果没有影响任何记录，则返回值为0，
     * 所以千万不要用布尔值来判断execute是否执行成功，事实上，在5.0里面不需要判断是否成功，因为如果发生错误一定会抛出异常。
     */
    public function demo5(){
        // 如果没有查询到任何数据，返回值就是一个空数组。
        $res = Db::execute('insert into resty_user(username, password) values (:username, :password)',['username'=>'111','password'=>'topthink']);
        halt($res);
    }
}