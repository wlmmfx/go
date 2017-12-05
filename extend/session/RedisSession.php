<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/4 19:16
 * |  Mail: Overcome.wan@Gmail.com
 * |  Function: 实现共享session操作
 * |  Help: https://segmentfault.com/a/1190000011558000
 * '------------------------------------------------------------------------------------------------------------------*/

namespace session;


class RedisSession
{
    /**
     * 保存session的数据库表的信息
     */
    private $_options = [
        'handler' => null, //数据库连接句柄
        'host' => null,
        'port' => null,
        'lifeTime' => null,
        'prefix' => 'PHPREDIS_SESSION:'
    ];

    /**
     * 构造函数
     * redisSession constructor.
     * @param array $option
     */
    public function __construct($option = [])
    {
        if (!class_exists('redis', false)) die("redis扩展未安装，请安装扩展");
        if (!isset($option['lifeTime']) || $option['lifeTime'] <= 0) {
            $option['lifeTime'] = ini_get('session.gc_maxlifetime');
        }
        // array_merge() 函数把一个或多个数组合并为一个数组
        $this->_options = array_merge($this->_options, $option);
    }

    /**
     * 开始使用该驱动的session
     * @return bool
     */
    public function begin()
    {
        if ($this->_options['host'] === null || $this->_options['port'] === null || $this->_options['lifeTime'] === null) {
            return false;
        }
        //设置session处理函数
        session_set_save_handler(
            [$this, 'open'],
            [$this, 'close'],
            [$this, 'read'],
            [$this, 'write'],
            [$this, 'destory'],
            [$this, 'gc']
        );
    }

    /**
     * 自动开始回话或者session_start()开始回话后第一个调用的函数
     * @param $savePath 默认的保存路径
     * @param $sessionName 默认的参数名，PHPSESSID
     * @return bool
     */
    public function open($savePath, $sessionName){
        // is_resource 用于检测变量是否为资源类型
        if(is_resource($this->_options['handler'])) return true;
        //连接redis
        $redisHandle = new \Redis();
        $redisHandle->connect($this->_options['host'], $this->_options['port']);
        if(!$redisHandle) return false;

        $this->_options['handler'] = $redisHandle;
        //$this->gc(null);
        return true;
    }

    /**
     * 类似于析构函数，在write之后调用或者session_write_close()函数之后调用
     * @return mixed
     */
    public function close(){
        return $this->_options['handler']->close();
    }

    /**
     * 读取session信息
     * @param $sessionId 通过该Id唯一确定对应的session数据
     * @return mixed session信息/空串
     */
    public function read($sessionId){
        $sessionId = $this->_options['prefix'].$sessionId;
        return $this->_options['handler']->get($sessionId);
    }

    /**
     * 写入或者修改session数据
     * @param $sessionId 要写入数据的session对应的id
     * @param $sessionData 要写入的数据，已经序列化过了
     * @return mixed
     */
    public function write($sessionId, $sessionData){
        $sessionId = $this->_options['prefix'].$sessionId;
        return $this->_options['handler']->setex($sessionId, $this->_options['lifeTime'], $sessionData);
    }

    /**
     * 主动销毁session会话
     * @param $sessionId
     * @return bool
     */
    public function destory($sessionId){
        $sessionId = $this->_options['prefix'].$sessionId;
//        $array = $this->print_stack_trace();
//        log::write($array);
        return $this->_options['handler']->delete($sessionId) >= 1 ? true : false;
    }

    /**
     * 清理回话中的过期数据
     * @param $lifeTime
     * @return bool
     */
    public function gc($lifeTime){
        //获取所有sessionid，让过期的释放掉
        //$this->_options['handler']->keys("*");
        return true;
    }

    /**
     * 打印堆栈信息
     * @return string
     */
    public function print_stack_trace()
    {
        $array = debug_backtrace();
        //截取用户信息
        $var = $this->read(session_id());
        $s = strpos($var, "index_dk_user|");
        $e = strpos($var, "}authId|");
        $user = substr($var,$s+14,$e-13);
        $user = unserialize($user);
        //print_r($array);//信息很齐全
        unset ( $array [0] );
        if(!empty($user)){
            $traceInfo = $user['id'].'|'.$user['user_name'].'|'.$user['user_phone'].'|'.$user['presona_name'].'++++++++++++++++\n';
        }else{
            $traceInfo = '++++++++++++++++\n';
        }
        $time = date ( "y-m-d H:i:m" );
        foreach ( $array as $t ) {
            $traceInfo .= '[' . $time . '] ' . $t ['file'] . ' (' . $t ['line'] . ') ';
            $traceInfo .= $t ['class'] . $t ['type'] . $t ['function'] . '(';
            $traceInfo .= implode ( ', ', $t ['args'] );
            $traceInfo .= ")\n";
        }
        $traceInfo .= '++++++++++++++++';
        return $traceInfo;
    }


}