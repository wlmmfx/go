<?php

/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/2 11:42
 * |  Mail: Overcome.wan@Gmail.com
 * |  Help: https://secure.php.net/manual/zh/function.session-set-save-handler.php
 * |  Function: 自定义会话管理器
 * '------------------------------------------------------------------------------------------------------------------*/
namespace session;

class MysqlSession implements \SessionHandlerInterface
{
    protected $_link;
    private $_lift_time; // 默认24分钟

    /**
     * Close the session
     * @since 5.4.0
     */
    public function close()
    {
        mysqli_close($this->link);
        return true;
    }

    /**
     * Destroy a session
     * @since 5.4.0
     */
    public function destroy($session_id)
    {
        $session_id = mysqli_escape_string($this->_link, $session_id);
        $sql = "delete from resty_session WHERE session_id=" . $session_id;
        mysqli_query($this->_link, $sql);
        return mysqli_affected_rows($this->_link) == 1;
    }

    /**
     * Cleanup old sessions
     * @since 5.4.0
     */
    public function gc($maxlifetime)
    {
        $sql = "delete from resty_session WHERE expire<" . time();
        mysqli_query($this->_link, $sql);
        if(mysqli_affected_rows($this->_link)>0) return true;
        return false;
    }

    /**
     * Initialize session
     * @since 5.4.0
     */
    public function open($save_path,$session_name)
    {
        try{
            $this->_lift_time = get_cfg_var("session.gc_maxlifetime");
            $this->_link = mysqli_connect('localhost', 'root', '');
            mysqli_set_charset($this->_link, 'utf8');
            mysqli_select_db($this->_link, 'resty');
            if ($this->_link == true){
                return true;
            }
        }catch (\Exception $e){
            $error = $e->getMessage();
        }
        return $error;
    }

    /**
     * Read session data
     * @since 5.4.0
     */
    public function read($session_id)
    {
        $sql = "select * from resty_session WHERE session_id='{$session_id}'";
        $result = mysqli_query($this->_link, $sql);
        if (!$result || mysqli_num_rows($result) == 1) {
            /**
             * mysql_fetch_assoc() 函数从结果集中取得一行作为关联数组。
             * 返回根据从结果集取得的行生成的关联数组，如果没有更多行，则返回 false。
             */
            return mysqli_fetch_assoc($result)['data'];
        }
    }

    /**
     * Write session data
     * @since 5.4.0
     */
    public function write( $session_id,  $session_data)
    {
        $newExp = $this->_lift_time + time();
        // mysql_escape_string --  转义一个字符串用于 mysql_query
        $session_id = mysqli_escape_string($this->_link, $session_id);
        //首选查询session_id 是否存在，如果存在则更新数据，否则是第一次写入数据
        $sql = "select * from resty_session WHERE session_id=" . $session_id;
        echo $sql;
        $result = mysqli_query($this->_link, $sql);
        /**
         * mysql_num_rows() 返回结果集中行的数目。此命令仅对 SELECT 语句有效。
         * 要取得被 INSERT，UPDATE 或者 DELETE 查询所影响到的行的数目，用 mysql_affected_rows()。
         */
        if (!$result || mysqli_num_rows($result) == 1) {
            $sql = "update resty_session set expires='{$newExp}',data='{$session_data}' WHERE id=" . $session_id;
        } else {
            $sql = "insert resty_session values('{$session_id}','{$session_data}','{$newExp}')";
        }
        mysqli_query($this->_link, $sql);
        return mysqli_affected_rows($this->_link) == 1;
    }
}