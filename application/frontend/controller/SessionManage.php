<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/2 11:41
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\frontend\controller;


use session\MysqlSession;

class SessionManage
{
    /**
     * url : https://www.tinywan.com/frontend/session_manage/demo001
     * @return int
     */
    public function demo001()
    {
        // 可以通过调用函数 session_start() 来手动开始一个会话
        session_start();
        // 设置session数据
        $_SESSION['name'] = 'Tinywan';
        $_SESSION['age'] = 24;
        $_SESSION['Github'] = 'github.com/Tinywan';
        echo "Session 的名字：",session_name(),"<br/>";
        echo "Session id：",session_id(),"<br/>";
        // 获取session中的数据
        var_dump($_SESSION);
        return 1;
    }

    public function demo002()
    {
        session_start();
        $_SESSION['cookie_name'] = 'Cookie';
        $_SESSION['cookie_time'] = "1小时";
        //保存一个数组
        $data =[
            'id'=>1002,
            'age'=>24,
            'name'=>"Tinywan"
        ];
        $_SESSION['user1002'] = $data;
        // 设置Session 保存周期
        setcookie(session_name(),session_id(),time()+3600);
        print_r($_SESSION);
        return 1;
    }

    /**
     * session MySQl存储
     * @return int
     */
    public function sessionSaveMySQL()
    {
        try{
        $session = new MysqlSession();
        // 自定义回话管理器
        ini_set('session.save_handler','user');
        session_set_save_handler($session,true);
        session_start();
        $_SESSION['age'] = 24;
        $_SESSION['username'] = "Tinywan";
        $save_path=1; $name=1;
        var_dump($session);
        }catch (\Exception $e){
            var_dump($e->getMessage());
        }
    }
}